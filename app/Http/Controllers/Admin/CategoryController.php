<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Translation\CategoryTranslation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        removeContentLocale();

        $this->authorize('admin_categories_list');

        $categories = Category::where('parent_id', null)
            ->with([
                'subCategories'
            ])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/categories.categories_list_page_title'),
            'categories' => $categories
        ];

        return view('admin.categories.lists', $data);
    }

    public function create()
    {
        $this->authorize('admin_categories_create');


        $data = [
            'pageTitle' => trans('admin/main.category_new_page_title'),
        ];

        return view('admin.categories.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_categories_create');

        $this->validate($request, [
            'title' => 'required|min:3|max:255',
            'slug' => 'nullable|max:255|unique:categories,slug',
        ]);

        $data = $request->all();

        if (!empty($data['order'])) {
            $order = $data['order'];
        } else {
            $order = Category::whereNull('parent_id')->count() + 1;
        }

        $category = Category::create([
            'slug' => $data['slug'] ?? Category::makeSlug($data['title']),
            'icon' => !empty($data['icon']) ? $data['icon'] : null,
            'cover_image' => !empty($data['cover_image']) ? $data['cover_image'] : null,
            'icon2' => !empty($data['icon2']) ? $data['icon2'] : null,
            'icon2_box_color' => !empty($data['icon2_box_color']) ? $data['icon2_box_color'] : null,
            'overlay_image' => !empty($data['overlay_image']) ? $data['overlay_image'] : null,
            'order' => $order,
            'enable' => (!empty($data['enable']) and $data['enable'] == 'on'),
        ]);

        CategoryTranslation::updateOrCreate([
            'category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'subtitle' => !empty($data['subtitle']) ? $data['subtitle'] : null,
            'bottom_seo_title' => !empty($data['bottom_seo_title']) ? $data['bottom_seo_title'] : null,
            'bottom_seo_content' => !empty($data['bottom_seo_content']) ? $data['bottom_seo_content'] : null,
        ]);

        $hasSubCategories = (!empty($request->get('has_sub')) and $request->get('has_sub') == 'on');
        $this->setSubCategory($category, $request->get('sub_categories'), $hasSubCategories, $data['locale']);

        cache()->forget(Category::$cacheKey);

        removeContentLocale();

        return redirect(getAdminPanelUrl() . '/categories');
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_categories_edit');

        $category = Category::findOrFail($id);
        $subCategories = Category::where('parent_id', $category->id)
            ->orderBy('order', 'asc')
            ->get();

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $category->getTable(), $category->id);

        $data = [
            'pageTitle' => trans('categories.edit_category'),
            'category' => $category,
            'subCategories' => $subCategories
        ];

        return view('admin.categories.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_categories_edit');

        $category = Category::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|min:3|max:255',
            'slug' => 'nullable|max:255|unique:categories,slug,' . $category->id,
        ]);

        $data = $request->all();

        $category->update([
            'icon' => !empty($data['icon']) ? $data['icon'] : null,
            'slug' => $data['slug'] ?? Category::makeSlug($data['title']),
            'order' => $data['order'] ?? $category->order,
            'cover_image' => !empty($data['cover_image']) ? $data['cover_image'] : null,
            'icon2' => !empty($data['icon2']) ? $data['icon2'] : null,
            'icon2_box_color' => !empty($data['icon2_box_color']) ? $data['icon2_box_color'] : null,
            'overlay_image' => !empty($data['overlay_image']) ? $data['overlay_image'] : null,
            'enable' => (!empty($data['enable']) and $data['enable'] == 'on'),
        ]);

        CategoryTranslation::updateOrCreate([
            'category_id' => $category->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'subtitle' => !empty($data['subtitle']) ? $data['subtitle'] : null,
            'bottom_seo_title' => !empty($data['bottom_seo_title']) ? $data['bottom_seo_title'] : null,
            'bottom_seo_content' => !empty($data['bottom_seo_content']) ? $data['bottom_seo_content'] : null,
        ]);

        $hasSubCategories = (!empty($request->get('has_sub')) and $request->get('has_sub') == 'on');
        $this->setSubCategory($category, $request->get('sub_categories'), $hasSubCategories, $data['locale']);


        cache()->forget(Category::$cacheKey);

        removeContentLocale();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.category_updated_successful'),
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('admin_categories_delete');

        $category = Category::where('id', $id)->first();
        $parent = !empty($category->parent_id) ? $category->parent_id : null;

        if (!empty($category)) {
            Category::where('parent_id', $category->id)
                ->delete();

            $category->delete();
        }

        cache()->forget(Category::$cacheKey);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => !empty($parent) ? trans('update.sub_category_successfully_deleted') : trans('update.category_successfully_deleted'),
            'status' => 'success'
        ];

        return !empty($parent) ? back()->with(['toast' => $toastData]) : redirect(getAdminPanelUrl() . '/categories')->with(['toast' => $toastData]);
    }

    public function search(Request $request)
    {
        $term = $request->get('term');

        $option = $request->get('option', null);

        $query = Category::select('id')
            ->whereTranslationLike('title', "%$term%");

        /*if (!empty($option)) {

        }*/

        $categories = $query->get();

        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'title' => $category->title,
            ];
        }

        return response()->json($result, 200);
    }

    public function setSubCategory(Category $category, $subCategories, $hasSubCategories, $locale)
    {
        $order = 1;
        $oldIds = [];

        if ($hasSubCategories and !empty($subCategories) and count($subCategories)) {
            foreach ($subCategories as $key => $subCategory) {
                $check = Category::where('id', $key)->first();

                if (is_numeric($key)) {
                    $oldIds[] = $key;
                }

                if (!empty($subCategory['title'])) {
                    $checkSlug = 0;
                    if (!empty($subCategory['slug'])) {
                        $checkSlug = Category::query()->where('slug', $subCategory['slug'])->count();
                    }

                    $slug = (!empty($subCategory['slug']) and ($checkSlug == 0 or ($checkSlug == 1 and $check->slug == $subCategory['slug']))) ? $subCategory['slug'] : Category::makeSlug($subCategory['title']);

                    if (!empty($check)) {
                        $check->update([
                            'slug' => $slug,
                            'order' => $order,
                            'icon' => $subCategory['icon'] ?? null,
                            'cover_image' => $subCategory['cover_image'] ?? null,
                            'icon2' => !empty($subCategory['icon2']) ? $subCategory['icon2'] : null,
                            'icon2_box_color' => !empty($subCategory['icon2_box_color']) ? $subCategory['icon2_box_color'] : null,
                            'overlay_image' => !empty($subCategory['overlay_image']) ? $subCategory['overlay_image'] : null,
                            'enable' => (!empty($subCategory['enable']) and $subCategory['enable'] == 'on'),
                        ]);

                        CategoryTranslation::updateOrCreate([
                            'category_id' => $check->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $subCategory['title'],
                            'subtitle' => !empty($subCategory['subtitle']) ? $subCategory['subtitle'] : null,
                            'bottom_seo_title' => !empty($subCategory['bottom_seo_title']) ? $subCategory['bottom_seo_title'] : null,
                            'bottom_seo_content' => !empty($subCategory['bottom_seo_content']) ? $subCategory['bottom_seo_content'] : null,
                        ]);
                    } else {

                        $new = Category::create([
                            'parent_id' => $category->id,
                            'slug' => $slug,
                            'order' => $order,
                            'icon' => $subCategory['icon'] ?? null,
                            'cover_image' => $subCategory['cover_image'] ?? null,
                            'icon2' => !empty($subCategory['icon2']) ? $subCategory['icon2'] : null,
                            'icon2_box_color' => !empty($subCategory['icon2_box_color']) ? $subCategory['icon2_box_color'] : null,
                            'overlay_image' => !empty($subCategory['overlay_image']) ? $subCategory['overlay_image'] : null,
                            'enable' => (!empty($subCategory['enable']) and $subCategory['enable'] == 'on'),
                        ]);

                        CategoryTranslation::updateOrCreate([
                            'category_id' => $new->id,
                            'locale' => mb_strtolower($locale),
                        ], [
                            'title' => $subCategory['title'],
                            'subtitle' => !empty($subCategory['subtitle']) ? $subCategory['subtitle'] : null,
                            'bottom_seo_title' => !empty($subCategory['bottom_seo_title']) ? $subCategory['bottom_seo_title'] : null,
                            'bottom_seo_content' => !empty($subCategory['bottom_seo_content']) ? $subCategory['bottom_seo_content'] : null,
                        ]);

                        $oldIds[] = $new->id;
                    }

                    $order += 1;
                }
            }
        }

        Category::where('parent_id', $category->id)
            ->whereNotIn('id', $oldIds)
            ->delete();

        return true;
    }
}
