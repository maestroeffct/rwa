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
                placeholder="{{ !empty($contents['background']) ? getFileNameByPath($contents['background']) : '' }}"
                value="{{ !empty($contents['background']) ? $contents['background'] : '' }}"
                hint="{{ trans('update.preferred_size') }} 64x64px"
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

        {{-- Upper Call to Action --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.upper_call_to_action') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('update.main_text') }}"
                name="contents[upper_cta][main_text]"
                value="{{ (!empty($contents['upper_cta']) and !empty($contents['upper_cta']['main_text'])) ? $contents['upper_cta']['main_text'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-icons-select
                label="{{ trans('update.icon') }}"
                name="contents[upper_cta][icon]"
                value="{{ (!empty($contents['upper_cta']) and !empty($contents['upper_cta']['icon'])) ? $contents['upper_cta']['icon'] : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-icons-select2"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('panel.url') }}"
                name="contents[upper_cta][url]"
                value="{{ (!empty($contents['upper_cta']) and !empty($contents['upper_cta']['url'])) ? $contents['upper_cta']['url'] : '' }}"
                placeholder=""
                hint=""
                icon="link"
                className="mb-0"
            />
        </div>

        {{-- Main Content --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.main_content') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('public.title') }} ({{ trans('update.line_#1') }})"
                name="contents[main_content][title_line_1]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['title_line_1'])) ? $contents['main_content']['title_line_1'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />


            <x-landingBuilder-input
                label="{{ trans('update.highlighted_word') }}"
                name="contents[main_content][highlighted_word]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['highlighted_word'])) ? $contents['main_content']['highlighted_word'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('public.title') }} ({{ trans('update.line_#2') }})"
                name="contents[main_content][title_line_2]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['title_line_2'])) ? $contents['main_content']['title_line_2'] : '' }}"
                placeholder=""
                hint=""
                className="mt-24"
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
                label="{{ trans('update.floating_icon') }} #1"
                name="contents[main_content][floating_icon_1]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['floating_icon_1'])) ? $contents['main_content']['floating_icon_1'] : '' }}"
                placeholder="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['floating_icon_1'])) ? getFileNameByPath($contents['main_content']['floating_icon_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 84x84px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.floating_icon') }} #2"
                name="contents[main_content][floating_icon_2]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['floating_icon_2'])) ? $contents['main_content']['floating_icon_2'] : '' }}"
                placeholder="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['floating_icon_2'])) ? getFileNameByPath($contents['main_content']['floating_icon_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 84x84px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-make-button
                title="{{ trans('update.primary_button') }}"
                inputNamePrefix="contents[main_content][primary_button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['primary_button'])) ? $contents['main_content']['primary_button'] : []"
                className=""
            />

            <x-landingBuilder-make-button
                title="{{ trans('update.secondary_button') }}"
                inputNamePrefix="contents[main_content][secondary_button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['secondary_button'])) ? $contents['main_content']['secondary_button'] : []"
                className="mt-24"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Image Content --}}
        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.image_content') }}</h3>

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #1"
                name="contents[image_1]"
                value="{{ (!empty($contents['image_1'])) ? $contents['image_1'] : '' }}"
                placeholder="{{ (!empty($contents['image_1'])) ? getFileNameByPath($contents['image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 280x480px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #2"
                name="contents[image_2]"
                value="{{ (!empty($contents['image_2'])) ? $contents['image_2'] : '' }}"
                placeholder="{{ (!empty($contents['image_2'])) ? getFileNameByPath($contents['image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 280x480px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #3"
                name="contents[image_3]"
                value="{{ (!empty($contents['image_3'])) ? $contents['image_3'] : '' }}"
                placeholder="{{ (!empty($contents['image_3'])) ? getFileNameByPath($contents['image_3']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 280x480px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #4"
                name="contents[image_4]"
                value="{{ (!empty($contents['image_4'])) ? $contents['image_4'] : '' }}"
                placeholder="{{ (!empty($contents['image_4'])) ? getFileNameByPath($contents['image_4']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 280x480px"
                icon="export"
                accept="image/*"
                className=""
            />

        </div>
    </div>

</div>
