<?php

namespace App\Http\Controllers\Admin\Store;


use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\ProductTopCategory;
use Illuminate\Http\Request;

class ProductTopCategoriesController extends Controller
{
    public function index()
    {
        $this->authorize('admin_store_top_categories');
        removeContentLocale();

        $featuredCategories = ProductTopCategory::query()->get();
        $productCategories = ProductCategory::query()->where('parent_id', null)
            ->with('subCategories')
            ->get();

        $data = [
            'pageTitle' => trans('update.top_categories'),
            'featuredCategories' => $featuredCategories,
            'productCategories' => $productCategories,
        ];

        return view('admin.store.top_categories.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_store_top_categories');

        $this->validate($request, [
            'category_id' => 'required|exists:product_categories,id',
            'image' => 'required',
        ]);

        $data = $request->all();

        $check = ProductTopCategory::query()->where('category_id', $data['category_id'])->first();

        if (!empty($check)) {
            return redirect()->back()->withErrors([
                'category_id' => trans('update.featured_category_exists'),
            ]);
        }

        ProductTopCategory::query()->create([
            'category_id' => $data['category_id'],
            'image' => $data['image'],
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.featured_category_created_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/store/top-categories"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_store_top_categories');

        $editFeaturedCategory = ProductTopCategory::query()->findOrFail($id);

        $productCategories = ProductCategory::query()->where('parent_id', null)
            ->with('subCategories')
            ->get();

        $data = [
            'pageTitle' => trans('update.top_categories'),
            'editFeaturedCategory' => $editFeaturedCategory,
            'productCategories' => $productCategories,
        ];

        return view('admin.store.top_categories.index', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_store_top_categories');

        $featuredCategory = ProductTopCategory::query()->findOrFail($id);

        $this->validate($request, [
            'category_id' => 'required|exists:product_categories,id',
            'image' => 'required',
        ]);

        $data = $request->all();

        $check = ProductTopCategory::query()->where('id', '!=', $featuredCategory->id)
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
        return redirect(getAdminPanelUrl("/store/top-categories"))->with(['toast' => $toastData]);
    }

    public function delete($id)
    {
        $this->authorize('admin_store_top_categories');

        $featuredCategory = ProductTopCategory::query()->findOrFail($id);

        $featuredCategory->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.featured_category_deleted_successful'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/store/top-categories"))->with(['toast' => $toastData]);
    }
}
