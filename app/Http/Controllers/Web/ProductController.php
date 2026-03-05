<?php

namespace App\Http\Controllers\Web;

use App\Enums\MorphTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\CheckContentLimitationTrait;
use App\Http\Controllers\Web\traits\InstallmentsTrait;
use App\Mixins\Cashback\CashbackRules;
use App\Mixins\Installment\InstallmentPlans;
use App\Mixins\Logs\VisitLogMixin;
use App\Models\AdvertisingBanner;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Follow;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductFeaturedCategory;
use App\Models\ProductTopCategory;
use App\Models\ProductOrder;
use App\Models\ProductSelectedFilterOption;
use App\Models\ProductSelectedSpecification;
use App\Models\ProductSpecification;
use App\Models\RewardAccounting;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use InstallmentsTrait;
    use CheckContentLimitationTrait;

    public function index(Request $request)
    {
        $query = Product::query()->where('products.status', Product::$active)
            ->where('ordering', true);

        $filterMaxPrice = (deepClone($query)->max('price') + 10) * 10;

        $query = $this->handleFilters($request, $query);

        $getListData = $this->getListData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }


        $categories = ProductCategory::whereNull('parent_id')
            ->with([
                'subCategories' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ])
            ->get();

        $categoryId = $request->get('category_id', null);

        $seoSettings = getSeoMetas('products_lists');
        $pageTitle = $seoSettings['title'] ?? '';
        $pageDescription = $seoSettings['description'] ?? '';
        $pageRobot = getPageRobot('products_lists');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'productCategories' => $categories,
            'filterMaxPrice' => $filterMaxPrice,
            'seoSettings' => $seoSettings,
            'pageBottomSeoContent' => $this->getPageBottomSeoContent($categoryId),
        ];
        $data = array_merge($data, $getListData);
        $data = array_merge($data, $this->getProductFeaturedContents());

        return view('design_1.web.products.lists.index', $data);
    }

    private function getPageBottomSeoContent($categoryId = null)
    {
        if (!empty($categoryId)) {
            $category = ProductCategory::query()->where('id', $categoryId)->first();

            if (!empty($category) and !empty($category->bottom_seo_title) and !empty($category->bottom_seo_description)) {
                return [
                    'title' => $category->bottom_seo_title,
                    'description' => $category->bottom_seo_description,
                ];
            }
        } else {
            $seoSettings = getSeoMetas('products_lists');

            if (!empty($seoSettings['bottom_seo_title']) and !empty($seoSettings['bottom_seo_content'])) {
                return [
                    'title' => $seoSettings['bottom_seo_title'],
                    'description' => $seoSettings['bottom_seo_content'],
                ];
            }
        }

        return null;
    }

    private function getProductFeaturedContents(): array
    {
        $data = [];
        $settings = getStoreFeaturedProductsSettings();

        $data['topCategories'] = ProductTopCategory::query()
            ->with([
                'category' => function ($query) {
                    $query->withCount('products');
                }
            ])
            ->get();

        if (!empty($settings) and !empty($settings['featured_products'])) {
            $data['featuredProducts'] = Product::query()->whereIn('id', $settings['featured_products'])
                ->with([
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio');
                    }
                ])
                ->where('status', Product::$active)
                ->where('ordering', true)
                ->get();
        }


        $data['featuredCategories'] = ProductFeaturedCategory::query()
            ->with([
                'category' => function ($query) {
                    $query->withCount('products');
                }
            ])
            ->get();

        return $data;
    }

    public function handleFilters(Request $request, $query, $isRewardProducts = false)
    {
        $search = $request->get('search', null);
        $isFree = $request->get('free', null);
        $isFreeShipping = $request->get('free_shipping', null);
        $withDiscount = $request->get('discount', null);
        $sort = $request->get('sort', null);
        $type = $request->get('type', null);
        $options = $request->get('options', null);
        $categoryId = $request->get('category_id', null);
        $minPrice = $request->get('min_price', null);
        $maxPrice = $request->get('max_price', null);
        $seller = $request->get('seller', null);

        if (!empty($search)) {
            $query->whereTranslationLike('title', '%' . $search . '%');
        }

        if (!empty($isFree) and $isFree == 'on') {
            $query->where(function ($qu) {
                $qu->whereNull('price')
                    ->orWhere('price', '0');
            });
        }

        if (!empty($isFreeShipping) and $isFreeShipping == 'on') {
            $query->where(function ($qu) {
                $qu->whereNull('delivery_fee')
                    ->orWhere('delivery_fee', '0');
            });
        }

        if (!empty($withDiscount) and $withDiscount == 'on') {
            $query->whereHas('discounts', function ($query) {
                $query->where('status', 'active')
                    ->where('start_date', '<', time())
                    ->where('end_date', '>', time());
            });
        }

        if (!empty($type) and count($type)) {
            $query->whereIn('type', $type);
        }

        if (!empty($seller)) {
            $query->where('creator_id', $seller);
        }

        if (!empty($options) and count($options)) {
            if (in_array('only_available', $options)) {
                $query->where(function ($query) {
                    $query->where('unlimited_inventory', true)
                        ->orWhereHas('productOrders', function ($query) {
                            $query->havingRaw('products.inventory > sum(quantity)')
                                ->whereNotNull('sale_id')
                                ->whereNotIn('status', [ProductOrder::$canceled, ProductOrder::$pending])
                                ->groupBy('product_id');
                        });
                });
            }

            if (in_array('products_with_points', $options)) {
                $query->whereNotNull('point');
            }

            if (in_array('featured', $options)) {
                $settings = getStoreFeaturedProductsSettings();
                $featuredProductsIds = (!empty($settings) and !empty($settings['featured_products'])) ? $settings['featured_products'] : [];

                $query->whereIn('id', $featuredProductsIds);
            }

            if (in_array('installments', $options)) {
                // ایجاد کوئری برای این پیچیده بود و نیاز به زمان داره.
                // پیدا کردن محصولاتی که اقساط داشته باشن سخته و اگر بخوایم همه محصولات رو بگیریم و یکی یکی چک کنیم هم خیلی سنگین میکنه ریکئوست رو

            }
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($minPrice)) {
            $query->where('price', '>', $minPrice);
        }

        if (!empty($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }

        if (!empty($sort)) {
            if ($sort == 'expensive') {
                if ($isRewardProducts) {
                    $query->orderBy('point', 'desc');
                } else {
                    $query->orderBy('price', 'desc');
                }
            }

            if ($sort == 'inexpensive') {
                if ($isRewardProducts) {
                    $query->orderBy('point', 'asc');
                } else {
                    $query->orderBy('price', 'asc');
                }
            }

            if ($sort == 'bestsellers') {
                $query->leftJoin('product_orders', function ($join) {
                    $join->on('products.id', '=', 'product_orders.product_id')
                        ->whereNotNull('product_orders.sale_id')
                        ->whereNotIn('product_orders.status', [ProductOrder::$canceled, ProductOrder::$pending]);
                })
                    ->select('products.*', DB::raw('sum(product_orders.quantity) as sales_counts'))
                    ->groupBy('product_orders.product_id')
                    ->orderBy('sales_counts', 'desc');
            }

            if ($sort == 'best_rates') {
                $query->leftJoin('product_reviews', function ($join) {
                    $join->on('products.id', '=', 'product_reviews.product_id');
                    $join->where('product_reviews.status', 'active');
                })
                    ->whereNotNull('rates')
                    ->select('products.*', DB::raw('avg(rates) as rates'))
                    ->groupBy('product_reviews.product_id')
                    ->orderBy('rates', 'desc');
            }
        }

        if (empty($sort) or $sort == "newest") {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getListData(Request $request, $query)
    {
        $page = $request->get('page') ?? 1;
        $count = 9;

        $cloneQuery = deepClone($query);
        $total = DB::table(DB::raw("({$cloneQuery->toSql()}) as sub"))
            ->mergeBindings($cloneQuery->getQuery()) // bind parameters
            ->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $products = $query->with([
            'creator' => function ($query) {
                $query->select('id', 'full_name', 'username', 'bio', 'role_id', 'role_name', 'avatar', 'avatar_settings');
            }
        ])->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $products, $total, $count);
        }

        return [
            'products' => $products,
            'pagination' => $this->makePagination($request, $products, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $products, $total, $count)
    {
        $categoryId = $request->get('category_id', null);
        $specificContent = null;

        if (!empty($categoryId)) {
            $pageBottomSeoContent = $this->getPageBottomSeoContent($categoryId);

            if (!empty($pageBottomSeoContent) and !empty($pageBottomSeoContent['title']) and !empty($pageBottomSeoContent['description'])) {
                $specificContent = [
                    'el' => '.js-page-bottom-seo-content',
                    'html' => (string)view()->make('design_1.web.products.lists.includes.bottom_seo_content', ['seoContent' => $pageBottomSeoContent])
                ];
            } else {
                $specificContent = [
                    'el' => '.js-page-bottom-seo-content',
                    'html' => null
                ];
            }
        }

        $html = (string)view()->make('design_1.web.products.components.cards.grids.index', [
            'products' => $products,
            'gridCardClassName' => "col-12 col-lg-6 mt-24",
            'withoutStyles' => true,
        ]);

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $products, $total, $count, true),
            'specific_content' => $specificContent,
        ]);
    }


    public function show(Request $request, $slug)
    {
        $user = null;

        if (auth()->check()) {
            $user = auth()->user();
        }

        $contentLimitation = $this->checkContentLimitation($user, true);
        if ($contentLimitation != "ok") {
            return $contentLimitation;
        }

        $product = Product::where('status', Product::$active)
            ->where('slug', $slug)
            ->with([
                'creator' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings', 'bio', 'about', 'cover_img', 'profile_secondary_image');
                    $qu->withCount([
                        'products'
                    ]);
                },
                'selectedSpecifications' => function ($query) {
                    $query->where('status', ProductSelectedSpecification::$Active);
                    $query->with(['specification']);
                },
                'files' => function ($query) {
                    $query->where('status', 'active');
                    $query->orderBy('order', 'asc');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->first();

        if (empty($product)) {
            abort(404);
        }


        $selectableSpecifications = $product->selectedSpecifications->where('allow_selection', true)
            ->where('type', 'multi_value');
        $selectedSpecifications = $product->selectedSpecifications->where('allow_selection', false);

        $seller = $product->creator;
        $following = $seller->following();
        $followers = $seller->followers();

        $authUserIsFollower = false;
        if (auth()->check()) {
            $authUserIsFollower = $followers->where('follower', auth()->id())
                ->where('status', Follow::$accepted)
                ->first();
        }

        $advertisingBanners = AdvertisingBanner::where('published', true)
            ->whereIn('position', ['product_show'])
            ->get();

        /* Installments */
        $installments = null;
        if (!empty($product->price) and $product->price > 0 and getInstallmentsSettings('status') and (empty($user) or $user->enable_installments)) {
            $installmentPlans = new InstallmentPlans($user);
            $installments = $installmentPlans->getPlans('store_products', $product->id, $product->type, $product->category_id, $product->creator_id);
        }

        /* Cashback Rules */
        $cashbackRules = null;
        if (!empty($product->price) and getFeaturesSettings('cashback_active') and (empty($user) or !$user->disable_cashback)) {
            $cashbackRulesMixin = new CashbackRules($user);
            $cashbackRules = $cashbackRulesMixin->getRules('store_products', $product->id, $product->type, $product->category_id, $product->creator_id);
        }

        $instructorDiscounts = null;

        if (!empty(getFeaturesSettings('frontend_coupons_status'))) {
            $instructorDiscounts = Discount::query()
                ->where('creator_id', $product->creator_id)
                ->where(function (Builder $query) use ($product) {
                    $query->where('source', 'all');
                    $query->orWhere(function (Builder $query) use ($product) {
                        $query->where('source', Discount::$discountSourceProduct);
                        $query->where('product_type', $product->type);
                    });
                })
                ->where('status', 'active')
                ->where('expired_at', '>', time())
                ->get();
        }

        $product->creator->someRandomProducts = Product::query()->where('creator_id', $product->creator_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit(3)
            ->with([
                'category'
            ])->get();

        $commentController = new CommentController();
        $productComments = $commentController->getComments($request, 'product', $product->id);

        $productReviewController = new ProductReviewController();
        $productReviews = $productReviewController->getReviewsByCourseSlug($request, $product->slug);

        // Visit Logs
        $visitLogMixin = new VisitLogMixin();
        $visitLogMixin->storeVisit($request, $product->creator_id, $product->id, MorphTypesEnum::PRODUCT);


        $pageRobot = getPageRobot('product_show'); // return => index

        $data = [
            'pageTitle' => $product->title,
            'pageDescription' => $product->seo_description,
            'pageRobot' => $pageRobot,
            'pageMetaImage' => $product->thumbnail,
            'product' => $product,
            'user' => $user,
            'selectableSpecifications' => $selectableSpecifications,
            'selectedSpecifications' => $selectedSpecifications,
            'seller' => $seller,
            'sellerBadges' => $seller->getBadges(),
            'sellerRates' => $seller->rates(),
            'sellerFollowers' => $following,
            'sellerFollowing' => $followers,
            'authUserIsFollower' => $authUserIsFollower,
            'advertisingBanners' => $advertisingBanners,
            'activeSpecialOffer' => $product->getActiveDiscount(),
            'installments' => $installments,
            'hasInstallments' => (!empty($installments) and count($installments)),
            'cashbackRules' => $cashbackRules,
            'instructorDiscounts' => $instructorDiscounts,
            'productComments' => $productComments,
            'productReviews' => $productReviews,
            'productAvailability' => $product->getAvailability(),
        ];

        return view("design_1.web.products.show.index", $data);
    }

    public function buyWithPoint(Request $request, $slug)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $data = $request->all();

            $product = Product::where('slug', $slug)
                ->where('status', 'active')
                ->first();

            $product_id = $data['item_id'];
            $specifications = $data['specifications'] ?? null;
            $quantity = $data['quantity'] ?? 1;

            if (!empty($product) and $product_id == $product->id) {
                if (empty($product->point)) {
                    $toastData = [
                        'title' => '',
                        'msg' => trans('update.can_not_buy_this_product_with_point'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $availablePoints = $user->getRewardPoints();
                $needPoints = $product->point * $quantity;

                if ($availablePoints < $needPoints) {
                    $toastData = [
                        'title' => '',
                        'msg' => trans('update.you_have_no_enough_points_for_this_product'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $checkCourseForSale = checkProductForSale($request, $product, $user);

                if ($checkCourseForSale != 'ok') {
                    if ($request->ajax()) {
                        return response()->json(['toast_alert' => $checkCourseForSale], 422);
                    } else {
                        return back()->with(['toast' => $checkCourseForSale]);
                    }
                }

                $productOrder = ProductOrder::create([
                    'product_id' => $product->id,
                    'seller_id' => $product->creator_id,
                    'buyer_id' => $user->id,
                    'specifications' => $specifications ? json_encode($specifications) : null,
                    'quantity' => $quantity,
                    'status' => 'pending',
                    'created_at' => time()
                ]);

                $sale = Sale::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $product->creator_id,
                    'product_order_id' => $productOrder->id,
                    'type' => Sale::$product,
                    'payment_method' => Sale::$credit,
                    'amount' => 0,
                    'total_amount' => 0,
                    'created_at' => time(),
                ]);

                $productOrder->update([
                    'sale_id' => $sale->id,
                    'status' => $product->isVirtual() ? ProductOrder::$success : ProductOrder::$waitingDelivery,
                ]);

                RewardAccounting::makeRewardAccounting($user->id, $needPoints, 'withdraw', null, false, RewardAccounting::DEDUCTION);

                $toastData = [
                    'title' => '',
                    'msg' => trans('update.success_pay_product_with_point_msg'),
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

        if (!empty($user) and !empty(getFeaturesSettings('direct_products_payment_button_status'))) {
            $this->validate($request, [
                'item_id' => 'required',
            ]);

            $data = $request->except('_token');

            $productId = $data['item_id'];
            $specifications = $data['specifications'] ?? null;
            $quantity = $data['quantity'] ?? 1;

            $product = Product::query()->where('id', $productId)
                ->where('status', 'active')
                ->first();

            if (!empty($product)) {
                $checkCourseForSale = checkProductForSale($request, $product, $user);

                if ($checkCourseForSale != 'ok') {
                    if ($request->ajax()) {
                        return response()->json(['toast_alert' => $checkCourseForSale], 422);
                    } else {
                        return back()->with(['toast' => $checkCourseForSale]);
                    }
                }

                $activeDiscount = $product->getActiveDiscount();

                $productOrder = ProductOrder::updateOrCreate([
                    'product_id' => $product->id,
                    'seller_id' => $product->creator_id,
                    'buyer_id' => $user->id,
                ], [
                    'specifications' => $specifications ? json_encode($specifications) : null,
                    'quantity' => $quantity,
                    'discount_id' => !empty($activeDiscount) ? $activeDiscount->id : null,
                    'status' => 'pending',
                    'created_at' => time()
                ]);


                Cart::updateOrCreate([
                    'creator_id' => $user->id,
                    'product_order_id' => $productOrder->id,
                ], [
                    'product_discount_id' => !empty($activeDiscount) ? $activeDiscount->id : null,
                    'created_at' => time()
                ]);

                /*$redirectTo = "";

                if ($product->isVirtual()) {

                } else {

                }*/

                return response()->json([
                    'code' => 200,
                    'title' => trans('cart.cart_add_success_title'),
                    'msg' => trans('cart.cart_add_success_msg'),
                    'redirect_to' => "/cart",
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function showFiles($slug)
    {
        $user = auth()->user();
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with([
                'creator' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings', 'bio', 'about');
                },
                'files' => function ($query) {
                    $query->where('status', 'active');
                    $query->orderBy('order', 'asc');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->first();

        if (!empty($user) and !empty($product->files) and count($product->files) and $product->checkUserHasBought()) {

            $data = [
                'pageTitle' => trans('update.download_page'),
                'product' => $product,
            ];

            return view("design_1.web.products.files.index", $data);
        }

        abort(404);
    }
}
