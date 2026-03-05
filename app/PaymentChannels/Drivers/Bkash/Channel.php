<?php

namespace App\PaymentChannels\Drivers\Bkash;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Channel extends BasePaymentChannel implements IChannel
{
    protected $currency;
    protected $test_mode;

    protected $base_url;           // e.g. https://tokenized.pay.bka.sh
    protected $app_key;            // bKash App Key
    protected $app_secret;         // bKash App Secret
    protected $username;           // bKash Username
    protected $password;           // bKash Password

    protected $order_session_key;
    protected $payment_session_key;

    protected array $credentialItems = [
        'base_url',
        'app_key',
        'app_secret',
        'username',
        'password',
    ];

    /**
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        // bKash supports BDT
        $this->currency = 'BDT';
        $this->order_session_key = 'bkash.payments.order_id';
        $this->payment_session_key = 'bkash.payments.payment_id';
        $this->setCredentialItems($paymentChannel);
    }

    private function getBaseUri(): string
    {
        // Auto-pick environment if base_url is empty
        $base = rtrim(trim((string) $this->base_url));
        if ($base === '') {
            $base = $this->test_mode ? 'https://tokenized.sandbox.bka.sh' : 'https://tokenized.pay.bka.sh';
        }
        if (!str_starts_with($base, 'http://') && !str_starts_with($base, 'https://')) {
            $base = 'https://' . $base;
        }
        return rtrim($base, '/');
    }

    private function grantToken(): array
    {
        $endpoint = $this->getBaseUri() . '/v1.2.0-beta/tokenized/checkout/token/grant';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'username' => (string) $this->username,
            'password' => (string) $this->password,
        ];

        $body = [
            'app_key' => (string) $this->app_key,
            'app_secret' => (string) $this->app_secret,
        ];

        $response = Http::withHeaders($headers)->post($endpoint, $body);

        if ($response->failed()) {
            \Log::error('bKash grantToken failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('bKash token grant failed');
        }

        return $response->json();
    }

    private function createPayment(string $idToken, Order $order, float $amount, string $payerReference): array
    {
        $endpoint = $this->getBaseUri() . '/v1.2.0-beta/tokenized/checkout/create';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => $idToken,
            'X-APP-Key' => (string) $this->app_key,
        ];

        $payload = [
            'mode' => '0011',
            'payerReference' => $payerReference,
            'callbackURL' => url('/payments/verify/Bkash'),
            'amount' => number_format($amount, 2, '.', ''),
            'currency' => $this->currency,
            'intent' => 'sale',
            'merchantInvoiceNumber' => 'order-' . $order->id,
        ];

        $response = Http::withHeaders($headers)->post($endpoint, $payload);

        if ($response->failed()) {
            \Log::error('bKash createPayment failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('bKash create payment failed');
        }

        return $response->json();
    }

    private function executePayment(string $idToken, string $paymentId): array
    {
        $endpoint = $this->getBaseUri() . '/v1.2.0-beta/tokenized/checkout/execute';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => $idToken,
            'X-APP-Key' => (string) $this->app_key,
        ];

        $payload = [
            'paymentID' => $paymentId,
        ];

        $response = Http::withHeaders($headers)->post($endpoint, $payload);

        if ($response->failed()) {
            \Log::error('bKash executePayment failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('bKash execute payment failed');
        }

        return $response->json();
    }

    public function paymentRequest(Order $order)
    {
        try {
            $amount = $this->makeAmountByCurrency($order->total_amount, $this->currency);

            $tokenResp = $this->grantToken();
            $idToken = $tokenResp['id_token'] ?? $tokenResp['token'] ?? null;
            if (!$idToken) {
                throw new \RuntimeException('bKash token missing in response');
            }

            $payerReference = preg_replace('/[^0-9]/', '', (string) ($order->user->mobile ?? '')); // optional
            if (empty($payerReference)) { $payerReference = (string) $order->user_id; }

            $createResp = $this->createPayment($idToken, $order, (float) $amount, $payerReference);
            $bkashUrl = trim((string) ($createResp['bkashURL'] ?? $createResp['paymentURL'] ?? ''));
            $paymentId = (string) ($createResp['paymentID'] ?? '');

            if ($bkashUrl === '' || $paymentId === '') {
                \Log::error('bKash createPayment missing redirect url or paymentID', [ 'resp' => $createResp ]);
                throw new \RuntimeException('bKash create payment response invalid');
            }

            session()->put($this->order_session_key, $order->id);
            session()->put($this->payment_session_key, $paymentId);

            return str_replace(["\r", "\n"], '', $bkashUrl);
        } catch (\Throwable $e) {
            \Log::error('bKash paymentRequest failed', [ 'message' => $e->getMessage() ]);
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
            return redirect()->back()->with(['toast' => $toastData])->withInput();
        }
    }

    public function verify(Request $request)
    {
        $user = auth()->user();
        $sessionOrderId = session()->get($this->order_session_key, null);
        $sessionPaymentId = session()->get($this->payment_session_key, null);
        session()->forget($this->order_session_key);
        session()->forget($this->payment_session_key);

        $order = null;

        try {
            $paymentId = $request->get('paymentID', $sessionPaymentId);
            if (!$paymentId) {
                throw new \RuntimeException('bKash paymentID not found');
            }

            $tokenResp = $this->grantToken();
            $idToken = $tokenResp['id_token'] ?? $tokenResp['token'] ?? null;
            if (!$idToken) {
                throw new \RuntimeException('bKash token missing in response');
            }

            $execResp = $this->executePayment($idToken, $paymentId);
            $trxStatus = strtoupper((string) ($execResp['transactionStatus'] ?? ''));

            if (!empty($sessionOrderId)) {
                $order = Order::where('id', $sessionOrderId)
                    ->where('user_id', $user?->id)
                    ->first();
            }

            if ($order) {
                if (in_array($trxStatus, ['COMPLETED', 'AUTHORIZED'])) {
                    $order->update(['status' => Order::$paying]);
                } else {
                    $order->update(['status' => Order::$fail]);
                }
            }
        } catch (\Throwable $e) {
            \Log::error('bKash verify failed', [ 'message' => $e->getMessage() ]);
        }

        return $order;
    }
}


