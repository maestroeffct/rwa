@if(!empty($landingItem))
    <div class="row">
        {{-- All Generated Components --}}
        <div class="col-12 col-lg-6 mt-16">
            <div class="bg-gray-100 p-16 rounded-16">
                <div class="">
                    <h3 class="font-14 ">{{ trans('update.general_components') }}</h3>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.drag_and_add_general_components_to_add_them_to_the_landing_page') }}</p>
                </div>

                <div class="form-group mb-0 mt-24">
                    <label class="form-group-label bg-gray-100">{{ trans('update.filter_components') }}</label>
                    <select name="filter" class="js-filter-landing-builder-components form-control bg-gray-100">
                        <option value="">{{ trans('public.all') }}</option>

                        @foreach(\App\Enums\LandingBuilderComponentCategories::getAll() as $landingBuilderCategory)
                            <option value="{{ $landingBuilderCategory }}">{{ trans("update.{$landingBuilderCategory}") }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Lists --}}
                <div class="js-general-components-lists">
                    @foreach($landingBuilderComponents as $landingBuilderComponent)
                        @include('landingBuilder.admin.pages.create.tabs.includes.component_card')
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Assigned Components --}}
        <div class="col-12 col-lg-6 mt-16">
            <div class="bg-gray-100 p-16 rounded-16">
                <div class="">
                    <h3 class="font-14 ">{{ trans('update.landing_components') }}</h3>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.sort_and_edit_different_components_you_added_to_the_page') }}</p>
                </div>

                {{-- Loading --}}
                <div class="js-assigned-components-lists-loading d-none">
                    <div class="d-flex align-items-center justify-content-center p-32 ">
                        <img src="/assets/design_1/img/loading.gif" width="56px" height="56px">
                    </div>
                </div>

                @if(empty($landingItem->components) or !count($landingItem->components))
                    <div class="js-assigned-components-lists-no-items d-flex-center flex-column text-center py-120 px-48">
                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                            <x-iconsax-bul-bill class="icons text-primary" width="32px" height="32px"/>
                        </div>
                        <h4 class="font-16 mt-12">{{ trans('update.no_components!') }}</h4>
                        <p class="font-12 text-gray-500 mt-4">{{ trans('update.there_are_no_components_assigned_to_this_landing_page') }}</p>
                    </div>
                @endif

                {{-- Lists --}}
                <ul class="js-assigned-components-lists draggable-content-lists assigned-components-draggable-lists" data-path="{{ getLandingBuilderUrl("/{$landingItem->id}/sort-components") }}" data-drag-class="assigned-components-draggable-lists">
                    @foreach($landingItem->components as $landingComponent)
                        @include('landingBuilder.admin.pages.create.tabs.includes.landing_component_card', ['landingComponent' => $landingComponent])
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@else
    <div class="d-flex-center flex-column text-center py-56">
        <div class="">
            <img src="/assets/design_1/img/landing_builder/components_tab.svg" alt="" class="img-fluid" height="300px">
        </div>
        <h4 class="font-14 mt-16">{{ trans('update.assign_landing_components') }}</h4>
        <p class="text-gray-500 mt-8">{{ trans('update.you_need_to_save_the_landing_page_information_to_assign_components') }}</p>
    </div>
@endif
