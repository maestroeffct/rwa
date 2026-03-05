<?php

namespace App\Http\Controllers\Admin\Store;


use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\ProductFeaturedCategory;
use Illuminate\Http\Request;

class ProductFeaturedCategoriesController extends Controller
{
    public function index()
    {
        $this->authorize('admin_store_featured_categories');
        removeContentLocale();

        $featuredCategories = ProductFeaturedCategory::query()->get();
        $productCategories = ProductCategory::query()->where('parent_id', null)
            ->with('subCategories')
            ->get();

        $data = [
            'pageTitle' => trans('update.featured_categories'),
            'featuredCategories' => $featuredCategories,
            'productCategories' => $productCategories,
        ];

        return view('admin.store.featured_categories.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_store_featured_categories');

        $this->validate($request, [
            'category_id' => 'required|exists:product_categories,id',
            'image' => 'required',
        ]);

        $data = $request->all();

        $check = ProductFeaturedCategory::query()->where('category_id', $data['category_id'])->first();

        if (!empty($check)) {
            return redirect()->back()->withErrors([
                'category_id' => trans('update.featured_category_exists'),
            ]);
        }

        ProductFeaturedCategory::query()->create([
            'category_id' => $data['category_id'],
            'image' => $data['image'],
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.featured_category_created_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/store/featured-categories"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_store_featured_categories');

        $editFeaturedCategory = ProductFeaturedCategory::query()->findOrFail($id);

        $productCategories = ProductCategory::query()->where('parent_id', null)
            ->with('subCategories')
            ->get();

        $data = [
            'pageTitle' => trans('update.featured_categories'),
            'editFeaturedCategory' => $editFeaturedCategory,
            'productCategories' => $productCategories,
        ];

        return view('admin.store.featured_categories.index', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_store_featured_categories');

        $featuredCategory = ProductFeaturedCategory::query()->findOrFail($id);

        $this->validate($request, [
            'category_id' => 'required|exists:product_categories,id',
            'image' => 'required',
        ]);

        $data = $request->all();

        $check = ProductFeaturedCategory::query()->where('id', '!=', $featuredCategory->id)
            ->where('category_id', $data['category_id'])
            ->first();

        if (!empty($check)) {
            return redirect()->back()->withErrors([
                'category_id' => trans('update.featured_category_exists'),
            ]);
        }

        $featuredCategory->update([
            'category_id' => $data['category_id'],
            'image' => $data['image'],
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.featured_category_updated_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/store/featured-categories"))->with(['toast' => $toastData]);
    }

    public function delete($id)
    {
        $this->authorize('admin_store_featured_categories');

        $featuredCategory = ProductFeaturedCategory::query()->findOrFail($id);

        $featuredCategory->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.featured_category_deleted_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/store/featured-categories"))->with(['toast' => $toastData]);
    }
}
