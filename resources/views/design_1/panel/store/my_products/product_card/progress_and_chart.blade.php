@if(!empty($product->inventory_warning) and $product->getAvailability() <= $product->inventory_warning)
    <div class="d-flex align-items-center">
        <div class="d-flex-center">
            <x-iconsax-bol-danger class="icons text-warning " width="24px" height="24px"/>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.inventory_warning') }}</h5>
            <p class="font-12 text-gray-500">{{ trans('update.the_inventory_is_below_normal') }}</p>
        </div>
    </div>
@endif
