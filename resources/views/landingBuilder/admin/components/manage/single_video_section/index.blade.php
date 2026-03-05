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
                label="{{ trans('update.badge_title') }}"
                name="contents[main_content][badge_title]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['badge_title'])) ? $contents['main_content']['badge_title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.back_image') }}"
                name="contents[main_content][back_image]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['back_image'])) ? $contents['main_content']['back_image'] : '' }}"
                placeholder="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['back_image'])) ? getFileNameByPath($contents['main_content']['back_image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 180x210px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">
        {{-- Video Content  --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <h3 class="font-14 mb-24">{{ trans('update.video_content') }}</h3>

            <x-landingBuilder-file
                label="{{ trans('update.video_file') }}"
                name="contents[video_content][video_file]"
                value="{{ (!empty($contents['video_content']) and !empty($contents['video_content']['video_file'])) ? $contents['video_content']['video_file'] : '' }}"
                placeholder="{{ (!empty($contents['video_content']) and !empty($contents['video_content']['video_file'])) ? getFileNameByPath($contents['video_content']['video_file']) : '' }}"
                hint=""
                icon="export"
                accept="video/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.revolver_icon') }}"
                name="contents[video_content][revolver_icon]"
                value="{{ (!empty($contents['video_content']) and !empty($contents['video_content']['revolver_icon'])) ? $contents['video_content']['revolver_icon'] : '' }}"
                placeholder="{{ (!empty($contents['video_content']) and !empty($contents['video_content']['revolver_icon'])) ? getFileNameByPath($contents['video_content']['revolver_icon']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 188x188px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-switch
                label="{{ trans('update.display_play_button') }}"
                id="display_play_button"
                name="contents[video_content][display_play_button]"
                checked="{{ (!empty($contents['video_content']) and !empty($contents['video_content']['display_play_button']) and $contents['video_content']['display_play_button'] == 'on') }}"
                hint=""
                className="mb-0"
            />
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}
