<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Translation\BlogCategoryTranslation;
use Illuminate\Http\Request;

class BlogCategoriesController extends Controller
{
    public function index()
    {
        $this->authorize('admin_blog_categories');
        removeContentLocale();

        $blogCategories = BlogCategory::withCount('blog')->get();

        $data = [
            'pageTitle' => trans('admin/pages/blog.blog_categories'),
            'blogCategories' => $blogCategories
        ];

        return view('admin.blog.categories', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_blog_categories_create');

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|max:255|unique:blog_categories,slug',
        ]);

        $data = $request->all();

        $category = BlogCategory::create([
            'slug' => !empty($data['slug']) ? $data['slug'] : BlogCategory::makeSlug($data['title']),
            'icon' => !empty($data['icon']) ? $data['icon'] : null,
            'cover_image' => !empty($data['cover_image']) ? $data['cover_image'] : null,
            'icon2' => !empty($data['icon2']) ? $data['icon2'] : null,
            'icon2_box_color' => !empty($data['icon2_box_color']) ? $data['icon2_box_color'] : null,
            'overlay_image' => !empty($data['overlay_image']) ? $data['overlay_image'] : null,
        ]);

        BlogCategoryTranslation::query()->updateOrCreate([
            'blog_category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.category_created_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/blog/categories/{$category->id}/edit"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $category_id)
    {
        $this->authorize('admin_blog_categories_edit');

        $editCategory = BlogCategory::findOrFail($category_id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $editCategory->getTable(), $editCategory->id);

        $data = [
            'pageTitle' => trans('admin/pages/blog.blog_categories'),
            'editCategory' => $editCategory
        ];

        return view('admin.blog.categories', $data);
    }

    public function update(Request $request, $category_id)
    {
        $this->authorize('admin_blog_categories_edit');

        $category = BlogCategory::findOrFail($category_id);

        $this->validate($request, [
            'title' => 'required',
            'slug' => 'nullable|max:255|unique:blog_categories,slug,' . $category->id,
        ]);

        $data = $request->all();

        $category->update([
            'slug' => !empty($data['slug']) ? $data['slug'] : BlogCategory::makeSlug($data['title']),
            'icon' => !empty($data['icon']) ? $data['icon'] : null,
            'cover_image' => !empty($data['cover_image']) ? $data['cover_image'] : null,
            'icon2' => !empty($data['icon2']) ? $data['icon2'] : null,
            'icon2_box_color' => !empty($data['icon2_box_color']) ? $data['icon2_box_color'] : null,
            'overlay_image' => !empty($data['overlay_image']) ? $data['overlay_image'] : null,
        ]);


        BlogCategoryTranslation::query()->updateOrCreate([
            'blog_category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.category_updated_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/blog/categories/{$category->id}/edit"))->with(['toast' => $toastData]);
    }

    public function delete($category_id)
    {
        $this->authorize('admin_blog_categories_delete');

        $editCategory = BlogCategory::findOrFail($category_id);

        $editCategory->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.category_deleted_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl() . '/blog/categories')->with(['toast' => $toastData]);
    }
}
