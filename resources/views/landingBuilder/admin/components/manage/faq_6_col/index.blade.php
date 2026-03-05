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
                label="{{ trans('update.background') }}"
                name="contents[background]"
                value="{{ !empty($contents['background']) ? $contents['background'] : '' }}"
                placeholder="{{ !empty($contents['background']) ? getFileNameByPath($contents['background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 1920x729px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.floating_image') }}"
                name="contents[floating_image]"
                value="{{ !empty($contents['floating_image']) ? $contents['floating_image'] : '' }}"
                placeholder="{{ !empty($contents['floating_image']) ? getFileNameByPath($contents['floating_image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 208x208px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.floating_image') }} #2"
                name="contents[floating_image_2]"
                value="{{ !empty($contents['floating_image_2']) ? $contents['floating_image_2'] : '' }}"
                placeholder="{{ !empty($contents['floating_image_2']) ? getFileNameByPath($contents['floating_image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 139x127px"
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

        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.additional_information') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('update.pre_title') }}"
                name="contents[additional_information][pre_title]"
                value="{{ (!empty($contents['additional_information']) and !empty($contents['additional_information']['pre_title'])) ? $contents['additional_information']['pre_title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[additional_information][title]"
                value="{{ (!empty($contents['additional_information']) and !empty($contents['additional_information']['title'])) ? $contents['additional_information']['title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-textarea
                label="{{ trans('public.description') }}"
                name="contents[additional_information][description]"
                value="{{ (!empty($contents['additional_information']) and !empty($contents['additional_information']['description'])) ? $contents['additional_information']['description'] : '' }}"
                placeholder=""
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />


            <x-landingBuilder-make-button
                title="{{ trans('update.button') }}"
                inputNamePrefix="contents[additional_information][button]"
                :buttonData="(!empty($contents['additional_information']) and !empty($contents['additional_information']['button'])) ? $contents['additional_information']['button'] : []"
                className="mt-24 mb-0"
            />
        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- FAQ Items --}}
        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.faq_items') }}"
                className="mb-0"
                mainRow="js-faq-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['faq_items']) and count($contents['faq_items']))
                    @foreach($contents['faq_items'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($itemData) and !empty($itemData['title'])) ? $itemData['title'] : trans('update.faq_item') }}"
                                id="faq_item_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.faq_6_col.faq_item',['itemKey' => $sKey, 'faqItemData' => $itemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}


<div class="js-faq-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.faq_item') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.faq_6_col.faq_item')
    </x-landingBuilder-accordion>
</div>
