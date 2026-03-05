<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MeetingPackage;
use App\Models\MeetingPackageSold;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\Request;

class PurchasedMeetingPackagesController extends Controller
{

    public function __construct()
    {
        if (empty(getMeetingPackagesSettings("status"))) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = MeetingPackageSold::query()->where('user_id', $user->id);

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $topStats = $this->handleTopStats($copyQuery);

        $allMeetingPackagesIds = deepClone($copyQuery)->pluck('meeting_package_id')->unique()->toArray();
        $allMeetingPackages = MeetingPackage::query()->whereIn('id', $allMeetingPackagesIds)->get();

        $allInstructorsIds = $allMeetingPackages->pluck('creator_id')->unique()->toArray();
        $allInstructors = User::query()->whereIn('id', $allInstructorsIds)->get();


        $data = [
            'pageTitle' => trans('update.purchased_meeting_packages'),
            'allMeetingPackages' => $allMeetingPackages,
            'allInstructors' => $allInstructors,
        ];
        $data = array_merge($data, $topStats);
        $data = array_merge($data, $getListData);

        return view('design_1.panel.meeting.purchased_packages.lists.index', $data);
    }

    private function handleTopStats(Builder $query): array
    {
        $totalPurchasedPackages = deepClone($query)->count();
        $totalDurations = 0;
        $openPackages = 0;
        $finishedPackages = 0;

        $meetingPackagesSold = deepClone($query)->get();

        foreach ($meetingPackagesSold as $meetingPackageSold) {
            if ($meetingPackageSold->status == "finished") {
                $finishedPackages += 1;
            } else {
                $openPackages += 1;
            }

            $totalDurations += $meetingPackageSold->getTotalDuration();
        }

        return [
            'totalPurchasedPackages' => $totalPurchasedPackages,
            'totalDurations' => $totalDurations,
            'totalOpenPackages' => $openPackages,
            'totalFinishedPackages' => $finishedPackages,
        ];
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $search = $request->get('search');
        $meetingPackageId = $request->get('meeting_package_id');
        $purchaseStartDate = $request->get('purchase_start_date');
        $purchaseEndDate = $request->get('purchase_end_date');
        $instructorId = $request->get('instructor_id');
        $status = $request->get('status');
        $sort = $request->get('sort');

        // Date
        $query = fromAndToDateFilter($purchaseStartDate, $purchaseEndDate, $query, 'created_at');

        if (!empty($search)) {
            $query->whereHas('meetingPackage', function (Builder $query) use ($search) {
                $query->whereHas('creator', function (Builder $query) use ($search) {
                    $query->where('full_name', 'like', '%' . $search . '%');
                });
            });
        }

        if (!empty($meetingPackageId)) {
            $query->where('meeting_package_id', $meetingPackageId);
        }

        if (!empty($instructorId)) {
            $query->whereHas('meetingPackage', function (Builder $query) use ($instructorId) {
                $query->where('creator_id', $instructorId);
            });
        }

        if (!empty($status)) {
            if ($status == 'open') {
                $query->where(function ($query) use ($status) {
                    $query->whereHas('sessions', function (Builder $query) {
                        $query->where('status', '!=', 'finished');
                    });

                    $query->where('expire_at', '>', time());
                });

            } elseif ($status == 'finished') {
                $query->where(function ($query) use ($status) {
                    $query->whereDoesntHave('sessions', function (Builder $query) {
                        $query->where('status', '!=', 'finished');
                    });

                    $query->orWhere('expire_at', '<', time());
                });
            }
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'paid_amount_asc':
                    $query->orderBy('paid_amount', 'asc');
                    break;
                case 'paid_amount_desc':
                    $query->orderBy('paid_amount', 'desc');
                    break;
                case 'purchase_date_asc':
                    $query->orderBy('paid_at', 'asc');
                    break;
                case 'purchase_date_desc':
                    $query->orderBy('paid_at', 'desc');
                    break;
                case 'expiry_date_asc':
                    $query->orderBy('expire_at', 'asc');
                    break;
                case 'expiry_date_desc':
                    $query->orderBy('expire_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('paid_at', 'desc');
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

        $meetingPackagesSold = $query
            ->with([
                'meetingPackage' => function ($query) {
                    $query->with([
                        'creator' => function ($query) {
                            $query->select('id', 'full_name', 'role_name', 'role_id', 'email', 'mobile', 'username', 'avatar', 'avatar_settings');
                        },
                    ]);
                },
                'sessions',
            ])
            ->withCount([
                'sessions'
            ])
            ->get();

        $time = time();

        foreach ($meetingPackagesSold as $meetingPackageSold) {
            $meetingPackageSold = $meetingPackageSold->handleExtraData();
        }


        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $meetingPackagesSold, $total, $count);
        }

        return [
            'meetingPackagesSold' => $meetingPackagesSold,
            'pagination' => $this->makePagination($request, $meetingPackagesSold, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $meetingPackagesSold, $total, $count)
    {
        $html = "";

        foreach ($meetingPackagesSold as $meetingPackageRow) {
            $html .= (string)view()->make('design_1.panel.meeting.purchased_packages.lists.table_items', ['meetingPackageSold' => $meetingPackageRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $meetingPackagesSold, $total, $count, true)
        ]);
    }

    public function getInstructorDetail($id)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($meetingPackageSold)) {

            $html = (string)view()->make("design_1.panel.meeting.modals.contact_info", [
                'userInfo' => $meetingPackageSold->meetingPackage->creator,
                'meetingPackageSold' => $meetingPackageSold,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

}
