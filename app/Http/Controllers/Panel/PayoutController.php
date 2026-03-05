<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_financial_payout");

        $user = auth()->user();
        $query = Payout::query()->where('user_id', $user->id);

        //$copyQuery = deepClone($query);
        //$query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $selectedBank = $user->selectedBank;

        $data = [
            'pageTitle' => trans('financial.payout_request'),
            'accountCharge' => $user->getAccountingCharge(),
            'readyPayout' => $user->getPayout(),
            'totalIncome' => $user->getIncome(),
            'selectedBank' => $selectedBank,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.financial.payout.index', $data);
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $payouts = $query
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $payouts, $total, $count);
        }

        return [
            'payouts' => $payouts,
            'pagination' => $this->makePagination($request, $payouts, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $payouts, $total, $count)
    {
        $html = "";

        foreach($payouts as $payoutRow) {
            $html .= (string)view()->make('design_1.panel.financial.payout.table_items', ['payout' => $payoutRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $payouts, $total, $count, true)
        ]);
    }

    public function requestPayout()
    {
        $this->authorize("panel_financial_payout");

        $user = auth()->user();
        $getUserPayout = $user->getPayout();
        $getFinancialSettings = getFinancialSettings();

        if (!empty($getFinancialSettings['minimum_payout']) and $getUserPayout < $getFinancialSettings['minimum_payout']) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('public.income_los_then_minimum_payout'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        if (!$user->financial_approval) {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('update.your_financial_information_has_not_been_approved_by_the_admin'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        if (!empty($user->selectedBank)) {

            Payout::create([
                'user_id' => $user->id,
                'user_selected_bank_id' => $user->selectedBank->id,
                'amount' => $getUserPayout,
                'status' => Payout::$waiting,
                'created_at' => time(),
            ]);

            $notifyOptions = [
                '[payout.amount]' => handlePrice($getUserPayout),
                '[amount]' => handlePrice($getUserPayout),
                '[u.name]' => $user->full_name
            ];

            sendNotification('payout_request', $notifyOptions, $user->id);
            sendNotification('payout_request_admin', $notifyOptions, 1); // for admin
            sendNotification('new_user_payout_request', $notifyOptions, 1); // for admin

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.payout_request_registered_successful_hint'),
                'status' => 'success'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $toastData = [
            'title' => trans('public.request_failed'),
            'msg' => trans('site.check_identity_settings'),
            'status' => 'error'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function getDetails($id)
    {
        $this->authorize("panel_financial_payout");
        $user = auth()->user();

        $payout = Payout::query()->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($payout)) {

            $html = (string)view()->make('design_1.panel.financial.payout.modals.details', [
                'payout' => $payout,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

}
