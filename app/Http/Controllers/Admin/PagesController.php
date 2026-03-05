<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Translation\PageTranslation;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        removeContentLocale();

        $this->authorize('admin_pages_list');

        $pages = Page::orderBy('created_at', 'desc')->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/setting.pages'),
            'pages' => $pages
        ];

        return view('admin.pages.lists', $data);
    }

    public function create()
    {
        $this->authorize('admin_pages_create');

        $data = [
            'pageTitle' => trans('admin/pages/setting.new_pages')
        ];

        return view('admin.pages.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_pages_create');

        $this->validate($request, [
            'locale' => 'required',
            'name' => 'required',
            'link' => 'required|unique:pages,link',
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'icon' => 'required|string',
            'cover' => 'required|string',
            'header_icon' => 'required|string',
            'seo_description' => 'nullable|string|max:255',
            'content' => 'required',
        ]);


        $storeData = $this->makeStoreData($request);
        $page = Page::create($storeData);

        $this->handleExtraData($request, $page);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.new_page_created_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/pages/{$page->id}/edit"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_pages_edit');

        $locale = $request->get('locale', app()->getLocale());

        $page = Page::findOrFail($id);

        storeContentLocale($locale, $page->getTable(), $page->id);

        $data = [
            'pageTitle' => trans('admin/pages/setting.edit_pages') . $page->name,
            'page' => $page
        ];

        return view('admin.pages.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_pages_edit');

        $page = Page::findOrFail($id);

        $this->validate($request, [
            'locale' => 'required',
            'name' => 'required',
            'link' => 'required|unique:pages,link,' . $page->id,
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'icon' => 'required|string',
            'cover' => 'required|string',
            'header_icon' => 'required|string',
            'seo_description' => 'nullable|string|max:255',
            'content' => 'required',
        ]);

        $storeData = $this->makeStoreData($request);
        $page->update($storeData);

        $this->handleExtraData($request, $page);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.the_page_updated_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/pages/{$page->id}/edit"))->with(['toast' => $toastData]);
    }

    private function makeStoreData(Request $request, $page = null)
    {
        $data = $request->all();

        $firstCharacter = substr($data['link'], 0, 1);
        if ($firstCharacter !== '/') {
            $data['link'] = '/' . $data['link'];
        }

        return [
            'link' => $data['link'],
            'name' => $data['name'],
            'icon' => $data['icon'],
            'cover' => $data['cover'],
            'header_icon' => $data['header_icon'],
            'robot' => $data['robot'] == '1',
            'status' => $data['status'],
            'created_at' => !empty($page) ? $page->created_at : time(),
        ];
    }

    private function handleExtraData(Request $request, $page)
    {
        $data = $request->all();

        PageTranslation::updateOrCreate([
            'page_id' => $page->id,
            'locale' => mb_strtolower($data['locale'])
        ], [
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
            'seo_description' => $data['seo_description'] ?? null,
            'content' => $data['content'],
        ]);
    }

    public function delete($id)
    {
        $this->authorize('admin_pages_delete');

        $page = Page::findOrFail($id);

        $page->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.the_page_deleted_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/pages"))->with(['toast' => $toastData]);
    }

    public function statusTaggle($id)
    {
        $this->authorize('admin_pages_toggle');

        $page = Page::findOrFail($id);

        $page->update([
            'status' => ($page->status == 'draft') ? 'publish' : 'draft'
        ]);

        return redirect(getAdminPanelUrl() . '/pages');
    }
}
