<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\PaymentController;
use App\Mixins\Cashback\CashbackRules;
use App\Models\Accounting;
use App\Models\OfflineBank;
use App\Models\OfflinePayment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AccountingSummaryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_financial_summary");

        $userAuth = auth()->user();

        $query = Accounting::query()->where('user_id', $userAuth->id)
            ->where('system', false)
            ->where('tax', false);

        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $data = [
            'pageTitle' => trans('financial.summary_page_title'),
            'commission' => getFinancialSettings('commission') ?? 0,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.financial.summary.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $search = $request->get('search');
        $type = $request->get('type');
        $store_type = $request->get('store_type');
        $type_account = $request->get('type_account');
        $sort = $request->get('sort');

        // $from and $to
        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($search)) {
            $query->where('description', "like", "%{$search}%");
        }

        if (!empty($type)) {
            $query->where('type', $type);
        }

        if (!empty($store_type)) {
            $query->where('store_type', $store_type);
        }

        if (!empty($type_account)) {
            $query->where('type_account', $type_account);
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('amount', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('amount', 'desc');
                    break;
                case 'create_date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'create_date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $accountings = $query
            ->with([
                'webinar',
                'promotion',
                'subscribe',
                'meetingTime' => function ($query) {
                    $query->with([
                        'meeting' => function ($query) {
                            $query->with([
                                'creator' => function ($query) {
                                    $query->select('id', 'full_name');
                                }
                            ]);
                        }
                    ]);
                }
            ])
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $accountings, $total, $count);
        }

        return [
            'accountings' => $accountings,
            'pagination' => $this->makePagination($request, $accountings, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $accountings, $total, $count)
    {
        $html = "";

        foreach ($accountings as $accountingRow) {
            $html .= (string)view()->make('design_1.panel.financial.summary.table_items', ['accounting' => $accountingRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $accountings, $total, $count, true)
        ]);
    }

}
