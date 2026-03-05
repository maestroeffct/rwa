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
                value="{{ !empty($contents['background']) ? $contents['background'] : '' }}"
                placeholder="{{ !empty($contents['background']) ? getFileNameByPath($contents['background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 1920x1270px"
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
                label="{{ trans('public.title') }}"
                name="contents[cta_section][title]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['title'])) ? $contents['cta_section']['title'] : '' }}"
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

            <x-landingBuilder-input
                label="{{ trans('update.link_title') }}"
                name="contents[cta_section][link_title]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['link_title'])) ? $contents['cta_section']['link_title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('panel.url') }}"
                name="contents[cta_section][url]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['url'])) ? $contents['cta_section']['url'] : '' }}"
                placeholder=""
                hint=""
                icon="link-1"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }}"
                name="contents[cta_section][image]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['image'])) ? $contents['cta_section']['image'] : '' }}"
                placeholder="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['image'])) ? getFileNameByPath($contents['cta_section']['image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 140x140px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Features  --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.instructors') }}"
                addText="{{ trans('update.add_instructor') }}"
                className="mb-0"
                mainRow="js-instructor-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['meeting_instructors']) and count($contents['meeting_instructors']))
                    @foreach($contents['meeting_instructors'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            @php
                                $selectedInstructorId = (!empty($itemData) and !empty($itemData['instructor'])) ? $itemData['instructor'] : null;
                                $selectedInstructor = (!empty($selectedInstructorId) and !empty($meetingInstructors) and count($meetingInstructors)) ? $meetingInstructors->where('id', $selectedInstructorId)->first() : null;
                            @endphp

                            <x-landingBuilder-accordion
                                title="{{ (!empty($selectedInstructor)) ? $selectedInstructor->full_name : trans('update.new_instructor') }}"
                                id="instructor_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.meeting_booking_list.instructor',['itemKey' => $sKey, 'selectedInstructorItem' => $selectedInstructor])
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
        @include('landingBuilder.admin.components.manage.meeting_booking_list.instructor')
    </x-landingBuilder-accordion>
</div>
