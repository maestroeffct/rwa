<?php

namespace App\Http\Controllers\Admin\Store;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;

class ProductFeaturedContentsController extends Controller
{

    public function featuredProducts(Request $request)
    {
        $this->authorize('admin_store_featured_products');
        removeContentLocale();

        $products = Product::query()->get();

        $setting = Setting::where('page', 'general')
            ->where('name', Setting::$storeFeaturedProductsSettingsName)
            ->first();

        $settingValues = !empty($setting) ? $setting->value : null;

        if (!empty($settingValues)) {
            $settingValues = json_decode($settingValues, true);
        }


        $data = [
            'pageTitle' => trans('update.featured_products'),
            'products' => $products,
            'settingValues' => $settingValues,
        ];

        return view('admin.store.featured_products.index', $data);
    }


    public function featuredCategories(Request $request)
    {
        $this->authorize("admin_store_featured_categories");
    }
}
