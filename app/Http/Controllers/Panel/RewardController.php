<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\RewardAccounting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_rewards_lists");

        $rewardsSettings = getRewardsSettings();

        if (empty($rewardsSettings) or ($rewardsSettings and $rewardsSettings['status'] != '1')) {
            abort(404);
        }

        $user = auth()->user();

        $query = RewardAccounting::query()->where('user_id', $user->id);

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $totalTransactions = deepClone($copyQuery)->count();

        $addictionPoints = deepClone($copyQuery)->where('status', RewardAccounting::ADDICTION)
            ->sum('score');

        $spentPoints = deepClone($copyQuery)->where('status', RewardAccounting::DEDUCTION)
            ->sum('score');

        $availablePoints = $addictionPoints - $spentPoints;

        $mostPointsUsers = RewardAccounting::selectRaw('*, SUM(CASE WHEN status = "addiction" THEN score ELSE 0 END) as total_points')
            ->groupBy('user_id')
            ->whereHas('user')
            ->with([
                'user'
            ])
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get();

        $earnByExchange = 0;
        if (!empty($rewardsSettings) and !empty($rewardsSettings['exchangeable']) and $rewardsSettings['exchangeable'] == '1') {
            $earnByExchange = $availablePoints / $rewardsSettings['exchangeable_unit'];
        }

        $data = [
            'pageTitle' => trans('update.rewards'),
            'availablePoints' => $availablePoints,
            'totalPoints' => $addictionPoints,
            'spentPoints' => $spentPoints,
            'totalTransactions' => $totalTransactions,
            'rewardsSettings' => $rewardsSettings,
            'earnByExchange' => $earnByExchange,
            'mostPointsUsers' => $mostPointsUsers,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.rewards.index', $data);
    }


    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $status = $request->get('status');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($status) and in_array($status, ['addiction', 'deduction'])) {
            $query->where('status', $status);
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

        $rewards = $query
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $rewards, $total, $count);
        }

        return [
            'rewards' => $rewards,
            'pagination' => $this->makePagination($request, $rewards, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $rewards, $total, $count)
    {
        $html = "";

        foreach ($rewards as $rewardRow) {
            $html .= (string)view()->make('design_1.panel.rewards.table_items', ['reward' => $rewardRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $rewards, $total, $count, true)
        ]);
    }

    public function exchange(Request $request)
    {
        $rewardsSettings = getRewardsSettings();

        if (empty($rewardsSettings) or ($rewardsSettings and $rewardsSettings['status'] != '1')) {
            abort(403);
        }

        $user = auth()->user();

        $availablePoints = $user->getRewardPoints();
        $earnByExchange = 0;
        if (!empty($rewardsSettings) and !empty($rewardsSettings['exchangeable']) and $rewardsSettings['exchangeable'] == '1') {
            $earnByExchange = $availablePoints / $rewardsSettings['exchangeable_unit'];
        }

        if ($availablePoints > 0 and $earnByExchange > 0) {
            RewardAccounting::makeRewardAccounting($user->id, $availablePoints, 'withdraw', null, false, RewardAccounting::DEDUCTION);

            Accounting::create([
                'user_id' => $user->id,
                'amount' => $earnByExchange,
                'type' => Accounting::$addiction,
                'type_account' => Accounting::$asset,
                'description' => trans('update.exchange_reward_points_to_wallet'),
                'created_at' => time(),
            ]);
        }

        return response()->json([
            'code' => 200
        ]);
    }
}
