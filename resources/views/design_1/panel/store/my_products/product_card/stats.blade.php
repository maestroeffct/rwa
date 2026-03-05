<div class="d-grid grid-columns-2 grid-lg-columns-3 gap-24 mt-16">
    {{-- Customers --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-profile class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ $product->salesCount() }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.customers') }}</span>
        </div>
    </div>
    {{-- Sales --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-money-3 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ handlePrice($product->sales()->sum('total_amount')) }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('panel.sales') }}</span>
        </div>
    </div>
    {{-- Availability --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-box-1 class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">
                @if($product->unlimited_inventory)
                    {{ trans('update.unlimited') }}
                @else
                    {{ $product->getAvailability() }}
                @endif
            </span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.availability') }}</span>
        </div>
    </div>
    {{-- Views --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-eye class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ $product->visits_count }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.views') }}</span>
        </div>
    </div>
    {{-- Waiting Orders --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-bag class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ $product->productOrders->whereIn('status',[\App\Models\ProductOrder::$waitingDelivery, \App\Models\ProductOrder::$shipped])->count() }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.waiting_orders') }}</span>
        </div>
    </div>
    {{-- Last Purchase --}}
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-white rounded-circle">
            <x-iconsax-lin-bag-timer class="icons text-gray-400" width="20px" height="20px"/>
        </div>
        <div class="ml-8 font-12">
            <span class="d-block font-weight-bold text-dark">{{ !empty($product->last_purchase_date) ? dateTimeFormat($product->last_purchase_date, 'j M Y') : '-' }}</span>
            <span class="d-block mt-2 text-gray-500">{{ trans('update.last_purchase') }}</span>
        </div>
    </div>
</div>
