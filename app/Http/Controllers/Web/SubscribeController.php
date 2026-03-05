<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\SubscribeMixins;
use App\Models\Accounting;
use App\Models\Bundle;
use App\Models\Sale;
use App\Models\Subscribe;
use App\Models\SubscribeUse;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{

    public function details(Request $request, $id)
    {
        $subscribe = Subscribe::query()->findOrFail($id);

        $subscribeMixins = (new SubscribeMixins());

        $recommendedCoursesQuery = Webinar::query()->where('status', 'active')
            ->where('subscribe', true);
        $recommendedCoursesQuery = $subscribeMixins->handleTargetProductLimitationOnCourseQuery($subscribe, $recommendedCoursesQuery, 'courses');
        $recommendedCourses = $recommendedCoursesQuery->inrandomOrder()
            ->take(8)
            ->get();

        $recommendedBundlesQuery = Bundle::query()->where('status', 'active')
            ->where('subscribe', true);
        $recommendedBundlesQuery = $subscribeMixins->handleTargetProductLimitationOnCourseQuery($subscribe, $recommendedBundlesQuery, 'bundles');
        $recommendedBundles = $recommendedBundlesQuery->inrandomOrder()
            ->take(8)
            ->get();

        $data = [
            'pageTitle' => trans('update.subscription_package_details'),
            'subscribe' => $subscribe,
            'recommendedCourses' => $recommendedCourses,
            'recommendedBundles' => $recommendedBundles,
        ];

        return view('design_1.web.subscribes.details.index', $data);
    }


    public function apply(Request $request, $webinarSlug)
    {
        $webinarQuery = Webinar::query()->where('slug', $webinarSlug)
            ->where('status', 'active')
            ->where('subscribe', true);

        return $this->handleSale($webinarQuery, 'webinar_id');
    }

    public function bundleApply($bundleSlug)
    {
        $bundleQuery = Bundle::query()->where('slug', $bundleSlug)
            ->where('status', 'active')
            ->where('subscribe', true);

        return $this->handleSale($bundleQuery, 'bundle_id');
    }

    private function handleSale(Builder $itemQuery, $itemName = 'webinar_id')
    {
        if (auth()->check()) {
            if (empty($itemQuery->first())) {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.item_not_found!'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }

            $user = auth()->user();

            $subscribe = Subscribe::getActiveSubscribe($user->id);

            if (empty($subscribe)) {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('site.you_dont_have_active_subscribe'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }

            $targetType = ($itemName == 'webinar_id') ? 'courses' : 'bundles';

            $subscribeMixins = (new SubscribeMixins());
            $item = $subscribeMixins->handleTargetProductLimitationOnCourseQuery($subscribe, $itemQuery, $targetType)->first();

            if (empty($item)) {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.you_cannot_purchase_this_item_with_your_subscription_plan'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }

            $checkCourseForSale = checkCourseForSale($item, $user);

            if ($checkCourseForSale != 'ok') {
                return back()->with(['toast' => $checkCourseForSale]);
            }

            $sale = Sale::create([
                'buyer_id' => $user->id,
                'seller_id' => $item->creator_id,
                $itemName => $item->id,
                'subscribe_id' => $subscribe->id,
                'type' => $itemName == 'webinar_id' ? Sale::$webinar : Sale::$bundle,
                'payment_method' => Sale::$subscribe,
                'amount' => 0,
                'total_amount' => 0,
                'created_at' => time(),
            ]);

            Accounting::createAccountingForSaleWithSubscribe($item, $subscribe, $itemName);

            SubscribeUse::create([
                'user_id' => $user->id,
                'subscribe_id' => $subscribe->id,
                $itemName => $item->id,
                'sale_id' => $sale->id,
                'installment_order_id' => $subscribe->installment_order_id ?? null,
            ]);

            $toastData = [
                'title' => trans('cart.success_pay_title'),
                'msg' => trans('cart.success_pay_msg_subscribe'),
                'status' => 'success'
            ];
            return back()->with(['toast' => $toastData]);
        } else {
            return redirect('/login');
        }
    }
}
