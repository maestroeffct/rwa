<?php

namespace App\PaymentChannels\Drivers\Paymob;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class Channel extends BasePaymentChannel implements IChannel
{
    use PaymobTrait;

    protected $currency;
    protected $test_mode;
    protected $order_session_key;

    protected $api_key;
    protected $username;
    protected $password;
    protected $integration_id;
    protected $iframe_id;
    protected $HMAC;


    protected array $credentialItems = [
        'api_key',
        'username',
        'password',
        'integration_id',
        'iframe_id',
        'HMAC',
    ];

    // https://github.com/samir-hussein/paymob
    // https://github.com/ctf0/laravel-paymob
    // https://acceptdocs.paymobsolutions.com/docs/card-payments

    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->currency = currency(); // EGP
        $this->setCredentialItems($paymentChannel);
        $this->order_session_key = 'paymob.payments.order_id';
    }

    public function paymentRequest(Order $order)
    {
        //$generalSettings = getGeneralSettings();
        $user = $order->user;
        $amount = $this->makeAmountByCurrency($order->total_amount, $this->currency);

        $errorMsg = null;
        try {

            $paymentAuth = $this->AuthenticationRequest();

            $orderItems = [];

            foreach ($order->orderItems as $orderItem) {
                $orderItems[] = [
                    'name' => "order_item_{$orderItem->id}",
                    'amount_cents' => $orderItem->total_amount * 100,
                    'quantity' => 1,
                    "description" => "order_item_{$orderItem->id}",
                ];
            }

            $paymentOrder = $this->OrderRegistrationAPI([
                'auth_token' => $paymentAuth->token,
                'amount_cents' => $amount * 100,
                'currency' => $this->currency,
                'delivery_needed' => false,
                'merchant_order_id' => $order->id,
                'items' => $orderItems
            ]);


            $paymentKey = $this->PaymentKeyRequest([
                'auth_token' => $paymentAuth->token,
                'amount_cents' => $amount * 100,
                'currency' => $this->currency,
                'order_id' => $paymentOrder->id,
                "billing_data" => [ // put your client information
                    "apartment" => "01",
                    "email" => $user->email,
                    "floor" => "01",
                    "first_name" => $user->full_name,
                    "street" => "001",
                    "building" => "002",
                    "phone_number" => $user->mobile,
                    "shipping_method" => null,
                    "postal_code" => "01898",
                    "city" => $user->getRegionByTypeId($user->city_id) ?? 'Jaskolskiburgh',
                    "country" => $user->getRegionByTypeId($user->country_id) ?? 'CR',
                    "last_name" => " user",
                    "state" => $user->getRegionByTypeId($user->province_id) ?? 'Utah',
                ]
            ]);

            if (!empty($paymentKey) and !empty($paymentKey->token)) {
                $data = [
                    'token' => $paymentKey->token,
                    'iframeId' => $this->iframe_id,
                ];
                return view('design_1.web.cart.payment.channels.paymob', $data);
            } else if (!empty($paymentKey) and !empty($paymentAuth->message)) {
                $errorMsg = $paymentAuth->message;
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        $toastData = [
            'title' => trans('cart.fail_purchase'),
            'msg' => $errorMsg ?? trans('update.gateway_error_please_contact_support'),
            'status' => 'error'
        ];
        return redirect()->back()->with(['toast' => $toastData])->withInput();
    }

    private function makeCallbackUrl()
    {
        return url("/payments/verify/Paymob");
    }

    public function verify(Request $request)
    {
        $data = $request->all();

        if (!empty($data)) {
            $requestHmac = !empty($data['hmac']) ? $data['hmac'] : '';

            try {
                $calcHmac = $this->calcHMAC($request);

                if ($requestHmac == $calcHmac) {
                    $orderId = $data['merchant_order_id'];
                    $amount_cents = $data['amount_cents'];
                    $data['transaction_id'] = $data['id'];

                    $user = auth()->user();

                    $order = Order::where('id', $orderId)
                        ->where('user_id', $user->id)
                        ->first();

                    if (!empty($order)) {
                        $orderStatus = Order::$fail;

                        if ($data['success'] and ($order->total_amount * 100) == $amount_cents) {
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
