<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingPackage;
use App\Models\MeetingPackageSold;
use App\Models\Role;
use App\Models\Translation\MeetingPackageTranslation;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MeetingPackagesController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("admin_meeting_packages_lists");

        $query = MeetingPackage::query();

        $meetingPackages = $this->handleListsFilters($request, $query)
            ->with([
                'creator' => function ($query) {
                    $query->select('id', 'username', 'full_name', 'email', 'mobile', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'sales'
            ])
            ->paginate(10);

        $roles = Role::query()->get();

        $data = [
            'pageTitle' => trans('update.meeting_packages'),
            'meetingPackages' => $meetingPackages,
            'roles' => $roles,
        ];
        $data = array_merge($data, $this->getPageTopStats());

        $creatorsIds = $request->get('creator_ids', []);
        if (!empty($creatorsIds)) {
            $data['creators'] = User::query()->whereIn('id', $creatorsIds)->get();
        }

        return view('admin.meeting_packages.lists.index', $data);
    }

    private function getPageTopStats(): array
    {
        $query = MeetingPackage::query();

        $totalPackages = deepClone($query)->count();

        $totalInstructorsPackages = deepClone($query)->whereHas('creator', function ($query) {
            $query->where('role_name', Role::$teacher);
        })->count();

        $totalOrganizationPackages = deepClone($query)->whereHas('creator', function ($query) {
            $query->where('role_name', Role::$organization);
        })->count();

        $totalSoldPackages = MeetingPackageSold::query()->count();

        return [
            'totalPackages' => $totalPackages,
            'totalInstructorsPackages' => $totalInstructorsPackages,
            'totalOrganizationPackages' => $totalOrganizationPackages,
            'totalSoldPackages' => $totalSoldPackages,
        ];
    }

    private function handleListsFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $search = $request->get('search');
        $creatorIds = $request->get('creator_ids');
        $roleId = $request->get('role_id');
        $status = $request->get('status');
        $sort = $request->get('sort');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($search)) {
            $query->whereTranslationLike('title', '%' . $search . '%');
        }

        if (!empty($creatorIds) and is_array($creatorIds)) {
            $query->whereIn('creator_id', $creatorIds);
        }

        if (!empty($roleId)) {
            $query->whereHas('creator', function ($query) use ($roleId) {
                $query->where('role_id', $roleId);
            });
        }

        if (!empty($status)) {
            if ($status == 'active') {
                $query->where('enable', true);
            } elseif ($status == 'disabled') {
                $query->where('enable', false);
            }
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'sessions_asc':
                    $query->orderBy('sessions', 'asc');
                    break;
                case 'sessions_desc':
                    $query->orderBy('sessions', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'created_date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function create()
    {
        $this->authorize("admin_meeting_packages_create");

        $data = [
            'pageTitle' => trans('update.new_package'),
        ];

        return view('admin.meeting_packages.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("admin_meeting_packages_create");

        $this->validate($request, [
            'creator_id' => 'required|exists:users,id',
            'title' => 'required|max:255',
            'icon' => 'nullable|string|max:255',
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
        return redirect(getAdminPanelUrl("/meeting-packages/{$meetingPackage->id}/edit"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize("admin_meeting_packages_create");

        $meetingPackage = MeetingPackage::query()->findOrFail($id);
        $locale = $request->get('locale', getDefaultLocale());

        $data = [
            'pageTitle' => trans('public.edit') . ' ' . $meetingPackage->title,
            'meetingPackage' => $meetingPackage,
            'locale' => mb_strtolower($locale),
        ];
        return view('admin.meeting_packages.create.index', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize("admin_meeting_packages_create");

        $meetingPackage = MeetingPackage::query()->findOrFail($id);

        $this->validate($request, [
            'creator_id' => 'required|exists:users,id',
            'title' => 'required|max:255',
            'icon' => 'nullable|string|max:255',
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
        return redirect(getAdminPanelUrl("/meeting-packages/{$meetingPackage->id}/edit"))->with(['toast' => $toastData]);
    }

    public function delete($id)
    {
        $this->authorize("admin_meeting_packages_delete");

        $meetingPackage = MeetingPackage::query()->findOrFail($id);

        if (!empty($meetingPackage->icon)) {
            $this->removeFile($meetingPackage->icon);
        }

        $meetingPackage->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.meeting_package_deleted_successfully'),
            'status' => 'success',
        ];
        return redirect(getAdminPanelUrl("/meeting-packages"))->with(['toast' => $toastData]);
    }


    private function makeStoreData(Request $request, $meetingPackage = null): array
    {
        $data = $request->all();

        return [
            'creator_id' => $data['creator_id'],
            'icon' => $data['icon'] ?? null,
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

    }

    public function search(Request $request)
    {
        $term = $request->get('term');

        $meetingPackages = MeetingPackage::select('id')
            ->whereTranslationLike('title', "%$term%")
            ->get();

        $result = [];
        foreach ($meetingPackages as $item) {
            $result[] = [
                'id' => $item->id,
                'title' => $item->title,
            ];
        }

        return response()->json($result, 200);
    }

}
