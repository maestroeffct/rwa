<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\SpecialOffer;
use App\Models\Ticket;
use App\Models\Webinar;
use App\Models\WebinarFilterOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassesController extends Controller
{
    public $tableName = 'webinars';
    public $columnId = 'webinar_id';


    public function index(Request $request)
    {
        $webinarsQuery = Webinar::where('webinars.status', 'active');

        $type = $request->get('type');

        if (!empty($type) and is_array($type) and in_array('bundle', $type)) {
            $webinarsQuery = Bundle::where('bundles.status', 'active');

            $this->tableName = 'bundles';
            $this->columnId = 'bundle_id';
        }

        $webinarsQuery->where('private', false); // Ignore Private (Courses||Bundles)
        $webinarsQuery->where('only_for_students', false); // Ignore Available Only for Students

        $filterMaxPrice = $webinarsQuery->max('price') ?? 10000;
        $coursesRatingsCount = $this->getCoursesCountByRatings(deepClone($webinarsQuery));

        $webinarsQuery = $this->handleFilters($request, $webinarsQuery);

        $sort = $request->get('sort', null);

        if (empty($sort) or $sort == 'newest') {
            $webinarsQuery = $webinarsQuery->orderBy("{$this->tableName}.created_at", 'desc');
        }

        if (empty($sort)) {
            $webinarsQuery = $webinarsQuery->orderBy("{$this->tableName}.created_at", 'desc');
        }

        $getListData = $this->getListData($request, $webinarsQuery);

        if ($request->ajax()) {
            return $getListData;
        }


        $seoSettings = getSeoMetas('classes');
        $pageTitle = $seoSettings['title'] ?? '';
        $pageDescription = $seoSettings['description'] ?? '';
        $pageRobot = getPageRobot('classes');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'pageBasePath' => $request->getPathInfo(),
            'filterMaxPrice' => ($filterMaxPrice > 1000) ? $filterMaxPrice : 1000,
            'coursesRatingsCount' => $coursesRatingsCount,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.web.courses.lists.classes', $data);
    }

    public function handleFilters($request, $query)
    {
        $upcoming = $request->get('upcoming', null);
        $isFree = $request->get('free', null);
        $withDiscount = $request->get('discount', null);
        $isDownloadable = $request->get('downloadable', null);
        $sort = $request->get('sort', null);
        $filterOptions = $request->get('filter_option', []);
        $typeOptions = $request->get('type', []);
        $moreOptions = $request->get('moreOptions', []);
        $instructor = $request->get('instructor', null);

        $query->whereHas('teacher', function ($query) {
            $query->where('status', 'active')
                ->where(function ($query) {
                    $query->where('ban', false)
                        ->orWhere(function ($query) {
                            $query->whereNotNull('ban_end_at')
                                ->where('ban_end_at', '<', time());
                        });
                });
        });

        if ($this->tableName == 'webinars') {

            if (!empty($upcoming) and $upcoming == 'on') {
                $query->whereNotNull('start_date')
                    ->where('start_date', '>=', time());
            }

            if (!empty($isDownloadable) and $isDownloadable == 'on') {
                $query->where('downloadable', 1);
            }

            if (!empty($typeOptions) and is_array($typeOptions)) {
                $query->whereIn("{$this->tableName}.type", $typeOptions);
            }

            if (!empty($moreOptions) and is_array($moreOptions)) {
                if (in_array('subscribe', $moreOptions)) {
                    $query->where('subscribe', 1);
                }

                if (in_array('certificate_included', $moreOptions)) {
                    $query->whereHas('quizzes', function ($query) {
                        $query->where('certificate', 1)
                            ->where('status', 'active');
                    });
                }

                if (in_array('with_quiz', $moreOptions)) {
                    $query->whereHas('quizzes', function ($query) {
                        $query->where('status', 'active');
                    });
                }

                if (in_array('featured', $moreOptions)) {
                    $query->whereHas('feature', function ($query) {
                        $query->whereIn('page', ['home_categories', 'categories'])
                            ->where('status', 'publish');
                    });
                }
            }
        }

        if (!empty($instructor)) {
            $query->where(function ($query) use ($instructor) {
                $query->where('creator_id', $instructor);
                $query->orWhere('teacher_id', $instructor);
            });
        }

        if (!empty($isFree) and $isFree == 'on') {
            $query->where(function ($qu) {
                $qu->whereNull('price')
                    ->orWhere('price', '0');
            });
        }

        if (!empty($withDiscount) and $withDiscount == 'on') {
            $now = time();
            $webinarIdsHasDiscount = [];

            $tickets = Ticket::where('start_date', '<', $now)
                ->where('end_date', '>', $now)
                ->whereNotNull("{$this->columnId}")
                ->get();

            foreach ($tickets as $ticket) {
                if ($ticket->isValid()) {
                    $webinarIdsHasDiscount[] = $ticket->{$this->columnId};
                }
            }

            $specialOffersItemIds = SpecialOffer::where('status', 'active')
                ->where('from_date', '<', $now)
                ->where('to_date', '>', $now)
                ->pluck("{$this->columnId}")
                ->toArray();

            $webinarIdsHasDiscount = array_merge($specialOffersItemIds, $webinarIdsHasDiscount);

            $webinarIdsHasDiscount = array_unique($webinarIdsHasDiscount);

            $query->whereIn("{$this->tableName}.id", $webinarIdsHasDiscount);
        }

        if (!empty($sort)) {
            if ($sort == 'expensive') {
                $query->where('price', '>', 0);
                $query->orderBy('price', 'desc');
            }

            if ($sort == 'inexpensive') {
                $query->orderBy('price', 'asc');
            }

            if ($sort == 'bestsellers') {
                $query->join('sales', function ($join) {
                    $join->on("{$this->tableName}.id", '=', "sales.{$this->columnId}")
                        ->whereNull('refund_at');
                })
                    ->whereNotNull("sales.{$this->columnId}")
                    ->addSelect("{$this->tableName}.*", "sales.{$this->columnId}", DB::raw("count(sales.{$this->columnId}) as sales_counts"))
                    ->groupBy("sales.{$this->columnId}")
                    ->orderBy('sales_counts', 'desc');
            }

            if ($sort == 'best_rates') {
                $query->join('webinar_reviews', function ($join) {
                    $join->on("{$this->tableName}.id", '=', "webinar_reviews.{$this->columnId}");
                    $join->where('webinar_reviews.status', 'active');
                })
                    //->whereNotNull('rates')
                    ->select("{$this->tableName}.*", DB::raw('avg(rates) as rates'))
                    ->groupBy("{$this->tableName}.id")
                    ->orderBy('rates', 'desc');

            }
        }

        if (!empty($filterOptions) and is_array($filterOptions)) {
            $webinarIdsFilterOptions = WebinarFilterOption::whereIn('filter_option_id', $filterOptions)
                ->pluck($this->columnId)
                ->toArray();

            $query->whereIn("{$this->tableName}.id", $webinarIdsFilterOptions);
        }

        return $query;
    }

    public function getListData(Request $request, $query)
    {
        $page = $request->get('page') ?? 1;
        $count = 9;

        $cloneQuery = deepClone($query);
        $total = DB::table(DB::raw("({$cloneQuery->toSql()}) as sub"))
            ->mergeBindings($cloneQuery->getQuery()) // bind parameters
            ->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $courses = $query->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $courses, $total, $count);
        }

        return [
            'courses' => $courses,
            'pagination' => $this->makePagination($request, $courses, $total, $count, true),
        ];
    }

    public function getAjaxResponse(Request $request, $courses, $total, $count)
    {
        $html = "";

        if ($request->get('card') == "list") {
            $html = (string)view()->make('design_1.web.courses.components.cards.rows.index', [
                'courses' => $courses,
                'rowCardClassName' => "col-12 mt-24",
                //'withoutStyles' => true
            ]);
        } else {
            $html = (string)view()->make('design_1.web.courses.components.cards.grids.index', [
                'courses' => $courses,
                'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-24",
                'withoutStyles' => true
            ]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $courses, $total, $count, true)
        ]);
    }

    public function getCoursesCountByRatings($query)
    {
        $ratings = [];

        $query2 = $query->join('webinar_reviews', function ($join) {
            $join->on("{$this->tableName}.id", '=', "webinar_reviews.{$this->columnId}");
            $join->where('webinar_reviews.status', 'active');
        })
            ->whereNotNull('rates')
            ->select("{$this->tableName}.*", DB::raw('avg(rates) as rates'))
            ->groupBy("{$this->tableName}.id");


        foreach ([5, 4, 3, 2, 1] as $rateNum) {
            $first = ($rateNum == 1) ? 0 : $rateNum;
            $next = $rateNum + 1;

            $ratings[$rateNum] = deepClone($query2)
                ->where('rates', '>=', $first)
                ->where('rates', '<', $next)
                ->get()->count();
        }

        return $ratings;
    }
}
