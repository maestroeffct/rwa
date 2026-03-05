<div class="panel-product-card-1__badges-lists d-flex flex-wrap align-items-center gap-8">
    @if($product->ordering and !empty($product->inventory) and $product->getAvailability() < 1)
        <div class="badge bg-danger">
            <x-iconsax-bul-more-circle class="icons text-white" width="20px" height="20px"/>
            <span class="">{{ trans('update.out_of_stock') }}</span>
        </div>
    @elseif(!$product->ordering and $product->getActiveDiscount())
        <div class="badge bg-info">
            <x-iconsax-bul-more-circle class="icons text-white" width="20px" height="20px"/>
            <span class="">{{ trans('update.ordering_off') }}</span>
        </div>
    @elseif($hasDiscount)
        <div class="badge bg-danger">
            <x-iconsax-bul-more-circle class="icons text-white" width="20px" height="20px"/>
            <span class="">{{ trans('public.offer',['off' => $hasDiscount->percent]) }}</span>
        </div>
    @else
        @switch($product->status)
            @case(\App\Models\Product::$active)
                <div class="badge bg-primary">
                    <x-iconsax-bul-tick-circle class="icons text-white" width="20px" height="20px"/>
                    <span class="">{{ trans('public.active') }}</span>
                </div>
                @break
            @case(\App\Models\Product::$draft)
                <div class="badge bg-danger">
                    <x-iconsax-bul-note-2 class="icons text-white" width="20px" height="20px"/>
                    <span class="">{{ trans('public.draft') }}</span>
                </div>
                @break
            @case(\App\Models\Product::$pending)
                <div class="badge bg-warning">
                    <x-iconsax-bul-more-circle class="icons text-white" width="20px" height="20px"/>
                    <span class="">{{ trans('public.waiting') }}</span>
                </div>
                @break
            @case(\App\Models\Product::$inactive)
                <div class="badge bg-danger">
                    <x-iconsax-bul-more-circle class="icons text-white" width="20px" height="20px"/>
                    <span class="">{{ trans('public.rejected') }}</span>
                </div>
                @break
        @endswitch
    @endif
</div>
