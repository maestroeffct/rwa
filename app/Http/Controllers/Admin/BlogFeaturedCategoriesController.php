<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogFeaturedCategory;
use Illuminate\Http\Request;

class BlogFeaturedCategoriesController extends Controller
{
    public function index()
    {
        $this->authorize('admin_blog_featured_categories');
        removeContentLocale();

        $featuredCategories = BlogFeaturedCategory::query()->get();
        $blogCategories = BlogCategory::query()->get();

        $data = [
            'pageTitle' => trans('update.featured_categories'),
            'featuredCategories' => $featuredCategories,
            'blogCategories' => $blogCategories,
        ];

        return view('admin.blog.featured_categories', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_blog_featured_categories');

        $this->validate($request, [
            'category_id' => 'required|exists:blog_categories,id',
            'thumbnail' => 'required',
        ]);

        $data = $request->all();

        $check = BlogFeaturedCategory::query()->where('category_id', $data['category_id'])->first();

        if (!empty($check)) {
            return redirect()->back()->withErrors([
                'category_id' => trans('update.featured_category_exists'),
            ]);
        }

        BlogFeaturedCategory::query()->create([
            'category_id' => $data['category_id'],
            'thumbnail' => $data['thumbnail'],
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.featured_category_created_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/blog/featured-categories"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_blog_featured_categories');

        $editFeaturedCategory = BlogFeaturedCategory::query()->findOrFail($id);


        $blogCategories = BlogCategory::query()->get();

        $data = [
            'pageTitle' => trans('update.featured_categories'),
            'editFeaturedCategory' => $editFeaturedCategory,
            'blogCategories' => $blogCategories,
        ];

        return view('admin.blog.featured_categories', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_blog_featured_categories');

        $featuredCategory = BlogFeaturedCategory::query()->findOrFail($id);

        $this->validate($request, [
            'category_id' => 'required|exists:blog_categories,id',
            'thumbnail' => 'required',
        ]);

        $data = $request->all();

        $check = BlogFeaturedCategory::query()->where('id', '!=', $featuredCategory->id)
            ->where('category_id', $data['category_id'])
            ->first();

        if (!empty($check)) {
            return redirect()->back()->withErrors([
                'category_id' => trans('update.featured_category_exists'),
            ]);
        }

        $featuredCategory->update([
            'category_id' => $data['category_id'],
            'thumbnail' => $data['thumbnail'],
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.featured_category_updated_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/blog/featured-categories"))->with(['toast' => $toastData]);
    }

    public function delete($id)
    {
        $this->authorize('admin_blog_featured_categories');

        $featuredCategory = BlogFeaturedCategory::query()->findOrFail($id);

        $featuredCategory->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.featured_category_deleted_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/blog/featured-categories"))->with(['toast' => $toastData]);
    }
}
