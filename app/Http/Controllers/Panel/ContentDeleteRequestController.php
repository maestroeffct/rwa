<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\ContentDeleteRequest;
use App\Models\Event;
use App\Models\Product;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentDeleteRequestController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'item_id' => 'required',
            'item_type' => 'required|in:course,bundle,product,post,event',
            'description' => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $itemId = $data['item_id'];
        $itemType = $data['item_type'];

        $itemRow = $this->getItem($itemId, $itemType);

        if (!empty($itemRow)) {
            $targetableType = $this->getTargetableType($itemType);

            $sales = null;
            $customersCount = null;

            if ($itemType == "course" or $itemType == "bundle" or $itemType == "event") {
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


            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.delete_request_saved_successfully_wait_for_admin_approval'),
            ]);
        }

        return response()->json([], 422);
    }

    private function getItem($itemId, $itemType)
    {
        $itemRow = null;
        $user = auth()->user();

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
        } elseif ($itemType == "event") {
            $itemRow = Event::where('id', $itemId)
                ->where('creator_id', $user->id)
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
        } elseif ($itemType == "event") {
            $type = "App\Models\Event";
        }

        return $type;
    }

}
