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
                className=""
            />

            <x-landingBuilder-switch
                label="{{ trans('update.enable_overlay') }}"
                id="enable_overlay"
                name="contents[enable_overlay]"
                checked="{{ !!(!empty($contents['enable_overlay']) and !empty($contents['enable_overlay']) == 'on') }}"
                hint=""
                className="mb-0"
            />
        </div>

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


            <x-landingBuilder-make-button
                title="{{ trans('update.button') }}"
                inputNamePrefix="contents[main_content][button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['button'])) ? $contents['main_content']['button'] : []"
                className="mt-24 mb-0"
            />
        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Statistics --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <h3 class="font-14 mb-24">{{ trans('update.statistics') }}</h3>

            @foreach(['statistic_1', 'statistic_2'] as $statistic)
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
                        className=""
                    />

                </div>
            @endforeach
        </div>

        @php
            $mediaContent = (!empty($contents['media_contents']) and is_array($contents['media_contents'])) ? $contents['media_contents'] : [];
        @endphp

        {{-- Image Content --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.image/video') }}</h3>

            <x-landingBuilder-select
                label="{{ trans('update.content_type') }}"
                name="contents[media_contents][content_type]"
                value="{{ (!empty($mediaContent) and !empty($mediaContent['content_type'])) ? $mediaContent['content_type'] : '' }}"
                :items="['image', 'video']"
                hint=""
                className=""
                selectClassName="js-select-change-for-action"
                changeActionEls="js-slider-content-type-fields"
            />

            @php
                $contentTypeFieldImage = (empty($mediaContent) or (!empty($mediaContent['content_type']) and $mediaContent['content_type'] == "image"));
                $contentTypeFieldVideo = (!empty($mediaContent) and !empty($mediaContent['content_type']) and $mediaContent['content_type'] == "video");
            @endphp

            <x-landingBuilder-file
                label="{{ trans('update.image') }}"
                name="contents[media_contents][image]"
                value="{{ (!empty($mediaContent) and !empty($mediaContent['image'])) ? $mediaContent['image'] : '' }}"
                placeholder="{{ (!empty($mediaContent) and !empty($mediaContent['image'])) ? getFileNameByPath($mediaContent['image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 324x324px"
                icon="export"
                accept="image/*"
                className="js-slider-content-type-fields js-select-change-for-action-field-image {{ $contentTypeFieldImage ? '' : 'd-none' }} mb-0"
            />


            <x-landingBuilder-file
                label="{{ trans('update.video_file') }}"
                name="contents[media_contents][video_file]"
                value="{{ (!empty($mediaContent) and !empty($mediaContent['video_file'])) ? $mediaContent['video_file'] : '' }}"
                placeholder="{{ (!empty($mediaContent) and !empty($mediaContent['video_file'])) ? getFileNameByPath($mediaContent['video_file']) : '' }}"
                hint=""
                icon="export"
                accept="video/*"
                className="js-slider-content-type-fields js-select-change-for-action-field-video {{ $contentTypeFieldVideo ? '' : 'd-none' }}"
            />

        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}
