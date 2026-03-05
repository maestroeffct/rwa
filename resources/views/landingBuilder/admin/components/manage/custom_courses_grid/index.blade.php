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
                hint="{{ trans('update.preferred_size') }} 1464x1268px"
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

        {{-- Main Content  --}}
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

            <x-landingBuilder-make-button
                title="{{ trans('update.button') }}"
                inputNamePrefix="contents[main_content][button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['button'])) ? $contents['main_content']['button'] : []"
                className="mt-24 mb-0"
            />

            <h5 class="font-14 text-gray-500 my-24">{{ trans('update.floating_images') }}</h5>

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #1"
                name="contents[floating_images][image_1]"
                value="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_1'])) ? $contents['floating_images']['image_1'] : '' }}"
                placeholder="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_1'])) ? getFileNameByPath($contents['floating_images']['image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 208x208px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #2"
                name="contents[floating_images][image_2]"
                value="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_2'])) ? $contents['floating_images']['image_2'] : '' }}"
                placeholder="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_2'])) ? getFileNameByPath($contents['floating_images']['image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 208x208px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">
        {{-- Featured Courses --}}
        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.courses') }}"
                addText="{{ trans('update.add_a_course') }}"
                className="mb-0"
                mainRow="js-featured-course-main-card"
            >
                @if(!empty($contents) and !empty($contents['featured_courses']) and count($contents['featured_courses']))
                    @foreach($contents['featured_courses'] as $sKey => $featuredData)
                        @if($sKey != 'record')
                            @php
                                $course = \App\Models\Webinar::query()->find($featuredData['course']);
                            @endphp

                            <x-landingBuilder-accordion
                                title="{{ !empty($course) ? $course->title : trans('update.course') }}"
                                id="featured_course_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.custom_courses_grid.course_item',['itemKey' => $sKey, 'featuredCourse' => $featuredData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>
    </div>{{-- End Col --}}

</div>



<div class="js-featured-course-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.course') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.custom_courses_grid.course_item')
    </x-landingBuilder-accordion>
</div>
