<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\Installment\InstallmentAccounting;
use App\Models\Cart;
use App\Models\InstallmentOrder;
use App\Models\InstallmentOrderPayment;
use App\Models\InstallmentStep;
use App\Models\SelectedInstallmentStep;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallmentsController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("panel_financial_installments");

        $user = auth()->user();

        $query = InstallmentOrder::query()
            ->where('user_id', $user->id)
            ->where('status', '!=', 'paying');

        $copyQuery = deepClone($query);
        //$query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }


        $openInstallmentsCount = deepClone($copyQuery)->where('status', 'open')->count();
        $pendingVerificationCount = deepClone($copyQuery)->where('status', 'pending_verification')->count();
        $finishedInstallmentsCount = $this->getFinishedInstallments($user);

        $overdueInstallments = $this->getOverdueInstallments($user);

        $data = [
            'pageTitle' => trans('update.installments'),
            'openInstallmentsCount' => $openInstallmentsCount,
            'pendingVerificationCount' => $pendingVerificationCount,
            'finishedInstallmentsCount' => $finishedInstallmentsCount,
            'overdueInstallments' => $overdueInstallments,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.financial.installments.lists.index', $data);
    }

    private function getListsData(Request $request, Builder $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $orders = $query
            ->with([
                'selectedInstallment' => function ($query) {
                    $query->with([
                        'steps' => function ($query) {
                            $query->orderBy('deadline', 'asc');
                        }
                    ]);
                    $query->withCount([
                        'steps'
                    ]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            $getRemainedInstallments = $this->getRemainedInstallments($order);

            $order->remained_installments_count = $getRemainedInstallments['total'];
            $order->remained_installments_amount = $getRemainedInstallments['amount'];

            $order->upcoming_installment = $this->getUpcomingInstallment($order);


            // is overdue
            $hasOverdue = $order->checkOrderHasOverdue();
            $order->has_overdue = $hasOverdue;
            $order->overdue_count = 0;
            $order->overdue_amount = 0;

            if ($hasOverdue) {
                $getOrderOverdueCountAndAmount = $order->getOrderOverdueCountAndAmount();
                $order->overdue_count = $getOrderOverdueCountAndAmount['count'];
                $order->overdue_amount = $getOrderOverdueCountAndAmount['amount'];
            }

        }

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $orders, $total, $count);
        }

        return [
            'orders' => $orders,
            'pagination' => $this->makePagination($request, $orders, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $orders, $total, $count)
    {
        $html = "";

        foreach($orders as $orderRow) {
            $html .= '<div class="col-12 col-lg-3 mt-16">';
            $html .= (string)view()->make("design_1.panel.financial.installments.lists.grid_card", ['order' => $orderRow]);
            $html .= '</div>';
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $orders, $total, $count, true)
        ]);
    }

    private function getRemainedInstallments($order)
    {
        $total = 0;
        $amount = 0;

        $itemPrice = $order->getItemPrice();

        foreach ($order->selectedInstallment->steps as $step) {
            $payment = InstallmentOrderPayment::query()
                ->where('installment_order_id', $order->id)
                ->where('selected_installment_step_id', $step->id)
                ->where('status', 'paid')
                ->whereHas('sale', function ($query) {
                    $query->whereNull('refund_at');
                })
                ->first();

            if (empty($payment)) {
                $total += 1;
                $amount += $step->getPrice($itemPrice);
            }
        }

        return [
            'total' => $total,
            'amount' => $amount,
        ];
    }

    private function getOverdueOrderInstallments($order)
    {
        $total = 0;
        $amount = 0;

        $time = time();
        $itemPrice = $order->getItemPrice();

        foreach ($order->selectedInstallment->steps as $step) {
            $dueAt = ($step->deadline * 86400) + $order->created_at;

            if ($dueAt < $time) {
                $payment = InstallmentOrderPayment::query()
                    ->where('installment_order_id', $order->id)
                    ->where('selected_installment_step_id', $step->id)
                    ->where('status', 'paid')
                    ->first();

                if (empty($payment)) {
                    $total += 1;
                    $amount += $step->getPrice($itemPrice);
                }
            }
        }

        return [
            'total' => $total,
            'amount' => $amount,
        ];
    }

    private function getUpcomingInstallment($order)
    {
        $result = null;
        $deadline = 0;

        foreach ($order->selectedInstallment->steps as $step) {
            $payment = InstallmentOrderPayment::query()
                ->where('installment_order_id', $order->id)
                ->where('selected_installment_step_id', $step->id)
                ->where('status', 'paid')
                ->first();

            if (empty($payment) and ($deadline == 0 or $deadline > $step->deadline)) {
                $deadline = $step->deadline;
                $result = $step;
            }
        }

        return $result;
    }

    private function getOverdueInstallments($user)
    {
        $overdueInstallments = collect();

        $orders = InstallmentOrder::query()
            ->where('user_id', $user->id)
            ->where('installment_orders.status', 'open')
            ->with([
                'installment',
                'webinar',
            ])
            ->get();

        foreach ($orders as $order) {
            if ($order->checkOrderHasOverdue()) {

                $getOrderOverdueCountAndAmount = $order->getOrderOverdueCountAndAmount();
                $order->overdue_count = $getOrderOverdueCountAndAmount['count'];
                $order->overdue_amount = $getOrderOverdueCountAndAmount['amount'];
                $order->overdue_timestamp = $getOrderOverdueCountAndAmount['firstDueAt'];


                $overdueInstallments->add($order);

            }
        }

        return $overdueInstallments;
    }

    private function getFinishedInstallments($user)
    {
        $orders = InstallmentOrder::query()
            ->where('user_id', $user->id)
            ->where('installment_orders.status', 'open')
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            $steps = $order->selectedInstallment->steps;
            $paidAllSteps = true;

            foreach ($steps as $step) {
                $payment = InstallmentOrderPayment::query()
                    ->where('installment_order_id', $order->id)
                    ->where('selected_installment_step_id', $step->id)
                    ->where('status', 'paid')
                    ->whereHas('sale', function ($query) {
                        $query->whereNull('refund_at');
                    })
                    ->first();

                if (empty($payment)) {
                    $paidAllSteps = false;
                }
            }

            if ($paidAllSteps) {
                $count += 1;
            }
        }

        return $count;
    }

    public function show($orderId)
    {
        $user = auth()->user();

        $order = InstallmentOrder::query()
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->with([
                'selectedInstallment' => function ($query) {
                    $query->with([
                        'steps' => function ($query) {
                            $query->orderBy('deadline', 'asc');
                        }
                    ]);
                }
            ])
            ->first();

        if (!empty($order) and !in_array($order->status, ['refunded', 'canceled'])) {

            $getRemainedInstallments = $this->getRemainedInstallments($order);
            $getOverdueOrderInstallments = $this->getOverdueOrderInstallments($order);

            $totalParts = $order->selectedInstallment->steps->count();
            $remainedParts = $getRemainedInstallments['total'];
            $remainedAmount = $getRemainedInstallments['amount'];
            $overdueAmount = $getOverdueOrderInstallments['amount'];
            $overdueParts = $getOverdueOrderInstallments['total'];

            $paidParts = $order->payments()->where('status', 'paid')
                ->where('type', 'step')
                ->get();

            $data = [
                'pageTitle' => trans('update.installment_overview'),
                'totalParts' => $totalParts,
                'remainedParts' => $remainedParts,
                'remainedAmount' => $remainedAmount,
                'overdueAmount' => $overdueAmount,
                'overdueParts' => $overdueParts,
                'order' => $order,
                'payments' => $order->payments,
                'installment' => $order->selectedInstallment,
                'itemPrice' => $order->getItemPrice(),
                'paidParts' => $paidParts,
            ];

            return view('design_1.panel.financial.installments.details.index', $data);
        }

        abort(404);
    }

    public function cancelVerification($orderId)
    {
        if (getInstallmentsSettings("allow_cancel_verification")) {
            $user = auth()->user();

            $order = InstallmentOrder::query()
                ->where('id', $orderId)
                ->where('user_id', $user->id)
                ->where('status', "pending_verification")
                ->first();

            if (!empty($order)) {
                $installmentRefund = new InstallmentAccounting();
                $installmentRefund->refundOrder($order);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'text' => trans('update.order_status_changes_to_canceled'),
                ]);
            }
        }

        abort(404);
    }

    public function payUpcomingPart($orderId)
    {
        $user = auth()->user();

        $order = InstallmentOrder::query()
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($order)) {
            $upcomingStep = $this->getUpcomingInstallment($order);

            if (!empty($upcomingStep)) {
                return $this->handlePayStep($order, $upcomingStep);
            }
        }

        abort(404);
    }

    public function payStep($orderId, $stepId)
    {
        $user = auth()->user();

        $order = InstallmentOrder::query()
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($order)) {
            $selectedInstallment = $order->selectedInstallment;

            if (!empty($selectedInstallment)) {
                $step = SelectedInstallmentStep::query()
                    ->where('selected_installment_id', $selectedInstallment->id)
                    ->where('id', $stepId)
                    ->first();

                if (!empty($step)) {
                    return $this->handlePayStep($order, $step);
                }
            }
        }

        abort(404);
    }

    private function handlePayStep($order, $step)
    {
        $installmentPayment = InstallmentOrderPayment::query()->updateOrCreate([
            'installment_order_id' => $order->id,
            'sale_id' => null,
            'type' => 'step',
            'selected_installment_step_id' => $step->id,
            'amount' => $step->getPrice($order->getItemPrice()),
            'status' => 'paying',
        ], [
            'created_at' => time(),
        ]);

        Cart::updateOrCreate([
            'creator_id' => $order->user_id,
            'installment_payment_id' => $installmentPayment->id,
        ], [
            'created_at' => time()
        ]);

        return redirect('/cart');
    }
}
