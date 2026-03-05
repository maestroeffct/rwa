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
                hint="{{ trans('update.preferred_size') }} 1920x1200px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.dark_mode_background') }} ({{ trans('public.optional') }})"
                name="contents[dark_mode_background]"
                value="{{ !empty($contents['dark_mode_background']) ? $contents['dark_mode_background'] : '' }}"
                placeholder="{{ !empty($contents['dark_mode_background']) ? getFileNameByPath($contents['dark_mode_background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 1920x1200px"
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
                label="{{ trans('update.badge_text') }}"
                name="contents[upper_cta][badge_text]"
                value="{{ (!empty($contents['upper_cta']) and !empty($contents['upper_cta']['badge_text'])) ? $contents['upper_cta']['badge_text'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

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
                label="{{ trans('update.title_line_1') }}"
                name="contents[main_content][title_line_1]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['title_line_1'])) ? $contents['main_content']['title_line_1'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.title_line_2') }}"
                name="contents[main_content][title_line_2]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['title_line_2'])) ? $contents['main_content']['title_line_2'] : '' }}"
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
                label="{{ trans('update.badge_text') }}"
                name="contents[main_content][badge_text]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['badge_text'])) ? $contents['main_content']['badge_text'] : '' }}"
                placeholder=""
                hint=""
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
        {{-- Students Widget --}}
        <div class="p-16 rounded-16 border-gray-200 ">

            <x-landingBuilder-addable-text-input
                title="{{ trans('update.checked_items') }}"
                inputLabel="{{ trans('public.title') }}"
                inputName="contents[checked_items][record]"
                :items="(!empty($contents['checked_items'])) ? $contents['checked_items'] : []"
                className=""
                titleClassName="text-dark"
            />
        </div>

        {{-- Image Content --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.image_content') }}</h3>

            <x-landingBuilder-select
                label="{{ trans('update.image_content_type') }}"
                name="contents[image_content][type]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['type'])) ? $contents['image_content']['type'] : '' }}"
                :items="['image', 'video']"
                hint=""
                className=""
                selectClassName="js-select-change-for-action"
                changeActionEls="js-image-content-type-fields"
            />

            @php
                $imageContentTypeFieldImage = ((empty($contents['image_content']) or empty($contents['image_content']['type'])) or (!empty($contents['image_content']) and !empty($contents['image_content']['type']) and $contents['image_content']['type'] == "image"));
                $imageContentTypeFieldVideo = (!empty($contents['image_content']) and !empty($contents['image_content']['type']) and $contents['image_content']['type'] == "video");
            @endphp

            <x-landingBuilder-file
                label="{{ trans('update.image') }}"
                name="contents[image_content][image]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['image'])) ? $contents['image_content']['image'] : '' }}"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['image'])) ? getFileNameByPath($contents['image_content']['image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 640x640px"
                icon="export"
                accept="image/*"
                className="js-image-content-type-fields js-select-change-for-action-field-image {{ $imageContentTypeFieldImage ? '' : 'd-none' }}"
            />

            <x-landingBuilder-file
                label="{{ trans('update.video') }}"
                name="contents[image_content][video]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['video'])) ? $contents['image_content']['video'] : '' }}"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['video'])) ? getFileNameByPath($contents['image_content']['video']) : '' }}"
                hint=""
                icon="export"
                accept="video/*"
                className="js-image-content-type-fields js-select-change-for-action-field-video {{ $imageContentTypeFieldVideo ? '' : 'd-none' }}"
            />


            <x-landingBuilder-file
                label="{{ trans('update.overlay_image') }} #1"
                name="contents[image_content][overlay_image_1]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image_1'])) ? $contents['image_content']['overlay_image_1'] : '' }}"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image_1'])) ? getFileNameByPath($contents['image_content']['overlay_image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 340px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.overlay_image') }} #2"
                name="contents[image_content][overlay_image_2]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image_2'])) ? $contents['image_content']['overlay_image_2'] : '' }}"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image_2'])) ? getFileNameByPath($contents['image_content']['overlay_image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 340px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>

    </div>{{-- End Col --}}
</div>{{-- End Row --}}
