<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Section;
use App\Models\Translation\RoleTranslation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('admin_roles_list');

        $roles = Role::with('users')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/roles.page_lists_title'),
            'roles' => $roles,
        ];

        return view('admin.roles.lists', $data);
    }

    public function create(Request $request)
    {
        $this->authorize('admin_roles_create');

        $sections = Section::whereNull('section_group_id')
            ->with('children')
            ->get();

        $locale = $request->get('locale', app()->getLocale());

        $data = [
            'pageTitle' => trans('admin/main.role_new_page_title'),
            'sections' => $sections,
            'locale' => $locale
        ];

        return view('admin.roles.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_roles_create');

        $this->validate($request, [
            'name' => 'required|min:3|max:64|unique:roles,name',
            'caption' => 'required|min:3|max:64|unique:role_translations,caption',
        ]);

        $data = $request->all();

        $role = Role::create([
            'name' => $data['name'],
            'is_admin' => (!empty($data['is_admin']) and $data['is_admin'] == 'on'),
            'created_at' => time(),
        ]);

        RoleTranslation::updateOrCreate([
            'role_id' => $role->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'caption' => $data['caption'],
        ]);

        if ($request->has('permissions')) {
            $this->storePermission($role, $data['permissions']);
        }

        Cache::forget('sections');

        return redirect(getAdminPanelUrl("/roles/{$role->id}/edit"));
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_roles_edit');

        $role = Role::findOrFail($id);
        $permissions = Permission::where('role_id', '=', $role->id)->get();
        $sections = Section::whereNull('section_group_id')
            ->with('children')
            ->get();

        $locale = $request->get('locale', app()->getLocale());

        $data = [
            'pageTitle' => trans('/admin/main.edit'),
            'role' => $role,
            'sections' => $sections,
            'permissions' => $permissions->keyBy('section_id'),
            'locale' => mb_strtolower($locale)
        ];

        return view('admin.roles.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_roles_edit');

        $role = Role::find($id);

        $this->validate($request, [
            'caption' => 'required',
        ]);

        $data = $request->all();

        $role->update([
            'is_admin' => ((!empty($data['is_admin']) and $data['is_admin'] == 'on') or $role->name == Role::$admin),
        ]);

        $locale = $data['locale'];
        RoleTranslation::updateOrCreate([
            'role_id' => $role->id,
            'locale' => mb_strtolower($locale),
        ], [
            'caption' => $data['caption'],
        ]);

        Permission::where('role_id', '=', $role->id)->delete();

        if (!empty($data['permissions'])) {
            $this->storePermission($role, $data['permissions']);
        }

        Cache::forget('sections');

        return redirect(getAdminPanelUrl("/roles/{$role->id}/edit?locale={$locale}"));
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('admin_roles_delete');

        $role = Role::find($id);

        if ($role->canDelete()) {
            $role->delete();

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('admin/main.role_deleted_success'),
                'status' => 'success'
            ];
        } else {
            $toastData = [
                'title' => trans('public.request_failed'),
                'msg' => trans('admin/main.role_cannot_delete'),
                'status' => 'error'
            ];
        }

        return redirect(getAdminPanelUrl("/roles"))->with(['toast' => $toastData]);
    }

    public function storePermission($role, $sections)
    {
        $sectionsId = Section::whereIn('id', $sections)->pluck('id');
        $permissions = [];
        foreach ($sectionsId as $section_id) {
            $permissions[] = [
                'role_id' => $role->id,
                'section_id' => $section_id,
                'allow' => true,
            ];
        }
        Permission::insert($permissions);
    }
}
