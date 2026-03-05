<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RelatedProduct;
use Illuminate\Http\Request;
use Validator;

class RelatedProductsController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['new'];

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'item_type' => 'required|in:webinar,bundle,product,upcomingCourse',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $type = $this->getTargetType($data);

        if ($this->checkHasAccessToItem($data['item_id'], $type)) {
            RelatedProduct::query()->updateOrCreate([
                'targetable_id' => $data['item_id'],
                'targetable_type' => $type,
                'product_id' => $data['product_id']
            ], [
                'order' => null,
            ]);

            return response()->json([
                'code' => 200,
            ], 200);
        }

        return response()->json([], 422);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'item_type' => 'required|in:webinar,bundle,product,upcomingCourse',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $type = $this->getTargetType($data);

        if ($this->checkHasAccessToItem($data['item_id'], $type)) {
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
            ], 200);
        }

        return response()->json([], 422);
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();

        $item = RelatedProduct::query()
            ->where('id', $id)
            ->first();

        if ($this->checkHasAccessToItem($item->targetable_id, $item->targetable_type)) {
            if (!empty($item)) {
                $item->delete();
            }

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
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

            case 'upcomingCourse':
                $type = "App\Models\UpcomingCourse";
                break;
        }

        return $type;
    }

    private function checkHasAccessToItem($itemId, $itemType)
    {
        $access = false;
        $user = auth()->user();

        if ($itemType == 'App\Models\Product') {
            $product = Product::query()->where('id', $itemId)
                ->where('creator_id', $user->id)
                ->first();

            if (!empty($product)) {
                $access = true;
            }
        }

        return $access;
    }

}
