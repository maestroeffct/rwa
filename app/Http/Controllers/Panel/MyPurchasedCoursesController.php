<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Session;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyPurchasedCoursesController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_webinars_my_purchases");

        $user = auth()->user();

        $query = $this->getQuery($user);
        $query = $this->handleFilters($request, $query);
        $pageListData = $this->getPageListData($request, $query);

        if ($request->ajax()) {
            return $pageListData;
        }

        $topStats = $this->handlePageTopStats($user);

        $pageTitle = trans('panel.my_purchases');
        $breadcrumbs = [
            ['text' => trans('update.platform'), 'url' => '/'],
            ['text' => trans('panel.dashboard'), 'url' => '/panel'],
            ['text' => $pageTitle, 'url' => null],
        ];

        $data = [
            'pageTitle' => $pageTitle,
            'breadcrumbs' => $breadcrumbs,
            ...$topStats,
            ...$pageListData,
        ];

        return view('design_1.panel.webinars.my_purchases.index', $data);
    }

    private function getQuery($user): Builder
    {
        $giftsIds = Gift::query()->where('email', $user->email)
            ->where('status', 'active')
            ->whereNull('product_id')
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->pluck('id')
            ->toArray();

        return Sale::query()
            ->where(function ($query) use ($user, $giftsIds) {
                $query->where('sales.buyer_id', $user->id);
                $query->orWhereIn('sales.gift_id', $giftsIds);
            })
            ->whereNull('sales.refund_at')
            ->where('access_to_purchased_item', true)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('sales.webinar_id')
                        ->where('sales.type', 'webinar')
                        ->whereHas('webinar', function ($query) {
                            $query->where('status', 'active');
                            $query->orWhere('only_for_students', true);
                        });
                });
                $query->orWhere(function ($query) {
                    $query->whereNotNull('sales.bundle_id')
                        ->where('sales.type', 'bundle')
                        ->whereHas('bundle', function ($query) {
                            $query->where('status', 'active');
                            $query->orWhere('only_for_students', true);
                        });
                });
                $query->orWhere(function ($query) {
                    $query->whereNotNull('gift_id');
                    $query->whereHas('gift');
                });
            });
    }

    private function handlePageTopStats($user): array
    {
        $query = $this->getQuery($user);

        $totalPurchasedCount = deepClone($query)->count();

        $totalPurchasedAmount = deepClone($query)->sum("total_amount");;

        $totalCoursesHours = 0;
        $totalUpcomingCount = 0;
        $purchasedCourseIds = [];

        $time = time();
        $sales = deepClone($query)->get();

        foreach ($sales as $sale) {
            if (!empty($sale->gift_id)) {
                if (!empty($gift->webinar)) {
                    $totalCoursesHours += $gift->webinar->duration;

                    if ($gift->webinar->start_date > $time) {
                        $totalUpcomingCount += 1;
                    }

                    $purchasedCourseIds[] = $gift->webinar->id;
                }

                if (!empty($gift->bundle)) {
                    $totalCoursesHours += $gift->bundle->getBundleDuration();
                }
            } else {
                if (!empty($sale->webinar)) {
                    $totalCoursesHours += $sale->webinar->duration;

                    if ($sale->webinar->start_date > $time) {
                        $totalUpcomingCount += 1;
                    }

                    $purchasedCourseIds[] = $sale->webinar->id;
                }

                if (!empty($sale->bundle)) {
                    $totalCoursesHours += $sale->bundle->getBundleDuration();
                }
            }
        }

        $upcomingLiveSessions = Session::whereIn('webinar_id', $purchasedCourseIds)
            ->where('date', '>=', time())
            ->orderBy('date', 'asc')
            ->where('status', Session::$Active)
            ->whereDoesntHave('agoraHistory', function ($query) {
                $query->whereNotNull('end_at');
            })
            ->get();

        return [
            'totalPurchasedCount' => $totalPurchasedCount,
            'totalCoursesHours' => $totalCoursesHours,
            'totalUpcomingCount' => $totalUpcomingCount,
            'totalPurchasedAmount' => $totalPurchasedAmount,
            'upcomingLiveSessions' => $upcomingLiveSessions,
        ];
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {

        return $query;
    }

    private function getPageListData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = 8; // $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $sales = $query
            ->with([
                'webinar' => function ($query) {
                    $query->with([
                        'files',
                        'reviews' => function ($query) {
                            $query->where('status', 'active');
                        },
                        'category',
                        'teacher' => function ($query) {
                            $query->select('id', 'full_name');
                        },
                    ]);
                    $query->withCount([
                        'sales' => function ($query) {
                            $query->whereNull('refund_at');
                        }
                    ]);
                },
                'bundle' => function ($query) {
                    $query->with([
                        'reviews' => function ($query) {
                            $query->where('status', 'active');
                        },
                        'category',
                        'teacher' => function ($query) {
                            $query->select('id', 'full_name');
                        },
                    ]);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($sales as &$sale) {
            $sale = $this->handleSaleExtraData($sale);
        }

        if ($request->ajax()) {
            return $this->handleAjaxResponse($request, $sales, $total, $count);
        }

        return [
            'sales' => $sales,
            'pagination' => $this->makePagination($request, $sales, $total, $count, true),
        ];
    }

    private function handleAjaxResponse(Request $request, $sales, $total, $count)
    {
        $html = "";

        foreach ($sales as $saleItem) {
            $html .= '<div class="col-12 col-lg-6 mb-32">';
            $html .= (string)view()->make("design_1.panel.webinars.my_purchases.item_card.index", ['sale' => $saleItem]);
            $html .= '</div>';
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $sales, $total, $count, true)
        ]);
    }

    private function handleSaleExtraData($sale)
    {
        if (!empty($sale->gift_id)) {
            $gift = $sale->gift;

            $sale->webinar_id = $gift->webinar_id;
            $sale->bundle_id = $gift->bundle_id;

            $sale->webinar = !empty($gift->webinar_id) ? $gift->webinar : null;
            $sale->bundle = !empty($gift->bundle_id) ? $gift->bundle : null;

            $sale->gift_recipient = !empty($gift->receipt) ? $gift->receipt->full_name : $gift->name;
            $sale->gift_sender = $sale->buyer->full_name;
            $sale->gift_date = $gift->date;
        }

        return $sale;
    }

    public function getJoinInfo(Request $request)
    {
        $data = $request->all();

        if (!empty($data['webinar_id'])) {
            $user = auth()->user();

            $checkSale = Sale::where('buyer_id', $user->id)
                ->where('webinar_id', $data['webinar_id'])
                ->where('type', 'webinar')
                ->whereNull('refund_at')
                ->first();

            if (!empty($checkSale)) {
                $webinar = Webinar::where('status', 'active')
                    ->where('id', $data['webinar_id'])
                    ->first();

                if (!empty($webinar)) {
                    $session = Session::select('id', 'creator_id', 'date', 'link', 'zoom_start_link', 'session_api', 'api_secret')
                        ->where('webinar_id', $webinar->id)
                        ->where('date', '>=', time())
                        ->orderBy('date', 'asc')
                        ->whereDoesntHave('agoraHistory', function ($query) {
                            $query->whereNotNull('end_at');
                        })
                        ->first();

                    if (!empty($session)) {
                        $session->date = dateTimeFormat($session->date, 'Y-m-d H:i', false);

                        $session->link = $session->getJoinLink(true);

                        return response()->json([
                            'code' => 200,
                            'session' => $session
                        ], 200);
                    }
                }
            }
        }

        return response()->json([], 422);
    }

}
