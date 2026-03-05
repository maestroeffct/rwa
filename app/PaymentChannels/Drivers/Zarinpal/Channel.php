<?php

namespace App\PaymentChannels\Drivers\Zarinpal;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;


class Channel extends BasePaymentChannel implements IChannel
{

    protected $order_session_key;
    protected $currency;
    protected $test_mode;
    protected $merchant_id;

    protected array $credentialItems = [
        'merchant_id',
    ];

    /**
     * Channel constructor.
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->order_session_key = 'zarinpal.payments.order_id';
        $this->currency = currency();
        $this->setCredentialItems($paymentChannel);
    }

    private function handleConfigs()
    {
        \Config::set('zarinpal.merchant_id', $this->merchant_id);
        \Config::set('zarinpal.merchant_id', $this->currency);
    }

    public function paymentRequest(Order $order)
    {
        $this->handleConfigs();
        $amount = $this->makeAmountByCurrency($order->total_amount, $this->currency);

        try {
            $response = zarinpal()
                ->merchantId($this->merchant_id)
                ->amount($amount)
                ->request()
                ->description(trans('public.paid_form_online_payment'))
                ->callbackUrl($this->makeCallbackUrl())
                ->mobile($order->user->mobile)
                ->email($order->user->mobile)
                ->send();

            if ($response->success()) {
                session()->put($this->order_session_key, $order->id);

                return $response->redirect();
            }

        } catch (\Exception $exception) {
            dd($exception);
        }

        $toastData = [
            'title' => trans('cart.fail_purchase'),
            'msg' => trans('update.ZarrinPal_gateway_error'),
            'status' => 'error'
        ];
        return redirect()->back()->with(['toast' => $toastData])->withInput();
    }

    private function makeCallbackUrl()
    {
        return url("/payments/verify/Zarinpal");
    }

    public function verify(Request $request)
    {
        $this->handleConfigs();
        $user = auth()->user();

        $authority = $request->get('Authority');
        $status = $request->get('Status');

        $orderId = session()->get($this->order_session_key, null);
        session()->forget($this->order_session_key);

        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($order)) {
            try {
                $amount = $this->makeAmountByCurrency($order->total_amount, $this->currency);

                $response = zarinpal()
                    ->merchantId($this->merchant_id)
                    ->amount($amount)
                    ->verification()
                    ->authority($authority)
                    ->send();

                $orderStatus = Order::$fail;

                if ($response->success()) {
                    $orderStatus = Order::$paying;
                }

                $order->update([
                    'status' => $orderStatus,
                ]);

                return $order;
            } catch (\Exception $exception) {
                //dd($exception->getMessage());
            }
        }

        return null;
    }
}
