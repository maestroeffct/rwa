<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\InstallmentsTrait;
use App\Mixins\Installment\InstallmentPlans;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\OfflineBank;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Subscribe;
use App\User;
use Illuminate\Http\Request;

class SubscribesController extends Controller
{
    use InstallmentsTrait;

    public function index()
    {
        $this->authorize("panel_financial_subscribes");

        $user = auth()->user();

        if (!$user) {
            $user = apiAuth();
        }

        $subscribes = Subscribe::query()
            ->orderBy('created_at', 'desc')
            ->get();


        $data = [
            'pageTitle' => trans('financial.subscribes'),
            'subscribes' => $subscribes,
            'activeSubscribe' => Subscribe::getActiveSubscribe($user->id),
            'dayOfUse' => Subscribe::getDayOfUse($user->id),
        ];

        return view('design_1.panel.financial.subscribes.index', $data);
    }

    public function pay(Request $request)
    {
        $paymentChannels = PaymentChannel::where('status', 'active')->get();

        $subscribe = Subscribe::where('id', $request->input('id'))->first();

        if (empty($subscribe)) {
            $toastData = [
                'msg' => trans('site.subscribe_not_valid'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $user = auth()->user();

        /*
        $activeSubscribe = Subscribe::getActiveSubscribe($user->id);

        if ($activeSubscribe) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('site.you_have_active_subscribe'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }*/

        $financialSettings = getFinancialSettings();
        $tax = $financialSettings['tax'] ?? 0;

        $amount = $subscribe->getPrice();
        $amount = $amount > 0 ? $amount : 0;

        $taxPrice = $tax ? $amount * $tax / 100 : 0;

        $order = Order::create([
            "user_id" => $user->id,
            "status" => Order::$pending,
            'tax' => $taxPrice,
            'commission' => 0,
            "amount" => $amount,
            "total_amount" => $amount + $taxPrice,
            "created_at" => time(),
        ]);

        $orderItem = OrderItem::updateOrCreate([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'subscribe_id' => $subscribe->id,
        ], [
            'amount' => $order->amount,
            'total_amount' => $amount + $taxPrice,
            'tax' => $tax,
            'tax_price' => $taxPrice,
            'commission' => 0,
            'commission_price' => 0,
            'created_at' => time(),
        ]);

        if ($amount > 0) {

            $razorpay = false;
            foreach ($paymentChannels as $paymentChannel) {
                if ($paymentChannel->class_name == 'Razorpay') {
                    $razorpay = true;
                }
            }

            $calculatePrices = [
                'total' => $order->total_amount,
                'sub_total' => $order->amount,
                'total_discount' => 0,
                'tax' => $tax,
                'tax_price' => $taxPrice,
            ];


            $data = [
                'pageTitle' => trans('public.checkout_page_title'),
                'paymentChannels' => $paymentChannels,
                'total' => $order->total_amount,
                'order' => $order,
                'calculatePrices' => $calculatePrices,
                'count' => 1,
                'userCharge' => $user->getAccountingCharge(),
                'razorpay' => $razorpay,
                'offlineBanks' => OfflineBank::query()->orderBy('created_at', 'desc')->with(['specifications'])->get(),
            ];

            return view('design_1.web.cart.payment.index', $data);
        }

        // Handle Free
        Sale::createSales($orderItem, Sale::$credit);

        $toastData = [
            'title' => 'public.request_success',
            'msg' => trans('update.success_pay_msg_for_free_subscribe'),
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }
}
