@if(!empty($products) and $products->isNotEmpty())
    <div id="profileProductsRow" class="row">
        @include('design_1.web.products.components.cards.grids.index',['products' => $products, 'gridCardClassName' => "col-12 col-lg-6 mt-16"])
    </div>

    @if(!empty($hasMoreProducts))
        <div class="d-flex-center mt-16">
            <div class="js-profile-tab-load-more-btn btn border-dashed border-gray-300 rounded-12 bg-white bg-hover-gray-100 cursor-pointer" data-path="/users/{{ $user->getUsername() }}/get-products" data-el="profileProductsRow">
                <x-iconsax-lin-rotate-left class="icons text-gray-500" width="16px" height="16px"/>
                <span class="ml-4 text-gray-500">{{ trans('update.load_more') }}</span>
            </div>
        </div>
    @endif
@else
    @include('design_1.panel.includes.no-result',[
        'file_name' => 'profile_products.svg',
        'title' => trans('update.user_profile_not_have_products'),
        'hint' => trans('update.user_profile_not_have_products_hint'),
        'extraClass' => 'mt-0',
    ])
@endif

