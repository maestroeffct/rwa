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
                hint="{{ trans('update.preferred_size') }} 1920x700px"
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

            {{-- Events --}}
            <h3 class="font-14 text-gray-500 mt-28 mb-24">{{ trans('update.events') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('update.number_of_events') }}"
                name="contents[number_of_events]"
                value="{{ (!empty($contents['number_of_events'])) ? $contents['number_of_events'] : '' }}"
                type="number"
                placeholder=""
                hint="{{ trans('update.number_of_events_to_display_in_this_slider') }}"
                className=""
            />

            <x-landingBuilder-select
                label="{{ trans('update.events_source') }}"
                name="contents[events_source]"
                value="{{ (!empty($contents) and !empty($contents['events_source'])) ? $contents['events_source'] : '' }}"
                :items="['newest_events', 'top_selling_events', 'best_rated_events']"
                hint=""
                className=""
            />

            {{-- Buttons --}}
            <x-landingBuilder-make-button
                title="{{ trans('update.primary_button') }}"
                inputNamePrefix="contents[buttons][primary_button]"
                :buttonData="(!empty($contents['buttons']) and !empty($contents['buttons']['primary_button'])) ? $contents['buttons']['primary_button'] : []"
                className="mt-24"
            />

            <x-landingBuilder-make-button
                title="{{ trans('update.secondary_button') }}"
                inputNamePrefix="contents[buttons][secondary_button]"
                :buttonData="(!empty($contents['buttons']) and !empty($contents['buttons']['secondary_button'])) ? $contents['buttons']['secondary_button'] : []"
                className="mt-24 mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Links  --}}
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
                                @include('landingBuilder.admin.components.manage.sliding_events.link',['itemKey' => $sKey, 'linkData' => $itemData])
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
        @include('landingBuilder.admin.components.manage.sliding_events.link')
    </x-landingBuilder-accordion>
</div>
