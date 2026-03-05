<?php

namespace App\PaymentChannels\Drivers\Chapa;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Chapa\Chapa\Facades\Chapa as Chapa;

class Channel extends BasePaymentChannel implements IChannel
{
    protected $order_session_key;
    protected $currency;
    protected $test_mode;
    protected $reference;

    protected $public_key;
    protected $secret_key;
    protected $encryption_key;
    protected $merchant_id;

    protected array $credentialItems = [
        'public_key',
        'secret_key',
        'encryption_key',
        'merchant_id',
    ];

    /**
     * Channel constructor.
     * @param PaymentChannel $paymentChannel
     * https://developer.chapa.co/laravel-sdk
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->order_session_key = 'chapa.payments.order_id';
        $this->currency = "ETB";//currency(); // "ETB"
        $this->setCredentialItems($paymentChannel);
    }

    private function handleChapaReference()
    {
        config()->set('chapa.publicKey', $this->public_key);
        config()->set('chapa.secretKey', $this->secret_key);

        $this->reference = Chapa::generateReference();
    }

    public function paymentRequest(Order $order)
    {
        $this->handleChapaReference();

        $generalSettings = getGeneralSettings();
        $user = $order->user;
        $price = $this->makeAmountByCurrency($order->total_amount, $this->currency);


        $data = [
            'amount' => $price,
            'email' => $user->email,
            'tx_ref' => $this->reference,
            'currency' => $this->currency,
            'callback_url' => $this->makeCallbackUrl(),
            'first_name' => $user->full_name,
            'last_name' => "",
            "customization" => [
                "title" => 'Order ' . $order->id,
                "description" => "{$generalSettings['site_name']} payment",
            ]
        ];

        try {
            $payment = Chapa::initializePayment($data);

            if ($payment['status'] == 'success') {
                session()->put($this->order_session_key, $order->id);

                return $payment['data']['checkout_url'];
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function verify(Request $request)
    {
        $reference = $request->get('reference');
        $user = auth()->user();
        $orderId = session()->get($this->order_session_key, null);
        session()->forget($this->order_session_key);

        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($order)) {
            $orderStatus = Order::$fail;

            $verifyTransaction = Chapa::verifyTransaction($reference);

            if (!empty($verifyTransaction) and $verifyTransaction['status'] == 'success') {
                $orderStatus = Order::$paying;
            }

            $order->update([
                'status' => $orderStatus,
            ]);
        }

        return $order;
    }

    private function makeCallbackUrl()
    {
        return route("chapa.callback", ['reference' => $this->reference]);
    }
}
