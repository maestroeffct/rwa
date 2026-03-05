<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Api\Bundle;
use App\Models\Discount;
use App\Models\DiscountBundle;
use App\Models\DiscountCategory;
use App\Models\DiscountCourse;
use App\Models\DiscountGroup;
use App\Models\DiscountUser;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DiscountController extends Controller
{

    public function __construct()
    {
        if (empty(getFeaturesSettings("frontend_coupons_status"))) {
            abort(404);
        }
    }

    public function index(Request $request)
    {
        $this->authorize("panel_marketing_coupons");

        $user = auth()->user();

        $query = Discount::query()->where('creator_id', $user->id);

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $totalCoupons = deepClone($copyQuery)->count();
        $activeCoupons = deepClone($copyQuery)->where('status', 'active')->where('expired_at', '<', time())->count();
        $couponPurchases = 0;
        $purchaseAmount = 0;

        $data = [
            'pageTitle' => trans('update.coupons'),
            'totalCoupons' => $totalCoupons,
            'activeCoupons' => $activeCoupons,
            'couponPurchases' => $couponPurchases,
            'purchaseAmount' => $purchaseAmount,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.marketing.discounts.lists.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $source = $request->get('source');
        $status = $request->get('status');


        // $from and $to
        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($source) and $source != "all") {
            $query->where('source', $source);
        }

        if (!empty($status) and $status != "all") {
            if ($status == "active") {
                $query->where('status', 'active');
                $query->where('expired_at', '>', time());
            } elseif ($status == "expired") {
                $query->where('expired_at', '<', time());
            }
        }

        $query->orderBy('created_at', 'desc');

        return $query;
    }


    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $discounts = $query
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $discounts, $total, $count);
        }

        return [
            'discounts' => $discounts,
            'pagination' => $this->makePagination($request, $discounts, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $discounts, $total, $count)
    {
        $html = "";

        foreach($discounts as $discountRow) {
            $html .= (string)view()->make('design_1.panel.marketing.discounts.lists.table_items', ['discount' => $discountRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $discounts, $total, $count, true)
        ]);
    }

    public function create()
    {
        $this->authorize("panel_marketing_new_coupon");

        $data = $this->getCreateData();
        $data = array_merge($data, [
            'pageTitle' => trans('update.new_coupon'),
        ]);

        return view('design_1.panel.marketing.discounts.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("panel_marketing_new_coupon");

        $user = auth()->user();

        $this->validate($request, [
            'title' => 'required',
            'discount_type' => 'required|in:' . implode(',', Discount::$discountTypes),
            'source' => 'required|in:' . implode(',', Discount::$panelDiscountSource),
            'code' => 'required|unique:discounts',
            'percent' => 'nullable',
            'amount' => 'nullable',
            'count' => 'nullable',
            'expired_at' => 'required',
        ]);

        $data = $request->all();
        $expiredAt = convertTimeToUTCzone($data['expired_at'], getTimezone());

        $discount = Discount::create([
            'creator_id' => $user->id,
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'discount_type' => $data['discount_type'],
            'source' => $data['source'],
            'code' => $data['code'],
            'percent' => (!empty($data['percent']) and $data['percent'] > 0) ? $data['percent'] : 0,
            'amount' => !empty($data['amount']) ? convertPriceToDefaultCurrency($data['amount']) : null,
            'max_amount' => !empty($data['max_amount']) ? convertPriceToDefaultCurrency($data['max_amount']) : null,
            'minimum_order' => !empty($data['minimum_order']) ? convertPriceToDefaultCurrency($data['minimum_order']) : null,
            'count' => (!empty($data['count']) and $data['count'] > 0) ? $data['count'] : 1,
            'user_type' => 'all_users',
            'product_type' => $data['product_type'] ?? null,
            'for_first_purchase' => false,
            'private' => (!empty($data['private']) and $data['private'] == "on"),
            'status' => 'active',
            'expired_at' => $expiredAt->getTimestamp(),
            'created_at' => time(),
        ]);

        $this->handleRelationItems($discount, $data);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.new_discount_coupon_has_been_created_successfully'),
            'status' => 'success'
        ];
        return redirect('/panel/marketing/discounts')->with(['toast' => $toastData]);
    }

    public function edit($id)
    {
        $this->authorize("panel_marketing_new_coupon");

        $user = auth()->user();
        $discount = Discount::where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($discount)) {

            $data = $this->getCreateData();
            $data = array_merge($data, [
                'pageTitle' => trans('update.edit_coupon'),
                'discount' => $discount,
            ]);

            return view('design_1.panel.marketing.discounts.create.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->authorize("panel_marketing_new_coupon");

        $user = auth()->user();
        $discount = Discount::where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($discount)) {

            $this->validate($request, [
                'title' => 'required',
                'discount_type' => 'required|in:' . implode(',', Discount::$discountTypes),
                'source' => 'required|in:' . implode(',', Discount::$panelDiscountSource),
                'code' => 'required|unique:discounts,code,' . $discount->id,
                'user_id' => 'nullable',
                'percent' => 'nullable',
                'amount' => 'nullable',
                'count' => 'nullable',
                'expired_at' => 'required',
            ]);

            $data = $request->all();
            $expiredAt = convertTimeToUTCzone($data['expired_at'], getTimezone());

            $discount->update([
                'title' => $data['title'],
                'subtitle' => $data['subtitle'] ?? null,
                'discount_type' => $data['discount_type'],
                'source' => $data['source'],
                'code' => $data['code'],
                'percent' => (!empty($data['percent']) and $data['percent'] > 0) ? $data['percent'] : 0,
                'amount' => !empty($data['amount']) ? convertPriceToDefaultCurrency($data['amount']) : null,
                'max_amount' => !empty($data['max_amount']) ? convertPriceToDefaultCurrency($data['max_amount']) : null,
                'minimum_order' => !empty($data['minimum_order']) ? convertPriceToDefaultCurrency($data['minimum_order']) : null,
                'count' => (!empty($data['count']) and $data['count'] > 0) ? $data['count'] : 1,
                'user_type' => 'all_users',
                'product_type' => $data['product_type'] ?? null,
                'for_first_purchase' => false,
                'private' => (!empty($data['private']) and $data['private'] == "on"),
                'status' => 'active',
                'expired_at' => $expiredAt->getTimestamp(),
            ]);

            DiscountCourse::where('discount_id', $discount->id)->delete();

            DiscountBundle::where('discount_id', $discount->id)->delete();

            $this->handleRelationItems($discount, $data);

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.discount_coupon_has_been_updated_successfully'),
                'status' => 'success'
            ];
            return redirect("/panel/marketing/discounts/{$discount->id}/edit")->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function delete(Request $request, $id)
    {
        $this->authorize("panel_marketing_delete_coupon");

        $user = auth()->user();
        $discount = Discount::where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($discount)) {
            $discount->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.discount_coupon_has_been_deleted_successfully'),
                'status' => 'success'
            ];
            return redirect("/panel/marketing/discounts")->with(['toast' => $toastData]);
        }

        abort(404);
    }


    private function getCreateData()
    {
        $user = auth()->user();

        $webinars = Webinar::query()->select('id', 'teacher_id', 'creator_id')
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })->get();

        $bundles = Bundle::query()->select('id', 'teacher_id', 'creator_id')
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })->get();

        return [
            'webinars' => $webinars,
            'bundles' => $bundles,
        ];
    }

    private function handleRelationItems($discount, $data)
    {
        $coursesIds = $data['webinar_ids'] ?? [];
        $bundlesIds = $data['bundle_ids'] ?? [];


        if (!empty($coursesIds) and count($coursesIds)) {
            foreach ($coursesIds as $coursesId) {
                DiscountCourse::create([
                    'discount_id' => $discount->id,
                    'course_id' => $coursesId,
                    'created_at' => time(),
                ]);
            }
        }

        if (!empty($bundlesIds) and count($bundlesIds)) {
            foreach ($bundlesIds as $bundlesId) {
                DiscountBundle::create([
                    'discount_id' => $discount->id,
                    'bundle_id' => $bundlesId,
                    'created_at' => time(),
                ]);
            }
        }
    }


}
