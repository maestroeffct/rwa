<?php

namespace App\Http\Controllers\Web;


use App\Bitwise\UserLevelOfTraining;
use App\Http\Controllers\Controller;
use App\Mixins\MeetingPackages\MeetingPackageSoldMixins;
use App\Models\MeetingPackage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sale;
use App\Models\UserMeta;
use App\Models\UserOccupation;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MeetingPackagesController extends Controller
{

    public function __construct()
    {
        if (empty(getMeetingPackagesSettings("status"))) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $query = MeetingPackage::query()->where('enable', true);
        $query->whereHas('creator', function (Builder $query) {
            $query->whereDoesntHave('meeting');

            $query->orWhereHas('meeting', function (Builder $query) {
                $query->where('enable_meeting_packages', true);
            });
        });

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $seoSettings = getSeoMetas('meeting_packages_lists');
        $pageTitle = $seoSettings['title'] ?? trans('update.meeting_packages');
        $pageDescription = $seoSettings['description'] ?? '';
        $pageRobot = getPageRobot('meeting_packages_lists');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'seoSettings' => $seoSettings,
            'filterMaxPrice' => deepClone($copyQuery)->max('price'),
            'filterMaxDuration' => deepClone($copyQuery)->max('session_duration'),
            'pageBasePath' => "/meeting-packages",
        ];

        $data = array_merge($data, $getListData);

        return view('design_1.web.meeting_packages.lists.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $instructor = $request->get('instructor');
        $categoryId = $request->get('category_id');
        $levelOfTraining = $request->get('level_of_training');
        $gender = $request->get('gender');
        $role = $request->get('role');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $minDuration = $request->get('min_duration');
        $maxDuration = $request->get('max_duration');
        $free = $request->get('free');
        $discount = $request->get('discount');
        $sort = $request->get('sort');

        if (!empty($instructor)) {
            $query->where('creator_id', $instructor);
        }

        if (!empty($categoryId)) {
            $userIds = UserOccupation::where('category_id', $categoryId)->pluck('user_id')->toArray();

            $query->whereIn('creator_id', $userIds);
        }

        if (!empty($levelOfTraining) and in_array($levelOfTraining, UserLevelOfTraining::$levelOfTraining)) {
            $levelBit = (new UserLevelOfTraining())->getValue($levelOfTraining);

            $userIds = User::query()->where('users.status', 'active')
                ->whereRaw('users.level_of_training & ? > 0', [$levelBit])
                ->pluck('id')
                ->toArray();

            $query->whereIn('creator_id', $userIds);
        }

        if (!empty($gender)) {
            $userIds = UserMeta::where('name', 'gender')
                ->where('value', $gender)
                ->pluck('user_id')
                ->toArray();

            $query->whereIn('creator_id', $userIds);
        }

        if (!empty($role)) {
            $query->whereHas('creator', function ($query) use ($role) {
                $query->where('role_name', $role);
            });
        }

        if (!empty($minPrice)) {
            $query->where('price', '>', $minPrice);
        }

        if (!empty($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }

        if (!empty($minDuration)) {
            $query->where('session_duration', '>', $minDuration);
        }

        if (!empty($maxDuration)) {
            $query->where('session_duration', '<=', $maxDuration);
        }

        if (!empty($free)) {
            $query->where(function ($query) {
                $query->whereNull('price');
                $query->orWhere('price', '<=', 0);
            });
        }

        if (!empty($discount)) {
            $query->whereNotNull('discount');
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'lowest_price':
                    $query->orderBy('price', 'asc');
                    break;
                case 'highest_price':
                    $query->orderBy('price', 'desc');
                    break;
                case 'sessions_asc':
                    $query->orderBy('sessions', 'asc');
                    break;
                case 'sessions_desc':
                    $query->orderBy('sessions', 'desc');
                    break;
                case 'meeting_hours_asc':
                    $query->orderBy('session_duration', 'asc');
                    break;
                case 'meeting_hours_desc':
                    $query->orderBy('session_duration', 'desc');
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

        $meetingPackages = $query->with([
            'creator' => function ($query) {
                $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio', 'about', 'verified', 'cover_img', 'profile_secondary_image');
            }
        ])->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $meetingPackages, $total, $count);
        }

        return [
            'meetingPackages' => $meetingPackages,
            'pagination' => $this->makePagination($request, $meetingPackages, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $meetingPackages, $total, $count)
    {
        $html = (string)view()->make('design_1.web.meeting_packages.components.cards.grids.index', [
            'meetingPackages' => $meetingPackages,
            'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-24",
            'withoutStyles' => true
        ]);

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $meetingPackages, $total, $count, true)
        ]);
    }

    public function buyFree($id)
    {
        $user = auth()->user();
        if (empty($user)) {
            return redirect("/login");
        }

        $query = MeetingPackage::query()->where('enable', true);
        $query->whereHas('creator', function (Builder $query) {
            $query->whereDoesntHave('meeting');

            $query->orWhereHas('meeting', function (Builder $query) {
                $query->where('enable_meeting_packages', true);
            });
        });

        $query->where('id', $id);
        $meetingPackage = $query->first();

        if (!empty($meetingPackage)) {
            if (!empty($meetingPackage->price) and $meetingPackage->price > 0) {
                $toastData = [
                    'title' => trans('cart.fail_purchase'),
                    'msg' => trans('update.meeting_package_not_free'),
                    'status' => 'error'
                ];

                return back()->with(['toast' => $toastData]);
            }

            $orderItem = (new OrderItem());
            $orderItem->user_id = $user->id;
            $orderItem->user = $user;
            $orderItem->meeting_package_id = $meetingPackage->id;
            $orderItem->amount = 0;
            $orderItem->total_amount = 0;
            $orderItem->meetingPackage = $meetingPackage;


            $sale = Sale::create([
                'buyer_id' => $user->id,
                'seller_id' => $meetingPackage->creator_id,
                'meeting_package_id' => $meetingPackage->id,
                'type' => Order::$meetingPackage,
                'payment_method' => Sale::$credit,
                'amount' => 0,
                'total_amount' => 0,
                'created_at' => time(),
            ]);

            Sale::handleSaleNotifications($orderItem, $meetingPackage->creator_id);

            // handle sessions
            (new MeetingPackageSoldMixins())->makeUserSessions($orderItem, $sale);

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.success_pay_msg_for_free_meeting_package'),
                'status' => 'success',
                'code' => 200,
            ];

            return back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

}
