<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Affiliate;
use App\Models\AffiliateCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_marketing_affiliates");

        $user = auth()->user();

        $query = Affiliate::query()->where('affiliate_user_id', $user->id);

        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $affiliateCode = $user->affiliateCode;

        if (empty($affiliateCode)) {
            $affiliateCode = $this->makeUserAffiliateCode($user);
        }

        $referredUsersCount = Affiliate::where('affiliate_user_id', $user->id)->count();

        $registrationBonus = Accounting::where('is_affiliate_amount', true)
            ->where('system', false)
            ->where('user_id', $user->id)
            ->sum('amount');

        $affiliateBonus = Accounting::where('is_affiliate_commission', true)
            ->where('system', false)
            ->where('user_id', $user->id)
            ->sum('amount');

        $referralHowWorkSettings = getReferralHowWorkSettings();

        $data = [
            'pageTitle' => trans('panel.affiliates_page'),
            'affiliateCode' => $affiliateCode,
            'registrationBonus' => $registrationBonus,
            'affiliateBonus' => $affiliateBonus,
            'referredUsersCount' => $referredUsersCount,
            'referralHowWorkSettings' => $referralHowWorkSettings,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.marketing.affiliates.index', $data);
    }


    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $affiliates = $query
            ->with([
                'referredUser',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $affiliates, $total, $count);
        }

        return [
            'affiliates' => $affiliates,
            'pagination' => $this->makePagination($request, $affiliates, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $affiliates, $total, $count)
    {
        $html = "";

        foreach ($affiliates as $affiliateRow) {
            $html .= (string)view()->make('design_1.panel.marketing.affiliates.table_items', ['affiliate' => $affiliateRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $affiliates, $total, $count, true)
        ]);
    }

    private function makeUserAffiliateCode($user)
    {
        $code = mt_rand(100000, 999999);

        $check = AffiliateCode::where('code', $code)->first();

        if (!empty($check)) {
            return $this->makeUserAffiliateCode($user);
        }

        $affiliateCode = AffiliateCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'created_at' => time()
        ]);

        return $affiliateCode;
    }
}
