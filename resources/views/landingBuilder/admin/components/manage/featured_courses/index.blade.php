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
                label="{{ trans('update.floating_background_image') }}"
                name="contents[floating_background]"
                value="{{ !empty($contents['floating_background']) ? $contents['floating_background'] : '' }}"
                placeholder="{{ !empty($contents['floating_background']) ? getFileNameByPath($contents['floating_background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 226x272px"
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
                className=""
            />

            <x-landingBuilder-switch
                label="{{ trans('update.enable_slider') }}"
                id="enable_slider"
                name="contents[enable_slider]"
                checked="{{ (!empty($contents['enable_slider']) and $contents['enable_slider'] == 'on') }}"
                hint="{{ trans('update.enable_slider_switch_hint') }}"
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
                className="mb-0"
            />

        </div>
    </div> {{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">
        {{-- Featured Courses --}}
        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.featured_courses') }}"
                addText="{{ trans('update.add_a_course') }}"
                className="mb-0"
                mainRow="js-featured-course-main-card"
            >
                @if(!empty($contents) and !empty($contents['featured_courses']) and count($contents['featured_courses']))
                    @foreach($contents['featured_courses'] as $sKey => $featuredData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ trans('update.featured_course') }}"
                                id="featured_course_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.featured_courses.featured_item',['itemKey' => $sKey, 'featuredCourse' => $featuredData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>
    </div>{{-- End Col --}}

</div>{{-- End Row --}}

<div class="js-featured-course-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.featured_course') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.featured_courses.featured_item')
    </x-landingBuilder-accordion>
</div>
