<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RelatedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelatedProductsController extends Controller
{

    public function getForm(Request $request, $id = null)
    {
        $relatedProduct = null;

        if (!empty($id)) {
            $relatedProduct = RelatedProduct::query()->findOrFail($id);
        }

        $data = [
            'itemId' => $request->get('item'),
            'itemType' => $request->get('item_type'),
            'relatedProduct' => $relatedProduct,
        ];

        $html = (string)view()->make("admin.store.products.create.relatedProducts.related_product_modal", $data);

        return response()->json([
            'code' => 200,
            'html' => $html
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'item_type' => 'required|in:webinar,bundle,product,upcomingProduct',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $type = $this->getTargetType($data);

        RelatedProduct::query()->updateOrCreate([
            'targetable_id' => $data['item_id'],
            'targetable_type' => $type,
            'product_id' => $data['product_id']
        ], [
            'order' => null,
        ]);

        return response()->json([
            'code' => 200,
            'title' => trans('update.saved_successfully')
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'item_type' => 'required|in:webinar,bundle,product,upcomingProduct',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $type = $this->getTargetType($data);

        $item = RelatedProduct::query()
            ->where('targetable_id', $data['item_id'])
            ->where('targetable_type', $type)
            ->where('id', $id)
            ->first();

        if (!empty($item)) {
            $item->update([
                'product_id' => $data['product_id']
            ]);
        }

        return response()->json([
            'code' => 200,
            'title' => trans('update.saved_successfully')
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $item = RelatedProduct::query()
            ->where('id', $id)
            ->first();

        if (!empty($item)) {
            $item->delete();
        }

        return redirect()->back();
    }

    private function getTargetType($data)
    {
        $type = null;

        switch ($data['item_type']) {
            case 'webinar':
                $type = "App\Models\Webinar";
                break;

            case 'bundle':
                $type = "App\Models\Bundle";
                break;

            case 'product':
                $type = "App\Models\Product";
                break;

            case 'upcomingProduct':
                $type = "App\Models\UpcomingProduct";
                break;
        }

        return $type;
    }

}
