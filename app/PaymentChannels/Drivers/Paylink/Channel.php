<?php

namespace App\PaymentChannels\Drivers\Paylink;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Paylink\Client as PaylinkClient;

class Channel extends BasePaymentChannel implements IChannel
{

    protected $order_session_key;
    protected $currency;
    protected $client;
    protected $test_mode;
    protected $api_key;
    protected $api_secret;


    protected array $credentialItems = [
        'api_key',
        'api_secret',
    ];

    /**
     * Channel constructor.
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->order_session_key = 'paylink.payments.order_id';
        $this->currency = currency();
        $this->setCredentialItems($paymentChannel);
    }

    private function handleClient()
    {
        // https://paylinksa.readme.io/docs/authentication

        $client = new PaylinkClient([
            'vendorId'  =>  $this->api_key,
            'vendorSecret'  =>  $this->api_secret,
            'persistToken'  =>  true, // false by default if not given
            'environment'  =>  $this->test_mode ? 'testing' : 'prod',
        ]);

        $this->client = $client;
    }

    /**
     * @throws \Exception
     */
    public function paymentRequest(Order $order)
    {
        $this->handleClient();

        // Send purchase request
        try {
            $user = $order->user;

            $products = [];
            foreach ($order->orderItems as $orderItem) {
                $products[] = [
                    'description' => 'Cart Item ' . $orderItem->id,
                    'imageSrc' => '',
                    'price' => $this->makeAmountByCurrency($orderItem->amount, $this->currency),
                    'qty' => 1,
                    'title' => 'Order ' . $orderItem->id,
                ];
            }

            $data = [
                'amount' => $this->makeAmountByCurrency($order->total_amount, $this->currency),
                'callBackUrl' => $this->makeCallbackUrl(),
                'clientEmail' => $user->email,
                'clientMobile' => $user->mobile,
                'clientName' => $user->full_name,
                'note' => 'This invoice is for client Cart.',
                'orderNumber' => $order->id,
                'products' => $products,
            ];

            $response = $this->client->createInvoice($data);

            if ($response) {
                $transactionNo = $response['transactionNo'];
                session()->put($this->order_session_key, $transactionNo);

                return $response['url'];
            }
        } catch (\Exception $exception) {
            //dd($exception);
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return null;
    }

    private function makeCallbackUrl(?Order $order = null)
    {
        // Include order_id to reduce reliance on session during callback
        if ($order && $order->id) {
            return url("/payments/verify/Paylink?order_id=" . $order->id);
        }
        return url("/payments/verify/Paylink");
    }

    public function verify(Request $request)
    {
        $this->handleClient();

        try {
            // Try to get transaction number from callback payload first, then fallback to session
            $transactionNo = $request->input('transactionNo')
                ?? $request->input('transaction_no')
                ?? $request->input('transaction')
                ?? session()->get($this->order_session_key, null);

            // Clear session key to avoid reuse
            session()->forget($this->order_session_key);

            if (empty($transactionNo)) {
                throw new \Exception('Missing transaction number for Paylink verification');
            }

            $response = $this->client->getInvoice($transactionNo);

            if (!empty($response) && !empty($response['gatewayOrderRequest'])) {
                $orderId = $response['gatewayOrderRequest']['orderNumber'] ?? $request->input('order_id');
                $paymentStatus = strtolower($response['orderStatus'] ?? '');

                if (empty($orderId)) {
                    throw new \Exception('Missing order id in Paylink verification response');
                }

                $order = Order::where('id', $orderId)->first();

                if (!empty($order)) {
                    $status = Order::$fail;

                    // Treat PAID/SUCCESS-like statuses as successful; Pending/Created are not
                    $paidStatuses = ['paid', 'success', 'successful'];
                    if (in_array($paymentStatus, $paidStatuses, true)) {
                        $status = Order::$paying;
                    }

                    $order->update([
                        'status' => $status,
                    ]);
                }

                return $order;
            }

        } catch (\Exception $exception) {
            //dd($exception);
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return null;
    }
}
