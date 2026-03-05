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
                label="{{ trans('update.maximum_course_cards') }}"
                name="contents[maximum_course_cards]"
                value="{{ (!empty($contents['maximum_course_cards'])) ? $contents['maximum_course_cards'] : '' }}"
                :items="['4', '8', '12']"
                hint=""
                className=""
                selectClassName=""
                selectItemDontTrans="true"
            />

            <x-landingBuilder-switch
                label="{{ trans('update.enable_component') }}"
                id="enable"
                name="enable"
                checked="{{ !!($landingComponent->enable) }}"
                hint=""
                className=""
            />
        </div>

        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.section_title') }}</h3>

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

    </div> {{-- End Col--}}

    <div class="col-12 col-lg-6 mt-20">

        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.course_tabs') }}"
                addText="{{ trans('update.add_course_tab') }}"
                className="mb-0"
                mainRow="js-course-tabs-content-main-card"
            >
                @if(!empty($contents) and !empty($contents['course_tabs_content']) and count($contents['course_tabs_content']))
                    @foreach($contents['course_tabs_content'] as $sKey => $tabContentDataRow)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($tabContentDataRow) and !empty($tabContentDataRow['title'])) ? $tabContentDataRow['title'] : trans('update.course_tab') }}"
                                id="course_tabs_content_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.multi_tab_courses.course_tabs_content',['itemKey' => $sKey, 'tabContentData' => $tabContentDataRow])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>
    </div>

</div>



{{-- Courses Tab --}}
<div class="js-course-tabs-content-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.course_tab') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.multi_tab_courses.course_tabs_content')
    </x-landingBuilder-accordion>
</div>
