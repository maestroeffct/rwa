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

            <x-landingBuilder-select
                label="{{ trans('update.background_color') }}"
                name="contents[background_color]"
                value="{{ (!empty($contents['background_color'])) ? $contents['background_color'] : '' }}"
                :items="['primary', 'secondary']"
                hint=""
                className=""
                selectClassName=""
                changeActionEls=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.background') }}"
                name="contents[background]"
                value="{{ !empty($contents['background']) ? $contents['background'] : '' }}"
                placeholder="{{ !empty($contents['background']) ? getFileNameByPath($contents['background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 1920x813px"
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
                className=""
            />

            <x-landingBuilder-textarea
                label="{{ trans('public.description') }}"
                name="contents[main_content][description]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['description'])) ? $contents['main_content']['description'] : '' }}"
                placeholder=""
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.icon') }}"
                name="contents[main_content][icon]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['icon'])) ? $contents['main_content']['icon'] : '' }}"
                placeholder="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['icon'])) ? getFileNameByPath($contents['main_content']['icon']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 440x440px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-make-button
                title="{{ trans('update.button') }}"
                inputNamePrefix="contents[main_content][button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['button'])) ? $contents['main_content']['button'] : []"
                className="mt-24 mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Information  --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.information') }} ({{ trans('update.maximum_4_item') }})"
                addText="{{ trans('update.add_information') }}"
                hint=""
                className="mb-0"
                mainRow="js-information-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['specific_information']) and count($contents['specific_information']))
                    @foreach($contents['specific_information'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($itemData) and !empty($itemData['title'])) ? $itemData['title'] : trans('update.information') }}"
                                id="information_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.cta_and_information_hybrid.information',['itemKey' => $sKey, 'informationData' => $itemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}


<div class="js-information-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.new_information') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.cta_and_information_hybrid.information')
    </x-landingBuilder-accordion>
</div>
