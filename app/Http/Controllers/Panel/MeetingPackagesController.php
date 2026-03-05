<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\MeetingPackage;
use App\Models\Translation\MeetingPackageTranslation;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $user = auth()->user();
        $query = MeetingPackage::query()->where('creator_id', $user->id);

        //$copyQuery = deepClone($query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        return $getListData;
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $meetingPackages = $query
            ->with([
                'sales'
            ])
            ->orderBy('created_at', 'desc')
            ->get();


        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $meetingPackages, $total, $count);
        }

        return [
            'meetingPackages' => $meetingPackages,
            'meetingPackagesPagination' => $this->makePagination($request, $meetingPackages, $total, $count, true, '/panel/meetings/packages'),
        ];
    }

    private function getAjaxResponse(Request $request, $meetingPackages, $total, $count)
    {
        $html = "";

        foreach ($meetingPackages as $meetingPackageRow) {
            $html .= (string)view()->make('design_1.panel.meeting.settings.tabs.meeting_packages.table_items', ['meetingPackage' => $meetingPackageRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $meetingPackages, $total, $count, true)
        ]);
    }


    public function store(Request $request)
    {
        $user = auth()->user();

        $userPackage = new UserPackage($user);
        $userMeetingPackageCountLimited = $userPackage->checkPackageLimit('meeting_packages_count');

        if ($userMeetingPackageCountLimited) {
            session()->put('registration_package_limited', $userMeetingPackageCountLimited);

            return redirect()->back();
        }

        $this->validate($request, [
            'title' => 'required|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'duration' => 'required|numeric',
            'duration_type' => 'required|in:day,week,month,year',
            'sessions' => 'required|numeric',
            'session_duration' => 'required|numeric',
            'price' => 'nullable|check_price',
            'discount' => 'nullable|numeric',
        ]);

        $storeData = $this->makeStoreData($request);
        $meetingPackage = MeetingPackage::query()->create($storeData);

        // Extra Data
        $this->handleExtraData($request, $meetingPackage);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.meeting_package_created_successfully'),
            'status' => 'success',
        ];
        return redirect("/panel/meetings/settings?tab=packages")->with(['toast' => $toastData]);
    }

    public function edit($id)
    {
        $user = auth()->user();
        $meetingPackage = MeetingPackage::query()->where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($meetingPackage)) {
            return [
                'pageTitle' => trans('public.edit'). ' ' . $meetingPackage->title,
                'meetingPackageEditItem' => $meetingPackage,
            ];
        }

        return [];
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $meetingPackage = MeetingPackage::query()->where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($meetingPackage)) {
            $this->validate($request, [
                'title' => 'required|max:255',
                'icon' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'duration' => 'required|numeric',
                'duration_type' => 'required|in:day,week,month,year',
                'sessions' => 'required|numeric',
                'session_duration' => 'required|numeric',
                'price' => 'nullable|check_price',
                'discount' => 'nullable|numeric',
            ]);

            $storeData = $this->makeStoreData($request, $meetingPackage);
            $meetingPackage->update($storeData);

            // Extra Data
            $this->handleExtraData($request, $meetingPackage);

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.meeting_package_updated_successfully'),
                'status' => 'success',
            ];
            return redirect("/panel/meetings/settings?tab=packages")->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function delete($id)
    {
        $user = auth()->user();
        $meetingPackage = MeetingPackage::query()->where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($meetingPackage)) {
            if (!empty($meetingPackage->icon)) {
                $this->removeFile($meetingPackage->icon);
            }

            $meetingPackage->delete();

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.meeting_package_deleted_successfully'),
            ]);
        }

        return response()->json([], 422);
    }

    private function makeStoreData(Request $request, $meetingPackage = null): array
    {
        $user = auth()->user();
        $data = $request->all();

        return [
            'creator_id' => $user->id,
            'duration' => $data['duration'],
            'duration_type' => $data['duration_type'],
            'sessions' => $data['sessions'],
            'session_duration' => $data['session_duration'],
            'price' => !empty($data['price']) ? convertPriceToDefaultCurrency($data['price']) : null,
            'discount' => !empty($data['discount']) ? $data['discount'] : null,
            'enable' => (!empty($data['enable']) and $data['enable'] == 'on'),
            'created_at' => !empty($meetingPackage) ? $meetingPackage->created_at : time(),
        ];
    }

    private function handleExtraData(Request $request, $meetingPackage)
    {
        $data = $request->all();
        $locale = $request->get('locale', getDefaultLocale());

        MeetingPackageTranslation::query()->updateOrCreate([
            'meeting_package_id' => $meetingPackage->id,
            'locale' => mb_strtolower($locale),
        ], [
            'title' => $data['title'],
        ]);


        $iconPath = $meetingPackage->icon ?? null;
        $iconFileUpload = $request->file('icon');

        if (!empty($iconFileUpload)) {
            if (!empty($meetingPackage->icon)) {
                $this->removeFile($meetingPackage->icon);
            }

            $iconPath = $this->uploadFile($iconFileUpload, "meeting_packages/{$meetingPackage->id}", 'icon', $meetingPackage->creator_id);
        }

        $meetingPackage->update([
            'icon' => $iconPath,
        ]);
    }


}
