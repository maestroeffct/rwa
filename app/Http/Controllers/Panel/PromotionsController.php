<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\OfflineBank;
use App\Models\Promotion;
use App\Models\Sale;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_marketing_promotions");

        $user = auth()->user();

        $query = Sale::query()->where('buyer_id', $user->id)
            ->where('type', Sale::$promotion)
            ->whereNull('refund_at');

        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $promotions = Promotion::orderBy('created_at', 'desc')->get();

        $data = [
            'pageTitle' => trans('panel.promotions'),
            'promotions' => $promotions,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.marketing.promotions.index', $data);
    }


    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $promotionSales = $query
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $promotionSales, $total, $count);
        }

        return [
            'promotionSales' => $promotionSales,
            'pagination' => $this->makePagination($request, $promotionSales, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $promotionSales, $total, $count)
    {
        $html = "";

        foreach ($promotionSales as $promotionSaleRow) {
            $html .= (string)view()->make('design_1.panel.marketing.promotions.table_items', ['promotionSale' => $promotionSaleRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $promotionSales, $total, $count, true)
        ]);
    }

    public function getPayForm($id)
    {
        $promotion = Promotion::query()->where('id', $id)->first();

        if (!empty($promotion)) {
            $user = auth()->user();

            $webinars = Webinar::query()->select('id', 'creator_id', 'teacher_id')
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                })
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            $html = (string)view()->make("design_1.panel.marketing.promotions.pay_modal", [
                'promotion' => $promotion,
                'webinars' => $webinars
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

    public function payPromotion(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->all();

        if (empty($data['course_id'])) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('validation.required', ['attribute' => trans('update.course')]),
                'status' => 'error'
            ];
            return redirect()->back()->with(['toast' => $toastData]);
        }

        $promotion = Promotion::query()->where('id', $id)->first();

        if (!empty($promotion)) {
            $paymentChannels = PaymentChannel::where('status', 'active')->get();

            $webinar = Webinar::where('id', $data['course_id'])
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                })
                ->where('status', 'active')
                ->first();

            if (!empty($webinar)) {
                $financialSettings = getFinancialSettings();
                //$commission = $financialSettings['commission'] ?? 0;
                $tax = $financialSettings['tax'] ?? 0;

                $amount = (!empty($promotion->price) and $promotion->price > 0) ? $promotion->price : 0;

                $taxPrice = $tax ? $amount * $tax / 100 : 0;
                //$commissionPrice = $commission ? $amount * $commission / 100 : 0;

                $order = Order::create([
                    "user_id" => $user->id,
                    "status" => Order::$pending,
                    'tax' => $taxPrice,
                    'commission' => 0,
                    "amount" => $promotion->price,
                    "total_amount" => $amount + $taxPrice,
                    "created_at" => time(),
                ]);

                $orderItem = OrderItem::updateOrCreate([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'webinar_id' => $webinar->id,
                    'promotion_id' => $promotion->id,
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
                        'calculatePrices' => $calculatePrices,
                        'order' => $order,
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
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.success_pay_msg_for_free_promotion'),
                    'status' => 'success'
                ];
                return redirect()->back()->with(['toast' => $toastData]);
            }
        }

        abort(404);
    }

    private function handleFreePromotion($orderItem)
    {

    }
}
