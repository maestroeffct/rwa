<?php

namespace App\Http\Controllers\Web;

use App\Enums\MorphTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\CheckContentLimitationTrait;
use App\Mixins\Logs\VisitLogMixin;
use App\Mixins\Events\EventTicketSoldMixins;
use App\Models\AdvertisingBanner;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventReport;
use App\Models\EventTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sale;
use App\Models\WebinarReview;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventsController extends Controller
{
    use CheckContentLimitationTrait;

    public function index(Request $request)
    {
        $query = Event::query()->where('events.status', 'publish')
            ->withMin('tickets', 'price')
            ->withMax('tickets', 'price');

        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $categories = Category::whereNull('parent_id')
            ->with([
                'subCategories' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
            ])
            ->get();


        $seoSettings = getSeoMetas('events_lists');
        $pageTitle = $seoSettings['title'] ?? trans('update.events');
        $pageDescription = $seoSettings['description'] ?? '';
        $pageRobot = getPageRobot('events_lists');

        $filterMaxPrice = EventTicket::query()->max('price');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'categoriesLists' => $categories,
            'seoSettings' => $seoSettings,
            'pageBasePath' => "/events",
            'filterMaxPrice' => $filterMaxPrice,
        ];

        $data = array_merge($data, $getListData);

        return view('design_1.web.events.lists.index', $data);
    }

    private function handleFilters(Request $request, $query)
    {
        $not_conducted = $request->get('not_conducted');
        $free = $request->get('free');
        $discount = $request->get('discount');
        $sort = $request->get('sort');
        $categoryId = $request->get('category_id');
        $types = $request->get('types', []);
        $moreOptions = $request->get('more_options', []);
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $instructorId = $request->get('instructor');
        $rating = $request->get('rating');

        $time = time();

        if (!empty($free)) {
            $query->whereHas('tickets', function ($query) {
                $query->whereNull('price');
                $query->orWhere('price', '<=', '0');
            });
        }

        if (!empty($discount)) {
            $query->whereHas('tickets', function ($query) use ($time) {
                $query->whereNotNull('discount');
                $query->where('discount_start_at', '<=', $time);
                $query->where('discount_end_at', '>', $time);
            });
        }

        if (!empty($not_conducted)) {
            $query->where(function (Builder $query) use ($time) {
                $query->whereNull("end_date");
                $query->orWhere('end_date', ">", $time);
            });
        }

        if (!empty($categoryId)) {
            $query->where('events.category_id', $categoryId);
        }

        if (!empty($instructorId)) {
            $query->where('events.creator_id', $instructorId);
        }

        if (!empty($types) and is_array($types)) {
            $query->whereIn('events.type', $types);
        }

        if (!empty($moreOptions) and is_array($moreOptions)) {
            switch ($moreOptions) {
                case 'supported_events':
                    $query->where('events.support', true);
                    break;
                case 'certificate_included':
                    $query->where('events.certificate', true);
                    break;
                case 'point_events':
                    $query->whereHas('tickets', function ($query) {
                        $query->whereNotNull('point');
                    });
                    break;
            }
        }

        if (!empty($minPrice)) {
            $query->whereHas('tickets', function ($query) use ($minPrice) {
                $query->where('price', '>', $minPrice);
            });
        }

        if (!empty($maxPrice)) {
            $query->whereHas('tickets', function ($query) use ($maxPrice) {
                $query->where('price', '<=', $maxPrice);
            });
        }

        if (!empty($rating)) {
            $query->leftJoin('webinar_reviews', function ($join) {
                $join->on("events.id", '=', "webinar_reviews.event_id");
                $join->where('webinar_reviews.status', 'active');
            })
                ->select("events.*", DB::raw('avg(webinar_reviews.rates) as rates_avg'))
                ->groupBy("events.id");

            /*$query->where('rates_avg', '>=', $rating);
            $query->where('rates_avg', '<', $rating + 1);*/

            // I made many changes but it gives an error !
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'highest_base_price':
                    $query->orderBy('tickets_max_price', 'desc');
                    break;
                case 'lowest_base_price':
                    $query->orderBy('tickets_min_price', 'asc');
                    break;
                case 'best_sellers':
                    $query->selectRaw('COUNT(event_tickets_sold.id) as total_sales')
                        ->leftJoin('event_tickets', 'event_tickets.event_id', '=', 'events.id')
                        ->leftJoin('event_tickets_sold', 'event_tickets_sold.event_ticket_id', '=', 'event_tickets.id') // change to ->join to get only have a sold tickets
                        ->groupBy('events.id')
                        ->orderBy('total_sales', 'desc');

                    break;
                case 'top_rated':
                    $query->select("events.*", DB::raw('avg(rates) as rates'))
                        ->leftJoin('webinar_reviews', function ($join) {
                            $join->on("events.id", '=', "webinar_reviews.event_id");
                            $join->where('webinar_reviews.status', 'active');
                        })
                        //->whereNotNull('rates')
                        ->groupBy('events.id')
                        ->orderBy('rates', 'desc');
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
        $count = 9;

        $total = $query->get()->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $events = $query->with([
            'reviews' => function ($query) {
                $query->where('status', 'active');
            },
            'creator' => function ($query) {
                $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio', 'about', 'verified', 'cover_img', 'profile_secondary_image');
            }
        ])->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $events, $total, $count);
        }

        return [
            'events' => $events,
            'pagination' => $this->makePagination($request, $events, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $events, $total, $count)
    {
        $html = (string)view()->make('design_1.web.events.components.cards.grids.index', [
            'events' => $events,
            'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-24",
            'withoutStyles' => true
        ]);

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $events, $total, $count, true)
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

        $event = Event::query()
            ->where('slug', $slug)
            ->where('status', 'publish')
            ->with([
                'tags',
                'faqs' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'prerequisites' => function ($query) {
                    $query->with([
                        'course' => function ($query) {
                            $query->with([
                                'teacher' => function ($qu) {
                                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                                }
                            ]);
                        }
                    ]);

                    $query->orderBy('order', 'asc');
                },
                'relatedCourses' => function ($query) {
                    $query->whereHas('course', function ($query) {
                        $query->where('status', 'active');
                    });
                },
                'extraDescriptions' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'creator' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio', 'about', 'verified', 'cover_img', 'profile_secondary_image');
                },
                'tickets' => function ($query) {
                    $query->where('enable', true);
                    $query->orderBy('order', 'asc');
                },
                'speakers' => function ($query) {
                    $query->where('enable', true);
                    $query->orderBy('order', 'asc');
                },
                'specificLocation',
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->withCount([
                'tickets' => function ($query) {
                    $query->where('enable', true);
                }
            ])
            ->first();

        if (!empty($event)) {

            $advertisingBanners = AdvertisingBanner::where('published', true)
                ->whereIn('position', ['event', 'event_sidebar'])
                ->get();

            $eventReviewController = new EventReviewController();
            $eventReviews = $eventReviewController->getReviewsByEventSlug($request, $event->slug);


            $commentController = new CommentController();
            $eventComments = $commentController->getComments($request, 'event', $event->id);

            // Visit Logs
            $visitLogMixin = new VisitLogMixin();
            $visitLogMixin->storeVisit($request, $event->creator_id, $event->id, MorphTypesEnum::EVENT);

            $pageRobot = getPageRobot('event_show'); // index

            $data = [
                'pageTitle' => $event->title,
                'pageDescription' => $event->seo_description,
                'pageRobot' => $pageRobot,
                'event' => $event,
                'advertisingBanners' => $advertisingBanners->where('position', 'event'),
                'advertisingBannersSidebar' => $advertisingBanners->where('position', 'event_sidebar'),
                'eventComments' => $eventComments,
                'eventReviews' => $eventReviews,
                'recentReviews' => $this->getEventRecentReviews($event->id),
            ];

            return view('design_1.web.events.show.index', $data);
        }

        abort(404);
    }

    private function getEventRecentReviews($eventId)
    {
        $recentReviews = null;

        if (!empty(getEventsSettings("event_recent_reviews_status"))) {
            $recentReviews = WebinarReview::query()->where('event_id', $eventId)
                ->where('status', 'active')
                ->whereNotNull('rates')
                ->orderBy('rates', 'desc')
                ->orderBy('created_at', 'desc')
                ->with([
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio', 'about');
                    }
                ])
                ->limit(5)
                ->get();
        }

        return $recentReviews;
    }

    public function report(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'reason' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $event = Event::query()
            ->where('id', $id)
            ->where('status', 'publish')
            ->first();

        if (!empty($user) and !empty($event)) {

            EventReport::query()->create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'reason' => $data['reason'],
                'message' => $data['message'],
                'created_at' => time()
            ]);

            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[content_type]' => trans('update.event')
            ];
            sendNotification("new_report_item_for_admin", $notifyOptions, 1);

            return response()->json([
                'code' => 200
            ], 200);
        }

        abort(422);
    }

    public function getShareModal($slug)
    {
        $event = Event::query()->where('slug', $slug)
            ->where('status', 'publish')
            ->first();

        if (!empty($event)) {
            $data = [
                'event' => $event
            ];

            $html = (string)view("design_1.web.events.show.includes.modals.share_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 400);
    }

    public function getReportModal($slug)
    {
        $event = Event::query()->where('slug', $slug)
            ->where('status', 'publish')
            ->first();

        if (!empty($event)) {
            $data = [
                'event' => $event
            ];

            $html = (string)view("design_1.web.events.show.includes.modals.report_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 400);
    }

    public function getFreeTicket(Request $request, $slug, $ticketId)
    {
        $user = auth()->user();

        if (!empty($user)) {
            $event = Event::query()->where('slug', $slug)
                ->where('status', 'publish')
                ->first();

            if (!empty($event)) {
                $eventTicket = EventTicket::query()->where('event_id', $event->id)
                    ->where('id', $ticketId)
                    ->where('enable', true)
                    ->first();

                if (!empty($eventTicket)) {
                    $quantity = $request->get('quantity') ?? 1;
                    $checkForSale = checkEventTicketForSale($eventTicket, $user, $quantity);

                    if ($checkForSale != 'ok') {
                        if ($request->ajax()) {
                            return response()->json(['toast_alert' => $checkForSale], 422);
                        }

                        return back()->with(['toast' => $checkForSale]);
                    }

                    $ticketPrice = $eventTicket->getPriceWithDiscount();

                    if (!empty($ticketPrice) and $ticketPrice > 0) {
                        $toastData = [
                            'title' => trans('cart.fail_purchase'),
                            'msg' => trans('update.event_ticket_not_free'),
                            'status' => 'error'
                        ];

                        if ($request->ajax()) {
                            return response()->json(['toast_alert' => $toastData], 422);
                        }

                        return back()->with(['toast' => $toastData]);
                    }

                    $orderItem = (new OrderItem());
                    $orderItem->user_id = $user->id;
                    $orderItem->user = $user;
                    $orderItem->event_ticket_id = $eventTicket->id;
                    $orderItem->amount = 0;
                    $orderItem->total_amount = 0;
                    $orderItem->eventTicket = $eventTicket;
                    $orderItem->quantity = $quantity;

                    $sale = Sale::create([
                        'buyer_id' => $user->id,
                        'seller_id' => $event->creator_id,
                        'event_ticket_id' => $eventTicket->id,
                        'type' => Order::$eventTicket,
                        'payment_method' => Sale::$credit,
                        'amount' => 0,
                        'total_amount' => 0,
                        'created_at' => time(),
                    ]);

                    Sale::handleSaleNotifications($orderItem, $event->creator_id);

                    (new EventTicketSoldMixins())->makeTicket($orderItem, $sale);

                    $toastData = [
                        'title' => trans('public.request_success'),
                        'msg' => trans('update.success_pay_msg_for_free_event_ticket'),
                        'status' => 'success',
                        'code' => 200,
                    ];

                    if ($request->ajax()) {
                        return response()->json($toastData);
                    }

                    return back()->with(['toast' => $toastData]);
                }
            }
        } else {
            return redirect('/login');
        }

        abort(404);
    }

}
