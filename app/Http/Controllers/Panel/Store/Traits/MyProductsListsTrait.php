<?php

namespace App\Http\Controllers\Panel\Store\Traits;

use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait MyProductsListsTrait
{
    private function handlePageTopStats($user): array
    {
        $query = Product::query()->where('creator_id', $user->id);

        $totalPhysicalProducts = deepClone($query)->where('type', Product::$physical)->count();

        $totalVirtualProducts = deepClone($query)->where('type', Product::$virtual)->count();

        $physicalSales = deepClone($query)->where('products.type', Product::$physical)
            ->join('product_orders', 'products.id', 'product_orders.product_id')
            ->leftJoin('sales', function ($join) {
                $join->on('product_orders.id', '=', 'sales.product_order_id')
                    ->whereNull('sales.refund_at');
            })
            ->select(DB::raw('sum(sales.total_amount) as total_sales'))
            ->whereNotNull('product_orders.sale_id')
            ->whereNotIn('product_orders.status', [ProductOrder::$canceled, ProductOrder::$pending])
            ->first();

        $virtualSales = deepClone($query)->where('products.type', Product::$virtual)
            ->join('product_orders', 'products.id', 'product_orders.product_id')
            ->leftJoin('sales', function ($join) {
                $join->on('product_orders.id', '=', 'sales.product_order_id')
                    ->whereNull('sales.refund_at');
            })
            ->select(DB::raw('sum(sales.total_amount) as total_sales'))
            ->whereNotNull('product_orders.sale_id')
            ->whereNotIn('product_orders.status', [ProductOrder::$canceled, ProductOrder::$pending])
            ->first();

        return [
            'totalPhysicalProducts' => $totalPhysicalProducts,
            'totalVirtualProducts' => $totalVirtualProducts,
            'totalPhysicalSales' => !empty($physicalSales) ? $physicalSales->total_sales : 0,
            'totalVirtualSales' => !empty($virtualSales) ? $virtualSales->total_sales : 0,
        ];
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {

        return $query;
    }

    private function getPageListData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = 8; // $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $products = $query
            ->with([
                'productOrders'
            ])
            ->withCount([
                'visits'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($products as $product) {
            $lastPurchase = $product->sales(true, true)->first();

            $product->last_purchase_date = !empty($lastPurchase) ? $lastPurchase->created_at : null;
        }

        if ($request->ajax()) {
            return $this->handleAjaxResponse($request, $products, $total, $count);
        }

        return [
            'products' => $products,
            'pagination' => $this->makePagination($request, $products, $total, $count, true),
        ];
    }

    private function handleAjaxResponse(Request $request, $products, $total, $count)
    {
        $html = "";

        foreach ($products as $productItem) {
            $html .= '<div class="col-12 col-lg-6 mb-32">';
            $html .= (string)view()->make("design_1.panel.store.my_products.product_card.index", ['product' => $productItem]);
            $html .= '</div>';
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $products, $total, $count, true)
        ]);
    }
}
