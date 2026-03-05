<?php

namespace App\PaymentChannels\Drivers\Cintepay;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Guysolamour\Cinetpay\Cinetpay;
use Illuminate\Support\Facades\Auth;


class Channel extends BasePaymentChannel implements IChannel
{

    protected $order_session_key;
    protected $currency;
    protected $test_mode;
    protected $api_key;
    protected $site_id;
    protected $secret_key;

    protected array $credentialItems = [
        'api_key',
        'site_id',
        'secret_key',
    ];

    // https://github.com/guysolamour/laravel-cinetpay
    // https://github.com/cinetpay/cinetpay-php-legacy

    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->order_session_key = 'cintepay.payments.order_id';
        $this->currency = currency(); // XOF
        $this->setCredentialItems($paymentChannel);
    }

    private function handleConfigs()
    {
        \Config::set('cinetpay.api_key', $this->api_key);
        \Config::set('cinetpay.site_id', $this->site_id);
        \Config::set('cinetpay.urls.notify', $this->makeCallbackUrl("notify"));
        \Config::set('cinetpay.urls.return', $this->makeCallbackUrl("return"));
        \Config::set('cinetpay.urls.cancel', $this->makeCallbackUrl("cancel"));
    }


    public function paymentRequest(Order $order)
    {
        $this->handleConfigs();

        $generalSettings = getGeneralSettings();
        $user = $order->user;
        $amount = $this->makeAmountByCurrency($order->total_amount, $this->currency);

        try {
            //$transactionId = "order_{$order->id}";
            $transactionId = \Guysolamour\Cinetpay\Cinetpay::generateTransId();

            $cinetpay = Cinetpay::init()
                ->setTransactionId($transactionId)
                ->setAmount($amount)
                ->setCurrency($this->currency)
                ->setDesignation("{$generalSettings['site_name']} payment")
                ->setCustom($order->id)
                ->setNotifyUrl($this->makeCallbackUrl("notify"))
                ->setReturnUrl($this->makeCallbackUrl("return"))
                ->setCancelUrl($this->makeCallbackUrl("cancel"));

            $data = [
                'cinetpay' => $cinetpay,
            ];

            return view('design_1.web.cart.payment.channels.cinetpay', $data);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function makeCallbackUrl($status)
    {
        return url("/payments/verify/Cintepay?status={$status}");
    }

    public function verify(Request $request)
    {
        $this->handleConfigs();

        $data = $request->all();
        $transactionId = !empty($data['cpm_trans_id']) ? $data['cpm_trans_id'] : null;

        if (!empty($transactionId)) {
            try {
                // https://github.com/cinetpay/cinetpay-php-legacy
                $cinetpay = Cinetpay::init($this->site_id, $this->api_key);
                $cinetpay->setTransId($transactionId)->getPayStatus();

                $orderId = $cinetpay->getCustom();

                if (!empty($orderId)) {
                    $order = Order::query()->where('id', $orderId)->first();

                    if (!empty($order)) {
                        Auth::loginUsingId($order->user_id);

                        $orderStatus = Order::$fail;

                        if ($cinetpay->isValidPayment()) {
                            $orderStatus = Order::$paying;
                        }

                        $order->update([
                            'status' => $orderStatus,
                            'payment_data' => json_encode($data)
                        ]);
                    }

                    return $order;
                }
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        return null;
    }

}
