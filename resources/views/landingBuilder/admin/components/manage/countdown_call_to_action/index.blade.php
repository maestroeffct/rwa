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
                label="{{ trans('update.section_style') }}"
                name="contents[section_style]"
                value="{{ (!empty($contents['section_style'])) ? $contents['section_style'] : '' }}"
                :items="['container_width', 'full_width']"
                hint=""
                className=""
                selectClassName=""
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

            <x-landingBuilder-file
                label="{{ trans('update.background') }}"
                name="contents[background]"
                placeholder="{{ !empty($contents['background']) ? getFileNameByPath($contents['background']) : '' }}"
                value="{{ !empty($contents['background']) ? $contents['background'] : '' }}"
                hint="{{ trans('update.preferred_size') }} 1920x870px"
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

            <x-landingBuilder-input
                label="{{ trans('update.countdown_end_time') }}"
                name="contents[countdown_end_time]"
                value="{{ (!empty($contents['countdown_end_time'])) ? $contents['countdown_end_time'] : '' }}"
                type="text"
                placeholder=""
                hint=""
                className=""
                icon="calendar"
                inputClass="datetimepicker js-default-init-date-picker"
            />

            <h3 class="font-14 mb-24 text-gray-500">{{ trans('update.images') }}</h3>

            <x-landingBuilder-file
                label="{{ trans('update.main_image') }}"
                name="contents[main_image]"
                value="{{ (!empty($contents['main_image'])) ? $contents['main_image'] : '' }}"
                placeholder="{{ (!empty($contents['main_image'])) ? getFileNameByPath($contents['main_image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 670x400px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #2"
                name="contents[image_2]"
                value="{{ (!empty($contents['image_2'])) ? $contents['image_2'] : '' }}"
                placeholder="{{ (!empty($contents['image_2'])) ? getFileNameByPath($contents['image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 200x200px"
                icon="export"
                accept="image/*"
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

        {{-- Statistics --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <h3 class="font-14 mb-24">{{ trans('update.statistics') }}</h3>

            @foreach(['statistic_1', 'statistic_2', 'statistic_3'] as $statistic)
                <div class="mt-16">
                    <h5 class="font-14 mb-24 text-gray-500">{{ trans("update.{$statistic}") }}</h5>

                    <x-landingBuilder-input
                        label="{{ trans('public.title') }}"
                        name="contents[{{ $statistic }}][title]"
                        value="{{ (!empty($contents[$statistic]) and !empty($contents[$statistic]['title'])) ? $contents[$statistic]['title'] : '' }}"
                        placeholder=""
                        hint=""
                        className=""
                    />

                    <x-landingBuilder-input
                        label="{{ trans('update.subtitle') }}"
                        name="contents[{{ $statistic }}][subtitle]"
                        value="{{ (!empty($contents[$statistic]) and !empty($contents[$statistic]['subtitle'])) ? $contents[$statistic]['subtitle'] : '' }}"
                        placeholder=""
                        hint=""
                        className="mb-0"
                    />

                </div>
            @endforeach
        </div>
    </div>

</div>
