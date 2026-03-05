<?php

namespace App\Http\Controllers\Panel\Store;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use App\Models\ProductOrder;
use App\Models\Sale;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_products_purchases");

        $user = auth()->user();

        $giftsIds = Gift::query()
            ->where(function ($query) use ($user) {
                $query->where('email', $user->email);
                $query->orWhere('user_id', $user->id);
            })
            ->where('status', 'active')
            ->whereNotNull('product_id')
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->pluck('id')
            ->toArray();

        $query = ProductOrder::query()
            ->where(function ($query) use ($user, $giftsIds) {
                $query->where('product_orders.buyer_id', $user->id);
                $query->orWhereIn('product_orders.gift_id', $giftsIds);
            })
            ->where('product_orders.status', '!=', 'pending')
            ->whereHas('sale', function ($query) {
                $query->whereIn('type', ['product', 'gift']);
                $query->where('access_to_purchased_item', true);
                $query->whereNull('refund_at');
            });

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }

        $totalOrders = deepClone($copyQuery)->count();
        $pendingOrders = deepClone($copyQuery)->where(function ($query) {
            $query->where('status', ProductOrder::$waitingDelivery)
                ->orWhere('status', ProductOrder::$shipped);
        })->count();

        $canceledOrders = deepClone($copyQuery)->where('status', ProductOrder::$canceled)->count();

        $totalPurchase = deepClone($copyQuery)
            ->join('sales', 'sales.product_order_id', 'product_orders.id')
            ->select(DB::raw("sum(total_amount) as totalAmount"))
            ->first();

        $sellerIds = deepClone($copyQuery)->pluck('seller_id')->toArray();
        $sellers = User::select('id', 'full_name')
            ->whereIn('id', array_unique($sellerIds))
            ->get();


        $data = [
            'pageTitle' => trans('update.product_purchases_lists_page_title'),
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'canceledOrders' => $canceledOrders,
            'totalPurchase' => $totalPurchase ? $totalPurchase->totalAmount : 0,
            'sellers' => $sellers,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.store.my_purchases.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $seller_id = $request->get('seller_id');
        $type = $request->get('type');
        $status = $request->get('status');
        $order_id = $request->get('order_id');
        $sort = $request->get('sort');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($seller_id) and $seller_id != 'all') {
            $query->where('seller_id', $seller_id);
        }

        if (isset($type) and $type !== 'all') {
            $query->whereHas('product', function ($query) use ($type) {
                $query->where('type', $type);
            });
        }

        if (isset($status) and $status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($order_id)) {
            $query->where('id', $order_id);
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'price_asc':
                    $query->join('sales', 'sales.id', '=', 'product_orders.sale_id')
                        ->select('product_orders.*', 'sales.amount')
                        ->orderBy('sales.amount', 'asc');
                    break;
                case 'price_desc':
                    $query->join('sales', 'sales.id', '=', 'product_orders.sale_id')
                        ->select('product_orders.*', 'sales.amount')
                        ->orderBy('sales.amount', 'desc');
                    break;
                case 'quantity_asc':
                    $query->orderBy('quantity', 'asc');
                    break;
                case 'quantity_desc':
                    $query->orderBy('quantity', 'desc');
                    break;
                case 'create_date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'create_date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getListsData(Request $request, Builder $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $orders = $query
            ->with([
                'product',
                'sale',
                'seller' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'mobile', 'email');
                }
            ])
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $orders, $total, $count);
        }

        return [
            'orders' => $orders,
            'pagination' => $this->makePagination($request, $orders, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $orders, $total, $count)
    {
        $html = "";

        foreach ($orders as $orderRow) {
            $html .= (string)view()->make('design_1.panel.store.my_purchases.table_items', ['order' => $orderRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $orders, $total, $count, true)
        ]);
    }


    public function getProductOrder($saleId, $orderId)
    {
        $user = auth()->user();

        $order = ProductOrder::where('buyer_id', $user->id)
            ->where('id', $orderId)
            ->where('sale_id', $saleId)
            ->first();

        if (!empty($order)) {
            $shippingTrackingUrl = getStoreSettings('shipping_tracking_url');

            $order->address = $order->buyer->getAddress(true);

            return response()->json([
                'order' => $order,
                'shipping_tracking_url' => $shippingTrackingUrl
            ]);
        }

        abort(403);
    }

    public function setGotTheParcel($saleId, $orderId)
    {
        $user = auth()->user();

        $order = ProductOrder::where('buyer_id', $user->id)
            ->where('id', $orderId)
            ->where('sale_id', $saleId)
            ->first();

        if (!empty($order)) {
            $order->update([
                'status' => ProductOrder::$success
            ]);

            $product = $order->product;
            $buyer = $order->buyer;

            $notifyOptions = [
                '[p.title]' => $product->title,
                '[u.name]' => $buyer->full_name
            ];
            sendNotification('product_receive_shipment', $notifyOptions, $order->seller_id);

            return response()->json([
                'code' => 200
            ]);
        }

        return response()->json([
            'code' => 422
        ]);
    }

    public function invoice($saleId, $orderId)
    {
        $user = auth()->user();

        $giftsIds = Gift::query()
            ->where(function ($query) use ($user) {
                $query->where('email', $user->email);
                $query->orWhere('user_id', $user->id);
            })
            ->where('status', 'active')
            ->whereNotNull('product_id')
            ->where(function ($query) {
                $query->whereNull('date');
                $query->orWhere('date', '<', time());
            })
            ->whereHas('sale')
            ->pluck('id')
            ->toArray();


        $productOrder = ProductOrder::query()
            ->where(function ($query) use ($user, $giftsIds) {
                $query->where('buyer_id', $user->id);
                $query->orWhereIn('gift_id', $giftsIds);
            })
            ->where('id', $orderId)
            ->where('sale_id', $saleId)
            ->first();

        if (!empty($productOrder)) {

            if (!empty($productOrder->gift_id)) {
                $gift = $productOrder->gift;

                $productOrder->buyer = $gift->user;
            }

            $data = [
                'pageTitle' => trans('webinars.invoice_page_title'),
                'order' => $productOrder,
                'product' => $productOrder->product,
                'sale' => $productOrder->sale,
                'seller' => $productOrder->seller,
                'buyer' => $productOrder->buyer,
            ];

            return view('design_1.panel.store.invoice.index', $data);
        }

        abort(404);
    }
}
