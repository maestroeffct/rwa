<?php

namespace App\PaymentChannels\Drivers\Clickpay;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Clickpaysa\Laravel_package\Facades\paypage;
use Illuminate\Support\Facades\Auth;


class Channel extends BasePaymentChannel implements IChannel
{

    protected $order_session_key;
    protected $currency;
    protected $test_mode;
    protected $profile_id;
    protected $server_key;

    protected array $credentialItems = [
        'profile_id',
        'server_key',
    ];

    // https://github.com/clickpaysa/laravel-package

    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->order_session_key = 'clickpay.payments.order_id';
        $this->currency = currency(); // SAR
        $this->setCredentialItems($paymentChannel);
    }

    private function handleConfigs()
    {
        \Config::set('clickpay.profile_id', $this->profile_id);
        \Config::set('clickpay.server_key', $this->server_key);
        \Config::set('clickpay.currency', $this->currency);
    }


    public function paymentRequest(Order $order)
    {
        $this->handleConfigs();

        $generalSettings = getGeneralSettings();
        $user = $order->user;
        $amount = $this->makeAmountByCurrency($order->total_amount, $this->currency);

        try {
            $callbackUrl = $this->makeCallbackUrl();

            $pay = paypage::sendPaymentCode('all')
                ->sendTransaction('sale')
                ->sendCart($order->id, $amount, "{$generalSettings['site_name']} payment") // $cart_id, $amount, $cart_description
                ->sendCustomerDetails(
                    $user->full_name, // $name
                    $user->email, // $email
                    $user->mobile, // $phone
                    $user->address ?? 'no address', // $address
                    $user->getRegionByTypeId($user->city_id), // $city
                    $user->getRegionByTypeId($user->province_id), // $state
                    $user->getRegionByTypeId($user->country_id), // $country
                    '1234', // $zip
                    '1.1.1.1', // $ip
                )
                //->sendShippingDetails('Name', 'email@email.com', '0501111111', 'test', 'Riyadh', 'Riyadh', 'SA', '1234', '10.0.0.10')
                ->sendURLs($callbackUrl, $callbackUrl)
                ->sendLanguage('en')
                ->create_pay_page();

            session()->put($this->order_session_key, $order->id);

            return $pay;

        } catch (\Exception $e) {
            //dd($e->getMessage());
        }
    }

    private function makeCallbackUrl()
    {
        return url("/payments/verify/Clickpay");
    }

    public function verify(Request $request)
    {
        $this->handleConfigs();
        $data = $request->all();

        if (!empty($data['tranRef'])) {
            try {
                $transaction = Paypage::queryTransaction($data['tranRef']);

                if (!empty($transaction)) {
                    $orderId = $transaction->cart_id;

                    if (!empty($orderId)) {
                        $order = Order::where('id', $orderId)->first();

                        if (!empty($order)) {
                            Auth::loginUsingId($order->user_id);

                            $orderStatus = Order::$fail;

                            if ($transaction->success) {
                                $orderStatus = Order::$paying;
                            }

                            $order->update([
                                'status' => $orderStatus,
                                'payment_data' => json_encode($transaction),
                            ]);

                            return $order;
                        }
                    }
                }
            } catch (\Exception $e) {
                // dd($e->getMessage());
            }
        }

        return null;
    }

}
