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

            <x-landingBuilder-switch
                label="{{ trans('update.enable_radius') }}"
                id="enable_radius"
                name="contents[enable_radius]"
                checked="{{ (!empty($contents['enable_radius']) and $contents['enable_radius'] == 'on') }}"
                hint="{{ trans('update.enable_radius_switch_hint') }}"
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



    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Instructors  --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.banner_images') }}"
                addText="{{ trans('update.add_banner') }}"
                className="mb-0"
                mainRow="js-banner-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['specific_banners']) and count($contents['specific_banners']))
                    @foreach($contents['specific_banners'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($itemData) and !empty($itemData['url'])) ? $itemData['url'] : trans('update.banner') }}"
                                id="banner_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.banner_2_items_per_row.banner',['itemKey' => $sKey, 'bannerData' => $itemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}


<div class="js-banner-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.new_banner') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.banner_2_items_per_row.banner')
    </x-landingBuilder-accordion>
</div>
