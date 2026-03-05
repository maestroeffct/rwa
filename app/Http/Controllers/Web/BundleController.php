<?php

namespace App\Http\Controllers\Web;

use App\Enums\MorphTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\BundleShowTrait;
use App\Http\Controllers\Web\traits\CheckContentLimitationTrait;
use App\Http\Controllers\Web\traits\InstallmentsTrait;
use App\Mixins\Cashback\CashbackRules;
use App\Mixins\Installment\InstallmentPlans;
use App\Mixins\Logs\VisitLogMixin;
use App\Models\AdvertisingBanner;
use App\Models\Bundle;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Favorite;
use App\Models\RewardAccounting;
use App\Models\Sale;
use App\Models\SpecialOffer;
use App\Models\Ticket;
use App\Models\UpcomingCourseFilterOption;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    use InstallmentsTrait, BundleShowTrait;
    use CheckContentLimitationTrait;

    public function index(Request $request)
    {
        $query = Bundle::query()->where('status', Bundle::$active);
        $query->where('private', false);
        $query->where('only_for_students', false);

        $filterMaxPrice = deepClone($query)->max('price') ?? 10000;

        $query = $this->handleFilters($request, $query);

        $getListData = $this->getListData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $seoSettings = getSeoMetas('bundles_lists');
        $pageTitle = $seoSettings['title'] ?? trans('update.bundles');
        $pageDescription = $seoSettings['description'] ?? '';
        $pageRobot = getPageRobot('bundles_lists');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'seoSettings' => $seoSettings,
            'pageBasePath' => "/bundles",
            'filterMaxPrice' => ($filterMaxPrice > 1000) ? $filterMaxPrice : 1000,
        ];

        $data = array_merge($data, $getListData);

        return view('design_1.web.bundles.lists.index', $data);
    }


    private function handleFilters(Request $request, $query)
    {
        $free = $request->get('free');
        $has_discount = $request->get('discount');
        $sort = $request->get('sort');
        $type = $request->get('type');
        $moreOptions = $request->get('moreOptions');


        if (!empty($free)) {
            $query->where(function ($query) {
                $query->whereNull('price');
                $query->orWhere('price', '<', '1');
            });
        }

        if (!empty($has_discount)) {

            if (!empty($withDiscount) and $withDiscount == 'on') {
                $now = time();
                $webinarIdsHasDiscount = [];

                $tickets = Ticket::where('start_date', '<', $now)
                    ->where('end_date', '>', $now)
                    ->whereNotNull("bundle_id")
                    ->get();

                foreach ($tickets as $ticket) {
                    if ($ticket->isValid()) {
                        $webinarIdsHasDiscount[] = $ticket->bundle_id;
                    }
                }

                $specialOffersItemIds = SpecialOffer::where('status', 'active')
                    ->where('from_date', '<', $now)
                    ->where('to_date', '>', $now)
                    ->pluck("bundle_id")
                    ->toArray();

                $webinarIdsHasDiscount = array_merge($specialOffersItemIds, $webinarIdsHasDiscount);

                $webinarIdsHasDiscount = array_unique($webinarIdsHasDiscount);

                $query->whereIn("id", $webinarIdsHasDiscount);
            }
        }

        if (!empty($type) and count($type)) {
            $query->whereHas('webinar', function (Builder $query) use ($type) {
                $query->whereIn('type', $type);
            });
        }

        if (!empty($moreOptions) and count($moreOptions)) {
            $query->whereHas('webinar', function (Builder $query) use ($moreOptions) {

                if (in_array('supported_courses', $moreOptions)) {
                    $query->where('support', true);
                }

                if (in_array('quiz_included', $moreOptions)) {
                    $query->where('include_quizzes', true);
                }

                if (in_array('certificate_included', $moreOptions)) {
                    $query->where('certificate', true);
                }

                if (in_array('assignment_included', $moreOptions)) {
                    $query->where('assignments', true);
                }

                if (in_array('course_forum_included', $moreOptions)) {
                    $query->where('forum', true);
                }

                if (in_array('point_courses', $moreOptions)) {
                    $query->where('points', true);
                }
            });
        }


        if (!empty($sort)) {
            switch ($sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'earliest_publish_date':
                    $query->orderBy('publish_date', 'asc');
                    break;
                case 'farthest_publish_date':
                    $query->orderBy('publish_date', 'desc');
                    break;
                case 'highest_price':
                    $query->orderBy('price', 'desc');
                    break;
                case 'lowest_price':
                    $query->orderBy('price', 'asc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getListData(Request $request, $query)
    {
        $page = $request->get('page') ?? 1;
        $count = 10;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $bundles = $query->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $bundles, $total, $count);
        }

        return [
            'bundles' => $bundles,
            'pagination' => $this->makePagination($request, $bundles, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $bundles, $total, $count)
    {
        $html = (string)view()->make('design_1.web.bundles.components.cards.grids.index', [
            'bundles' => $bundles,
            'gridCardClassName' => "col-12 col-lg-6 mt-24",
            'withoutStyles' => true
        ]);

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $bundles, $total, $count, true)
        ]);
    }


    public function show(Request $request, $slug)
    {
        $user = null;

        if (auth()->check()) {
            $user = auth()->user();
        }

        $contentLimitation = $this->checkContentLimitation($user);
        if ($contentLimitation != "ok") {
            return $contentLimitation;
        }

        $bundle = Bundle::where('slug', $slug)
            ->with([
                'tickets' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'relatedCourses' => function ($query) {
                    $query->whereHas('course', function ($query) {
                        $query->where('status', 'active');
                    });
                },
                'filterOptions',
                'category',
                'teacher',
                'tags',
                'bundleWebinars' => function ($query) {
                    $query->with([
                        'webinar' => function ($query) {
                            $query->where('status', Webinar::$active);
                        }
                    ]);
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->withCount([
                'sales' => function ($query) {
                    $query->whereNull('refund_at');
                }
            ])
            ->where('status', 'active')
            ->first();

        if (empty($bundle)) {
            abort(404);
        }


        $installmentLimitation = $this->installmentContentLimitation($user, $bundle->id, 'bundle_id');
        if ($installmentLimitation != "ok") {
            return $installmentLimitation;
        }


        $isFavorite = false;

        if (!empty($user)) {
            $isFavorite = Favorite::where('bundle_id', $bundle->id)
                ->where('user_id', $user->id)
                ->first();
        }

        $hasBought = $bundle->checkUserHasBought($user);
        $canSale = ($bundle->canSale() and !$hasBought);

        $advertisingBanners = AdvertisingBanner::where('published', true)
            ->whereIn('position', ['bundle', 'bundle_sidebar'])
            ->get();

        /* Installments */
        if ($canSale and !empty($bundle->price) and $bundle->price > 0 and getInstallmentsSettings('status') and (empty($user) or $user->enable_installments)) {
            $installmentPlans = new InstallmentPlans($user);
            $installments = $installmentPlans->getPlans('bundles', $bundle->id, $bundle->type, $bundle->category_id, $bundle->teacher_id);
        }

        /* Cashback Rules */
        if ($canSale and !empty($bundle->price) and getFeaturesSettings('cashback_active') and (empty($user) or !$user->disable_cashback)) {
            $cashbackRulesMixin = new CashbackRules($user);
            $cashbackRules = $cashbackRulesMixin->getRules('bundles', $bundle->id, $bundle->type, $bundle->category_id, $bundle->teacher_id);
        }

        $instructorDiscounts = null;

        if (!empty(getFeaturesSettings('frontend_coupons_status'))) {
            $instructorDiscounts = Discount::query()
                ->where(function (Builder $query) use ($bundle) {
                    $query->where('creator_id', $bundle->creator_id);
                    $query->orWhere('creator_id', $bundle->teacher_id);
                })
                ->where(function (Builder $query) use ($bundle) {
                    $query->where('source', 'all');
                    $query->orWhere(function (Builder $query) use ($bundle) {
                        $query->where('source', Discount::$discountSourceBundle);

                        $query->where(function (Builder $query) use ($bundle) {
                            $query->whereHas('discountBundles', function ($query) use ($bundle) {
                                $query->where('bundle_id', $bundle->id);
                            });

                            $query->whereDoesntHave('discountBundles');
                        });
                    });
                })
                ->where('status', 'active')
                ->where('expired_at', '>', time())
                ->get();
        }


        $reviewController = new BundleReviewController();
        $bundleReviews = $reviewController->getReviewsByBundleSlug($request, $bundle->slug);

        $commentController = new CommentController();
        $bundleComments = $commentController->getComments($request, 'bundle', $bundle->id);


        // Visit Logs
        $visitLogMixin = new VisitLogMixin();
        $visitLogMixin->storeVisit($request, $bundle->creator_id, $bundle->id, MorphTypesEnum::BUNDLE);


        $pageRobot = getPageRobot('bundle_show'); // index

        $data = [
            'pageTitle' => $bundle->title,
            'pageDescription' => $bundle->seo_description,
            'pageRobot' => $pageRobot,
            'bundle' => $bundle,
            'isFavorite' => $isFavorite,
            'hasBought' => $hasBought,
            'user' => $user,
            'advertisingBanners' => $advertisingBanners->where('position', 'bundle'),
            'advertisingBannersSidebar' => $advertisingBanners->where('position', 'bundle_sidebar'),
            'activeSpecialOffer' => $bundle->activeSpecialOffer(),
            'cashbackRules' => $cashbackRules ?? null,
            'installments' => $installments ?? null,
            'instructorDiscounts' => $instructorDiscounts,
            'bundleComments' => $bundleComments,
            'bundleReviews' => $bundleReviews,
        ];

        return view('design_1.web.bundles.show.index', $data);
    }

    public function free(Request $request, $slug)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $bundle = Bundle::where('slug', $slug)
                ->where('status', 'active')
                ->first();

            if (!empty($bundle)) {
                $checkCourseForSale = checkCourseForSale($bundle, $user);

                if ($checkCourseForSale != 'ok') {
                    return back()->with(['toast' => $checkCourseForSale]);
                }

                if (!isFreeModeEnabled() and !empty($bundle->price) and $bundle->price > 0) {
                    $toastData = [
                        'title' => trans('cart.fail_purchase'),
                        'msg' => trans('update.bundle_not_free'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
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

                $toastData = [
                    'title' => '',
                    'msg' => trans('cart.success_pay_msg_for_free_course'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            }

            abort(404);
        } else {
            return redirect('/login');
        }
    }

    public function directPayment(Request $request)
    {
        $user = auth()->user();

        if (!empty($user) and !empty(getFeaturesSettings('direct_bundles_payment_button_status'))) {
            $this->validate($request, [
                'item_id' => 'required',
                'item_name' => 'nullable',
            ]);

            $data = $request->except('_token');

            $bundleId = $data['item_id'];
            $ticketId = $data['ticket_id'] ?? null;

            $bundle = Bundle::where('id', $bundleId)
                ->where('status', 'active')
                ->first();

            if (!empty($bundle)) {
                $checkCourseForSale = checkCourseForSale($bundle, $user);

                if ($checkCourseForSale != 'ok') {
                    return back()->with(['toast' => $checkCourseForSale]);
                }

                $fakeCarts = collect();

                $fakeCart = new Cart();
                $fakeCart->creator_id = $user->id;
                $fakeCart->bundle_id = $bundle->id;
                $fakeCart->ticket_id = $ticketId;
                $fakeCart->special_offer_id = null;
                $fakeCart->created_at = time();

                $fakeCarts->add($fakeCart);

                $cartController = new CartController();

                return $cartController->checkout(new Request(), $fakeCarts);
            }
        }

        abort(404);
    }

}
