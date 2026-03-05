<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Models\Api\Blog;
use App\Models\Api\Bundle;
use App\Models\Api\ContentDeleteRequest;
use App\Models\Api\Product;
use App\Models\Api\Webinar;
use Illuminate\Http\Request;

class ContentDeleteRequestController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'item_id' => 'required',
            'item_type' => 'required|in:course,bundle,product,post',
            'description' => 'required|string|min:3',
        ];

        validateParam($data, $rules);

        $itemId = $data['item_id'];
        $itemType = $data['item_type'];

        $itemRow = $this->getItem($itemId, $itemType);

        if (!empty($itemRow)) {
            $user = apiAuth();
            $targetableType = $this->getTargetableType($itemType);

            $sales = null;
            $customersCount = null;

            if ($itemType == "course" or $itemType == "bundle") {
                $sales = $itemRow->sales()->whereNull('refund_at')->sum('total_amount');
                $customersCount = $itemRow->sales()->whereNull('refund_at')->count();
            } elseif ($itemType == "product") {
                $sales = $itemRow->sales()->sum('total_amount');
                $customersCount = $itemRow->salesCount();
            }

            ContentDeleteRequest::query()->updateOrCreate([
                'user_id' => $user->id,
                'targetable_id' => $itemId,
                'targetable_type' => $targetableType,
            ], [
                'content_title' => $itemRow->title,
                'content_published_date' => !empty($itemRow->created_at) ? $itemRow->created_at : null,
                'customers_count' => $customersCount,
                'sales' => $sales,
                'description' => $data['description'],
                'status' => 'pending',
                'created_at' => time(),
            ]);

            return apiResponse2(1, 'done', trans('update.delete_request_saved_successfully_wait_for_admin_approval'));
        }

        return apiResponse2(0, 'forbidden', trans('api.not_access_to_this_item'));
    }


    private function getItem($itemId, $itemType)
    {
        $itemRow = null;
        $user = apiAuth();

        if ($itemType == "course") {
            $itemRow = Webinar::where('id', $itemId)
                ->where('creator_id', $user->id)
                ->first();
        } elseif ($itemType == "bundle") {
            $itemRow = Bundle::where('id', $itemId)
                ->where('creator_id', $user->id)
                ->first();
        } elseif ($itemType == "product") {
            $itemRow = Product::where('id', $itemId)
                ->where('creator_id', $user->id)
                ->first();
        } elseif ($itemType == "post") {
            $itemRow = Blog::where('id', $itemId)
                ->where('author_id', $user->id)
                ->first();
        }

        return $itemRow;
    }

    private function getTargetableType($itemType)
    {
        $type = null;

        if ($itemType == "course") {
            $type = "App\Models\Webinar";
        } elseif ($itemType == "bundle") {
            $type = "App\Models\Bundle";
        } elseif ($itemType == "product") {
            $type = "App\Models\Product";
        } elseif ($itemType == "post") {
            $type = "App\Models\Blog";
        }

        return $type;
    }
}
