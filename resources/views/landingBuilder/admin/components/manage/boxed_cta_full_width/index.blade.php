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
                hint="{{ trans('update.preferred_size') }} 1368x541px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.dark_mode_background') }} ({{ trans('public.optional') }})"
                name="contents[dark_mode_background]"
                value="{{ !empty($contents['dark_mode_background']) ? $contents['dark_mode_background'] : '' }}"
                placeholder="{{ !empty($contents['dark_mode_background']) ? getFileNameByPath($contents['dark_mode_background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 1368x541px"
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

            <x-landingBuilder-icons-select
                label="{{ trans('update.pre_title_icon') }}"
                name="contents[main_content][pre_title_icon]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['pre_title_icon'])) ? $contents['main_content']['pre_title_icon'] : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-icons-select2"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.pre_title') }}"
                name="contents[main_content][pre_title]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['pre_title'])) ? $contents['main_content']['pre_title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.main_title') }} ({{ trans('update.line_#1') }})"
                name="contents[main_content][main_title_line_1]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['main_title_line_1'])) ? $contents['main_content']['main_title_line_1'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.main_title') }} ({{ trans('update.line_#2') }})"
                name="contents[main_content][main_title_line_2]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['main_title_line_2'])) ? $contents['main_content']['main_title_line_2'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.badge_title') }}"
                name="contents[main_content][badge_title]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['badge_title'])) ? $contents['main_content']['badge_title'] : '' }}"
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


            <x-landingBuilder-make-button
                title="{{ trans('update.primary_button') }}"
                inputNamePrefix="contents[main_content][primary_button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['primary_button'])) ? $contents['main_content']['primary_button'] : []"
                className="mt-24"
            />

            <x-landingBuilder-make-button
                title="{{ trans('update.secondary_button') }}"
                inputNamePrefix="contents[main_content][secondary_button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['secondary_button'])) ? $contents['main_content']['secondary_button'] : []"
                className="mt-24 mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Instructors  --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.links') }}"
                addText="{{ trans('update.add_link') }}"
                className="mb-0"
                mainRow="js-link-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['specific_links']) and count($contents['specific_links']))
                    @foreach($contents['specific_links'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($itemData) and !empty($itemData['title'])) ? $itemData['title'] : trans('update.link') }}"
                                id="link_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.boxed_cta_full_width.link',['itemKey' => $sKey, 'linkData' => $itemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}


<div class="js-link-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.new_link') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.boxed_cta_full_width.link')
    </x-landingBuilder-accordion>
</div>
