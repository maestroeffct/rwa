<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subscribe;
use App\Models\SubscribeSpecificationItem;
use App\Models\Translation\SubscribeTranslation;
use Illuminate\Http\Request;

class SubscribesController extends Controller
{
    public function index()
    {
        $this->authorize('admin_subscribe_list');

        removeContentLocale();

        $subscribes = Subscribe::with([
            'sales' => function ($query) {
                $query->whereNull('refund_at');
            }
        ])->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/financial.subscribes'),
            'subscribes' => $subscribes
        ];

        return view('admin.financial.subscribes.lists', $data);
    }

    public function create()
    {
        $this->authorize('admin_subscribe_create');

        removeContentLocale();

        $categories = Category::getCategories();

        $data = [
            'pageTitle' => trans('admin/pages/financial.new_subscribe'),
            'categories' => $categories
        ];

        return view('admin.financial.subscribes.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_subscribe_create');

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'usable_count' => 'required|numeric',
            'days' => 'required|numeric',
            'price' => 'required|numeric',
            'icon' => 'required|string',
        ]);

        $storeData = $this->makeStoreData($request);
        $subscribe = Subscribe::create($storeData);

        /* Extra Data */
        $this->handleStoreExtraData($request, $subscribe);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.subscribe_created_successfully'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/financial/subscribes/{$subscribe->id}/edit"))->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_subscribe_edit');

        $subscribe = Subscribe::findOrFail($id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $subscribe->getTable(), $subscribe->id);

        $categories = Category::getCategories();

        $data = [
            'pageTitle' => trans('admin/pages/financial.edit_subscribe'),
            'subscribe' => $subscribe,
            'categories' => $categories
        ];

        return view('admin.financial.subscribes.create.index', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_subscribe_edit');

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'usable_count' => 'required|numeric',
            'days' => 'required|numeric',
            'price' => 'required|numeric',
            'icon' => 'required|string',
        ]);

        $subscribe = Subscribe::findOrFail($id);

        $storeData = $this->makeStoreData($request, $subscribe);
        $subscribe->update($storeData);

        /* Extra Data */
        $this->handleStoreExtraData($request, $subscribe);

        removeContentLocale();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.subscribe_updated_successfully'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/financial/subscribes/{$subscribe->id}/edit"))->with(['toast' => $toastData]);
    }

    public function delete($id)
    {
        $this->authorize('admin_subscribe_delete');

        $subscribe = Subscribe::findOrFail($id);

        $subscribe->delete();

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.subscribe_deleted_successfully'),
            'status' => 'success'
        ];
        return redirect(getAdminPanelUrl("/financial/subscribes"))->with(['toast' => $toastData]);
    }

    private function makeStoreData(Request $request, $subscribe = null): array
    {
        $data = $request->all();

        return [
            'target_type' => $data['target_type'] ?? 'all',
            'target' => $data['target'] ?? null,
            'usable_count' => $data['usable_count'],
            'days' => $data['days'],
            'price' => $data['price'],
            'icon' => $data['icon'],
            'is_popular' => (!empty($data['is_popular']) and $data['is_popular'] == '1'),
            'infinite_use' => (!empty($data['infinite_use']) and $data['infinite_use'] == '1'),
            'created_at' => !empty($subscribe) ? $subscribe->created_at : time(),
        ];
    }

    private function handleStoreExtraData(Request $request, $subscribe)
    {
        $data = $request->all();

        SubscribeTranslation::updateOrCreate([
            'subscribe_id' => $subscribe->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'subtitle' => !empty($data['subtitle']) ? $data['subtitle'] : null,
            'description' => !empty($data['description']) ? $data['description'] : null,
        ]);


        // Handle Target Products
        SubscribeSpecificationItem::query()->where('subscribe_id', $subscribe->id)->delete();

        $specificationItems = [
            'category_ids' => 'category_id',
            'instructor_ids' => 'instructor_id',
            'courses_ids' => 'course_id',
            'bundle_ids' => 'bundle_id',
        ];

        foreach ($specificationItems as $key => $column) {
            if (!empty($data[$key]) and $this->checkStoreSpecificationItems($key, $subscribe->target, $subscribe->target_type)) {
                $insert = [];

                foreach ($data[$key] as $item) {
                    $insert[] = [
                        'subscribe_id' => $subscribe->id,
                        $column => $item,
                    ];
                }

                if (!empty($insert)) {
                    SubscribeSpecificationItem::query()->insert($insert);
                }
            }
        }
    }

    private function checkStoreSpecificationItems($item, $target, $type)
    {
        $store = false;

        $items = [
            'category_ids' => 'specific_categories',
            'instructor_ids' => 'specific_instructors',
            'courses_ids' => 'specific_courses',
            'bundle_ids' => 'specific_bundles',
        ];

        if ($items[$item] == $target) {
            $store = true;
        }

        return $store;
    }

}
