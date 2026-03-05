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
                label="{{ trans('public.title') }} ({{ trans('update.line_#1') }})"
                name="contents[main_content][title_line_1]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['title_line_1'])) ? $contents['main_content']['title_line_1'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-addable-text-input
                title="{{ trans('update.highlighted_words') }}"
                inputLabel="{{ trans('update.highlighted_word') }}"
                inputName="contents[main_content][highlight_words][record]"
                :items="(!empty($contents['main_content']) and !empty($contents['main_content']['highlight_words'])) ? $contents['main_content']['highlight_words'] : []"
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

    </div>

    <div class="col-12 col-lg-6 mt-20">
        {{-- Students Widget --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <h3 class="font-14 mb-24">{{ trans('update.students_widget') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[students_widget][title]"
                value="{{ (!empty($contents['students_widget']) and !empty($contents['students_widget']['title'])) ? $contents['students_widget']['title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('panel.url') }}"
                name="contents[students_widget][url]"
                value="{{ (!empty($contents['students_widget']) and !empty($contents['students_widget']['url'])) ? $contents['students_widget']['url'] : '' }}"
                placeholder=""
                hint=""
                icon="link"
                className=""
            />

            <x-landingBuilder-addable-file-input
                title="{{ trans('update.students') }}"
                inputLabel="{{ trans('update.student_avatar') }}"
                inputName="contents[students_widget_avatars][record]"
                :items="(!empty($contents['students_widget']) and !empty($contents['students_widget_avatars'])) ? $contents['students_widget_avatars'] : []"
                accept="image/*"
                className=""
            />

        </div>

        {{-- Image Content --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.image_content') }}</h3>

            <x-landingBuilder-select
                label="{{ trans('update.image_content_type') }}"
                name="contents[image_content][type]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['type'])) ? $contents['image_content']['type'] : '' }}"
                :items="['image', 'lottie_json']"
                hint=""
                className=""
                selectClassName="js-select-change-for-action"
                changeActionEls="js-image-content-type-fields"
            />

            @php
                $imageContentTypeFieldImage = ((empty($contents['image_content']) or empty($contents['image_content']['type'])) or (!empty($contents['image_content']) and !empty($contents['image_content']['type']) and $contents['image_content']['type'] == "image"));
                $imageContentTypeFieldLottie = (!empty($contents['image_content']) and !empty($contents['image_content']['type']) and $contents['image_content']['type'] == "lottie_json");
            @endphp

            <x-landingBuilder-file
                label="{{ trans('update.image') }}"
                name="contents[image_content][image]"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['image'])) ? getFileNameByPath($contents['image_content']['image']) : '' }}"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['image'])) ? $contents['image_content']['image'] : '' }}"
                hint="{{ trans('update.preferred_size') }} 640x640px"
                icon="export"
                accept="image/*"
                className="js-image-content-type-fields js-select-change-for-action-field-image {{ $imageContentTypeFieldImage ? '' : 'd-none' }}"
            />

            <x-landingBuilder-file
                label="{{ trans('update.lottie_json') }}"
                name="contents[image_content][lottie_json]"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['lottie_json'])) ? getFileNameByPath($contents['image_content']['lottie_json']) : '' }}"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['lottie_json'])) ? $contents['image_content']['lottie_json'] : '' }}"
                hint=""
                icon="export"
                accept="application/json"
                className="js-image-content-type-fields js-select-change-for-action-field-lottie_json {{ $imageContentTypeFieldLottie ? '' : 'd-none' }}"
            />

            <x-landingBuilder-file
                label="{{ trans('update.overlay_image') }}"
                name="contents[image_content][overlay_image]"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image'])) ? getFileNameByPath($contents['image_content']['overlay_image']) : '' }}"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image'])) ? $contents['image_content']['overlay_image'] : '' }}"
                hint="{{ trans('update.preferred_size') }} 120x480px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.spinning_image') }}"
                name="contents[image_content][spinning_image]"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['spinning_image'])) ? getFileNameByPath($contents['image_content']['spinning_image']) : '' }}"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['spinning_image'])) ? $contents['image_content']['spinning_image'] : '' }}"
                hint="{{ trans('update.preferred_size') }} 120x120px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>

    </div>
</div>
