<div id="landingBuilderSidebar" class="landing-builder-sidebar bg-white pt-0" @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>

    <div class="js-show-landing-builder-sidebar cursor-pointer d-flex d-lg-none">
        <x-iconsax-lin-add class="icons text-dark close-icon" width="24px" height="24px"/>
    </div>

    <div class="d-flex-center flex-column mt-36">
        <div class="d-flex-center size-64 rounded-circle bg-gray-100">
            <div class="d-flex-center size-56 bg-white rounded-circle">
                <x-iconsax-bul-copy class="icons text-primary" width="24px" height="24px"/>
            </div>
        </div>

        <h4 class="font-14 font-weight-bold text-dark mt-8">{{ trans('update.landing_builder') }}</h4>

        <div class="d-flex align-items-center justify-content-around mt-12 rounded-10 bg-gray-100 py-12 px-16">
            <div class="d-flex flex-column align-items-center flex-1">
                <span class="font-12 font-weight-bold">12</span>
                <span class="font-12 text-gray-500">{{ trans('update.landings') }}</span>
            </div>

            <div class="landing-builder-sidebar__stat-divider mx-16"></div>

            <div class="d-flex flex-column align-items-center flex-1">
                <span class="font-12 font-weight-bold">12</span>
                <span class="font-12 text-gray-500">{{ trans('update.components') }}</span>
            </div>
        </div>
    </div>

    {{-- New Landing --}}
    @can('admin_landing_builder_create')
        <div class="my-16 p-12 border-top-gray-100 border-bottom-gray-200">
            <a href="{{ getLandingBuilderUrl("/create") }}" class="">
                <div class="bg-primary rounded-12 p-4">
                    <div class="bg-white p-12 rounded-8">
                        <h5 class="font-14 text-dark">{{ trans('update.new_landing') }}</h5>
                        <p class="font-12 text-gray-500 mt-4">{{ trans('update.create_professional_landing_pages_with_different_components') }}</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-between px-4 py-8 opacity-75">
                        <span class="font-12 text-white">{{ trans('update.assign_components') }}</span>

                        <x-iconsax-lin-arrow-right class="icons text-white" width="16px" height="16px"/>
                    </div>
                </div>
            </a>
        </div>
    @endcan

    {{-- all landings --}}
    <div class="px-12">
        @if(!empty($landingItems) and count($landingItems))
            @foreach($landingItems as $landingItemRow)
                <a href="{{ getLandingBuilderUrl("/{$landingItemRow->id}/edit") }}" class="edit-component-sidebar__component-card {{ (!empty($landingItem) and $landingItemRow->id == $landingItem->id) ? 'active' : '' }} position-relative d-flex align-items-center bg-gray-100 p-16 rounded-12 mt-12 ">
                    <div class="">
                        <h6 class="font-12">{{ truncate($landingItemRow->title, 26) }}</h6>
                        <p class="font-12 mt-8">{{ $landingItemRow->components_count }} {{ trans('update.components') }}</p>
                    </div>

                    <div class="icons-box">
                        <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                    </div>
                </a>
            @endforeach
        @endif
    </div>

</div>
