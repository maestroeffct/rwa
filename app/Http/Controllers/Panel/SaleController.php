<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Sale;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_financial_sales_reports");

        $user = auth()->user();

        $query = Sale::query()->where('seller_id', $user->id)
            ->whereNull('refund_at');

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }


        $studentIds = deepClone($copyQuery)->pluck('buyer_id')->toArray();
        $students = User::query()->select('id', 'full_name')
            ->whereIn('id', array_unique($studentIds))
            ->get();

        $getStudentCount = count($studentIds);
        $getWebinarsCount = count(array_filter(deepClone($copyQuery)->pluck('webinar_id')->toArray()));
        $getMeetingCount = count(array_filter(deepClone($copyQuery)->pluck('meeting_id')->toArray()));


        $userWebinars = Webinar::query()->select('id', 'category_id')
            ->where('status', 'active')
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->get();

        $categoriesIds = $userWebinars->pluck('category_id')->toArray();
        $categories = Category::query()->whereIn('id', $categoriesIds)->get();

        $data = [
            'pageTitle' => trans('admin/pages/financial.sales_page_title'),
            'studentCount' => $getStudentCount,
            'webinarCount' => $getWebinarsCount,
            'meetingCount' => $getMeetingCount,
            'totalSales' => $user->getSaleAmounts(),
            'userWebinars' => $userWebinars,
            'students' => $students,
            'categories' => $categories,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.financial.sales.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $student_id = $request->get('student_id');
        $webinar_id = $request->get('webinar_id');
        $category_id = $request->get('category_id');
        $type = $request->get('type');
        $sort = $request->get('sort');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($type)) {
            if (in_array($type, ['live_class', 'text_lesson', 'course'])) {
                $query->where('type', 'webinar');

                $query->whereHas('webinar', function (Builder $query) use ($type) {
                    if ($type == "live_class") {
                        $query->where('type', 'webinar');
                    } else {
                        $query->where('type', $type);
                    }
                });
            } else {
                $query->where('type', $type);
            }
        }

        if (!empty($student_id)) {
            $query->where('buyer_id', $student_id);
        }

        if (!empty($webinar_id)) {
            $query->where('webinar_id', $webinar_id);
        }

        if (!empty($category_id)) {
            $query->whereHas('webinar', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('amount', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('amount', 'desc');
                    break;
                case 'create_date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'create_date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
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

        $sales = $query
            ->with([
                'webinar',
                'productOrder',
                'bundle',
                'registrationPackage',
                'promotion',
                'subscribe'
            ])
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $sales, $total, $count);
        }

        return [
            'sales' => $sales,
            'pagination' => $this->makePagination($request, $sales, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $sales, $total, $count)
    {
        $html = "";

        foreach ($sales as $saleRow) {
            $html .= (string)view()->make('design_1.panel.financial.sales.table_items', ['sale' => $saleRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $sales, $total, $count, true)
        ]);
    }
}
