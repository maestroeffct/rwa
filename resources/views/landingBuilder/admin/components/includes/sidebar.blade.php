<div id="landingBuilderSidebar" class="landing-builder-sidebar edit-component-sidebar bg-white pb-80"  @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>

    <div class="d-flex-center flex-column mt-36">
        <div class="d-flex-center size-64 rounded-circle bg-gray-100">
            <div class="d-flex-center size-56 bg-white rounded-circle">
                <x-iconsax-bul-copy class="icons text-primary" width="24px" height="24px"/>
            </div>
        </div>

        <h4 class="font-14 font-weight-bold text-dark mt-8">{{ trans('update.landing_info') }}</h4>

        <div class="d-flex align-items-center justify-content-around mt-12 rounded-10 bg-gray-100 py-12 px-16">
            <div class="d-flex flex-column align-items-center flex-1">
                <span class="font-12 font-weight-bold">12K</span>
                <span class="font-12 text-gray-500">{{ trans('update.views') }}</span>
            </div>

            <div class="landing-builder-sidebar__stat-divider mx-16"></div>

            <div class="d-flex flex-column align-items-center flex-1">
                <span class="font-12 font-weight-bold">{{ count($landingItem->components) }}</span>
                <span class="font-12 text-gray-500">{{ trans('update.components') }}</span>
            </div>
        </div>
    </div>


    <div class="my-16 p-12 border-top-gray-100 border-bottom-gray-100">
        <div class="bg-gray-200 rounded-12 p-4">
            <div class="bg-white p-12 rounded-8">
                <h5 class="font-14 text-dark">{{ $landingItem->title }}</h5>
                <p class="font-12 text-gray-500 mt-4">{{ trans('update.you_can_manage_this_landing_page_components_from_this_section') }}</p>
            </div>

            <a href="{{ getLandingBuilderUrl("/{$landingItem->id}/edit") }}" class="">
                <div class="d-flex align-items-center justify-content-between px-4 py-8 opacity-75">
                    <span class="font-12 text-gray-500">{{ trans('update.back_to_landing') }}</span>

                    <x-iconsax-lin-arrow-left class="icons text-gray-500" width="16px" height="16px"/>
                </div>
            </a>
        </div>
    </div>

    <div class="px-12">
        {{-- all assigned components --}}
        @foreach($landingItem->components as $landingItemComponent)
            <a href="{{ getLandingBuilderUrl("/{$landingItem->id}/components/{$landingItemComponent->id}/edit") }}" class="edit-component-sidebar__component-card {{ ($landingItemComponent->id == $landingComponent->id) ? 'active' : '' }} position-relative d-flex align-items-center bg-gray-100 p-16 rounded-12 mt-12 ">
                <div class="">
                    <h6 class="font-12">{{ truncate(trans("update.{$landingItemComponent->landingBuilderComponent->name}_component_title"), 26) }}</h6>
                    <p class="font-12 mt-8">{{ truncate(trans("update.{$landingItemComponent->landingBuilderComponent->name}_component_hint"), 30) }}</p>
                </div>

                <div class="icons-box">
                    <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                </div>
            </a>
        @endforeach

    </div>
</div>
