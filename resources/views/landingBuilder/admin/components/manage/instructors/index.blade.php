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
                hint="{{ trans('update.preferred_size') }} 1920x840px"
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

            <h3 class="font-14 mb-24 text-gray-500">{{ trans('update.cta_section') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('update.title_bold_text') }}"
                name="contents[cta_section][title_bold_text]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['title_bold_text'])) ? $contents['cta_section']['title_bold_text'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.title_regular_text') }}"
                name="contents[cta_section][title_regular_text]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['title_regular_text'])) ? $contents['cta_section']['title_regular_text'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-textarea
                label="{{ trans('public.description') }}"
                name="contents[cta_section][description]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['description'])) ? $contents['cta_section']['description'] : '' }}"
                placeholder=""
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />

            <x-landingBuilder-icons-select
                label="{{ trans('update.icon') }}"
                name="contents[cta_section][icon]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['icon'])) ? $contents['cta_section']['icon'] : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-icons-select2"
                className="mb-0"
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

        {{-- Instructors  --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.instructors') }}"
                addText="{{ trans('update.add_instructor') }}"
                className="mb-0"
                mainRow="js-instructor-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['specific_instructors']) and count($contents['specific_instructors']))
                    @foreach($contents['specific_instructors'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            @php
                                $selectedInstructorId = (!empty($itemData) and !empty($itemData['instructor_id'])) ? $itemData['instructor_id'] : null;
                                $selectedInstructor = (!empty($selectedInstructorId) and !empty($instructors) and count($instructors)) ? $instructors->where('id', $selectedInstructorId)->first() : null;
                            @endphp

                            <x-landingBuilder-accordion
                                title="{{ (!empty($selectedInstructor)) ? $selectedInstructor->full_name : trans('update.new_instructor') }}"
                                id="instructor_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.instructors.instructor',['itemKey' => $sKey, 'selectedInstructorItem' => $selectedInstructor])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}


<div class="js-instructor-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.new_instructor') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.instructors.instructor')
    </x-landingBuilder-accordion>
</div>
