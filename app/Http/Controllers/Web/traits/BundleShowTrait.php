<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\Favorite;
use App\Models\RewardAccounting;
use App\Models\Sale;
use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait BundleShowTrait
{

    public function favoriteToggle($slug)
    {
        $userId = auth()->id();
        $bundle = Bundle::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($bundle)) {

            $isFavorite = Favorite::where('bundle_id', $bundle->id)
                ->where('user_id', $userId)
                ->first();

            if (empty($isFavorite)) {
                Favorite::create([
                    'user_id' => $userId,
                    'bundle_id' => $bundle->id,
                    'created_at' => time()
                ]);
            } else {
                $isFavorite->delete();
            }
        }

        return response()->json([], 200);
    }

    public function getShareModal($slug)
    {
        $bundle = Bundle::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($bundle)) {
            $data = [
                'bundle' => $bundle
            ];

            $html = (string)view("design_1.web.bundles.show.includes.modals.share_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 400);
    }

    public function getBuyWithPointModal($slug)
    {
        $user = auth()->user();

        $bundle = Bundle::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($user) and !empty($bundle) and !empty($bundle->points)) {
            $availablePoints = $user->getRewardPoints();
            $haveEnoughPoints = ($availablePoints >= $bundle->points);

            $data = [
                'bundle' => $bundle,
                'availablePoints' => $availablePoints,
                'haveEnoughPoints' => $haveEnoughPoints,
            ];

            $html = (string)view("design_1.web.bundles.show.includes.modals.buy_with_point_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
                'btn_url' => $haveEnoughPoints ? "/bundles/{$bundle->slug}/points/apply" : "/panel/rewards",
                'btn_text' => $haveEnoughPoints ? trans('update.purchase') : trans('update.my_points'),
            ]);
        }

        return response()->json([], 400);
    }

    public function buyWithPoint($slug)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $bundle = Bundle::where('slug', $slug)
                ->where('status', 'active')
                ->first();

            if (!empty($bundle)) {
                if (empty($bundle->points)) {
                    $toastData = [
                        'title' => '',
                        'msg' => trans('update.can_not_buy_this_bundle_with_point'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $availablePoints = $user->getRewardPoints();

                if ($availablePoints < $bundle->points) {
                    $toastData = [
                        'title' => '',
                        'msg' => trans('update.you_have_no_enough_points_for_this_bundle'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $checkCourseForSale = checkCourseForSale($bundle, $user);

                if ($checkCourseForSale != 'ok') {
                    return back()->with(['toast' => $checkCourseForSale]);
                }

                Sale::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $bundle->creator_id,
                    'bundle_id' => $bundle->id,
                    'type' => Sale::$bundle,
                    'payment_method' => Sale::$credit,
                    'amount' => 0,
                    'total_amount' => 0,
                    'created_at' => time(),
                ]);

                RewardAccounting::makeRewardAccounting($user->id, $bundle->points, 'withdraw', null, false, RewardAccounting::DEDUCTION);

                $toastData = [
                    'title' => '',
                    'msg' => trans('update.success_pay_bundle_with_point_msg'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            }

            abort(404);
        } else {
            return redirect('/login');
        }
    }

}
