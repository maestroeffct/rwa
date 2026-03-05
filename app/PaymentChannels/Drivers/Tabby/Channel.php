<?php

namespace App\PaymentChannels\Drivers\Tabby;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tabby\Services\TabbyService;
use Tabby\Models\Buyer as TabbyBuyer;
use Tabby\Models\Order as TabbyOrder;
use Tabby\Models\ShippingAddress as TabbyShippingAddress;
use Tabby\Models\OrderItem as TabbyOrderItem;


class Channel extends BasePaymentChannel implements IChannel
{
    protected $currency;
    protected $test_mode;
    protected $merchantCode;
    protected $publicKey;
    protected $secretKey;
    protected $order_session_key;


    protected array $credentialItems = [
        'merchantCode',
        'publicKey',
        'secretKey',
    ];

    /**
     * @url https://github.com/sultan-algarbi/tabby-laravel-package
     *
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->currency = $this->resolveCurrency($paymentChannel);
        $this->order_session_key = 'tabby.payments.order_id';
        $this->setCredentialItems($paymentChannel);
    }

    private function resolveCurrency(PaymentChannel $paymentChannel): string
    {
        $siteCurrency = strtoupper(currency());
        $allowedCurrencies = is_array($paymentChannel->currencies) ? array_map('strtoupper', $paymentChannel->currencies) : [];

        if (in_array($siteCurrency, $allowedCurrencies, true)) {
            return $siteCurrency;
        }

        if (!empty($allowedCurrencies)) {
            return $allowedCurrencies[0];
        }

        // Fallback for safety
        return 'AED';
    }

    private function formatPhoneToE164(?string $rawPhone): string
    {
        $defaultDial = '+971'; // AED default
        if (strtoupper($this->currency) === 'SAR') {
            $defaultDial = '+966';
        } elseif (strtoupper($this->currency) === 'AED') {
            $defaultDial = '+971';
        }

        $digits = preg_replace('/[^0-9]/', '', (string) $rawPhone);
        if (empty($digits)) {
            // Fallback minimal valid-looking phone to pass API schema
            return $defaultDial . '555000123';
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }
        if (str_starts_with($digits, '971') || str_starts_with($digits, '966')) {
            return '+' . $digits;
        }

        return $defaultDial . $digits;
    }

    public function paymentRequest(Order $order)
    {
        $user = $order->user;
        $generalSettings = getGeneralSettings();
        $price = $this->makeAmountByCurrency($order->total_amount, $this->currency);

        // We'll call Tabby API directly to ensure strict JSON formatting

        try {
            $buyerPhone = $this->formatPhoneToE164($user->mobile ?? ($generalSettings['site_phone'] ?? ''));
            $buyerEmail = !empty($user->email) ? $user->email : (($generalSettings['site_email'] ?? null) ?: ('user'.$user->id.'@example.com'));
            $buyerName = !empty($user->full_name) ? $user->full_name : 'Customer';

            $tabbyBuyer = new TabbyBuyer(
                phone: $buyerPhone,
                email: $buyerEmail,
                name: $buyerName,
                dob: null,
            );

            $allOrderItems = [];

            foreach ($order->orderItems as $orderItem) {
                $allOrderItems[] = new TabbyOrderItem(
                    title: "order_item_{$orderItem->id}",
                    category: $orderItem->getItemTypeName(),
                    unitPrice: $orderItem->total_amount,
                    quantity: 1,
                    referenceId: "order_item_{$orderItem->id}",
                    description: "order_item_{$orderItem->id}",
                );
            }

            $tabbyOrder = new TabbyOrder(
                referenceId: "order-{$order->id}",
                items: $allOrderItems,
            );

            // Shipping address data (fallbacks kept meaningful)
            $shippingAddress = new TabbyShippingAddress(
                city: $user->city ?? 'Unknown City',
                address: $user->address ?? 'Unknown Address',
                zip: '00000',
            );

            // Build payload strictly as Tabby expects
            $payload = [
                'payment' => [
                    'amount' => number_format((float)$price, 2, '.', ''),
                    'currency' => $this->currency,
                    'description' => 'Order #' . $order->id,
                    'buyer' => $tabbyBuyer->toArray(),
                    'buyer_history' => [
                        'registered_since' => null,
                        'loyalty_level' => null,
                        'is_existing_customer' => true,
                    ],
                    'order' => $tabbyOrder->toArray(),
                    'order_history' => [[
                        'purchased_at' => date(DATE_ATOM),
                        'amount' => number_format((float)$price, 2, '.', ''),
                        'payment_method' => 'card',
                        'status' => 'new',
                        'buyer' => $tabbyBuyer->toArray(),
                        'shipping_address' => $shippingAddress->toArray(),
                    ]],
                    'shipping_address' => $shippingAddress->toArray(),
                ],
                'lang' => 'ar',
                'merchant_code' => $this->merchantCode,
                'merchant_urls' => [
                    'success' => $this->makeCallbackUrl('success'),
                    'cancel' => $this->makeCallbackUrl('cancel'),
                    'failure' => $this->makeCallbackUrl('failure'),
                ],
                'token' => null,
            ];

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->publicKey,
            ])->post('https://api.tabby.ai/api/v2/checkout', $payload);

            if ($response->failed()) {
                \Log::error('Tabby API request failed', [
                    'status' => $response->status(),
                    'message' => optional($response->json())['message'] ?? $response->body(),
                    'response' => $response->json(),
                ]);
                throw new \RuntimeException('Tabby checkout creation failed');
            }

            $sessionData = $response->json();

            session()->put($this->order_session_key, $order->id);

            // Fetch the payment url from the checkout session
            $paymentUrl = $sessionData['configuration']['available_products']['installments'][0]['web_url'] ?? '';
            // Sanitize to prevent header injection and invalid redirects
            $paymentUrl = str_replace(["\r", "\n"], '', trim($paymentUrl));
            if (!filter_var($paymentUrl, FILTER_VALIDATE_URL)) {
                \Log::error('Tabby paymentUrl is invalid', ['url' => $paymentUrl]);
                $toastData = [
                    'title' => trans('cart.fail_purchase'),
                    'msg' => trans('cart.gateway_error'),
                    'status' => 'error'
                ];
                return redirect()->back()->with(['toast' => $toastData])->withInput();
            }

            // Log raw and hex-encoded URL for debugging header issues
            try {
                \Log::debug('Tabby paymentUrl (raw)', ['url' => $paymentUrl]);
                \Log::debug('Tabby paymentUrl (hex)', ['hex' => bin2hex($paymentUrl)]);
            } catch (\Throwable $__) {}

            // Return URL (controller decides Redirect/away). Avoid headers here.
            return $paymentUrl;
        } catch (\Exception $e) {
            // Handle exceptions with friendly message
            \Log::error('Tabby API request failed', [
                'message' => $e->getMessage(),
            ]);
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
            return redirect()->back()->with(['toast' => $toastData])->withInput();
        }
    }

    private function makeCallbackUrl($status)
    {
        return url("/payments/verify/Tabby?status={$status}");
    }

    public function verify(Request $request)
    {
        $status = $request->get('status');
        $paymentId = $request->get('payment_id');

        $user = auth()->user();
        $sessionOrderId = session()->get($this->order_session_key, null);
        session()->forget($this->order_session_key);

        $order = null;

        if (!empty($paymentId)) {
            $tabbyService = new TabbyService(
                merchantCode: $this->merchantCode,
                publicKey: $this->publicKey,
                secretKey: $this->secretKey,
                currency: $this->currency,
            );

            $payment = $tabbyService->retrievePayment($paymentId);
            $paymentOrder = $payment->getOrder();

            $paymentOrderId = str_replace('order-', '', $paymentOrder->getReferenceId());

            $order = Order::where('id', $paymentOrderId)
                ->where('user_id', $user->id)
                ->first();

            if (!empty($order) and in_array($payment->getStatus(), ["AUTHORIZED", "CLOSED"])) {
                $order->update([
                    'status' => Order::$paying,
                ]);
            } else {
                $order->update([
                    'status' => Order::$fail,
                ]);
            }
        }

        return $order;
    }
}
