<div class="row">
    <div class="col-12 col-lg-6 mt-20">
        {{-- General Information --}}
        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.general_information') }}</h3>

            @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($landingComponent) ? $landingComponent : null,
                'withoutReloadLocale' => false,
                'extraClass' => ''
            ])

            <x-landingBuilder-file
                label="{{ trans('update.floating_background_image') }}"
                name="contents[floating_background]"
                value="{{ !empty($contents['floating_background']) ? $contents['floating_background'] : '' }}"
                placeholder="{{ !empty($contents['floating_background']) ? getFileNameByPath($contents['floating_background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 180x180px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-switch
                label="{{ trans('update.enable_component') }}"
                id="enable"
                name="enable"
                checked="{{ !!($landingComponent->enable) }}"
                hint=""
                className="mb-0"
            />
        </div>

        {{-- Main Content --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.main_content') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('update.pre_title') }}"
                name="contents[main_content][pre_title]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['pre_title'])) ? $contents['main_content']['pre_title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[main_content][title]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['title'])) ? $contents['main_content']['title'] : '' }}"
                placeholder=""
                hint=""
                className="mb-0"
            />
        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Information Cards --}}
        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.trending_categories') }}"
                addText="{{ trans('update.add_category') }}"
                className="mb-0"
                mainRow="js-trending-category-main-card"
            >
                @if(!empty($contents) and !empty($contents['trending_categories']) and count($contents['trending_categories']))
                    @foreach($contents['trending_categories'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            @php
                                $selectedTrendingCategoryId = (!empty($itemData) and !empty($itemData['category'])) ? $itemData['category'] : null;
                                $selectedTrendingCategory = (!empty($selectedTrendingCategoryId) and !empty($trendingCategories) and count($trendingCategories)) ? $trendingCategories->where('id', $selectedTrendingCategoryId)->first() : null;
                            @endphp

                            <x-landingBuilder-accordion
                                title="{{ (!empty($selectedTrendingCategory)) ? $selectedTrendingCategory->category->title : trans('update.trending_category') }}"
                                id="trending_category_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.trending_categories.trending_category', ['itemKey' => $sKey, 'selectedTrendingCategoryItem' => $selectedTrendingCategory])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div> {{-- End Row --}}

<div class="js-trending-category-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.trending_category') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.trending_categories.trending_category')
    </x-landingBuilder-accordion>
</div>
