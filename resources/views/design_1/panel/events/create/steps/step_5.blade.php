@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    {{-- Event Speakers --}}
    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-profile-2user class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.speakers') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.add_and_showcase_speakers_for_your_event') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.events.create.includes.accordions.speaker')
        </div>

        <div class="col-lg-6 mt-16">
            @if(!empty($event->speakers) and count($event->speakers))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.speakers') }}</h3>

                    <ul class="draggable-content-lists speakers-draggable-lists" data-path="/panel/events/{{ $event->id }}/speakers/order-items" data-drag-class="speakers-draggable-lists">
                        @foreach($event->speakers as $speakerInfo)
                            @include('design_1.panel.events.create.includes.accordions.speaker',['speaker' => $speakerInfo])
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-document-sketch class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.speakers_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.speakers_no_result_hint') !!}</p>
                </div>
            @endif

        </div>
    </div>



    {{-- FAQs --}}
    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed mt-32">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-bill class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.frequently_asked_questions') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.add_FAQ_and_display_them_on_the_course_page') }}</p>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.events.create.includes.accordions.faq')
        </div>

        <div class="col-lg-6 mt-16">
            @if(!empty($event->faqs) and count($event->faqs))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.faqs') }}</h3>

                    <ul class="draggable-content-lists faq-draggable-lists" data-path="" data-drag-class="faq-draggable-lists">
                        @foreach($event->faqs as $faqInfo)
                            @include('design_1.panel.events.create.includes.accordions.faq',['faq' => $faqInfo])
                        @endforeach
                    </ul>

                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-message-question class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('public.faq_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('public.faq_no_result_hint') !!}</p>
                </div>
            @endif
        </div>
    </div>
    {{-- End FAQs --}}

    @php
        $learningMaterials = $event->extraDescriptions->where('type', 'learning_materials');
        $requirements = $event->extraDescriptions->where('type', 'requirements');
    @endphp

    {{-- Learning Materials --}}
    <div class="d-flex align-items-center justify-content-between mt-32 p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-bill class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.learning_materials') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.add_FAQ_and_display_them_on_the_course_page') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.events.create.includes.accordions.extra_description', [
                'extraDescriptionType' => 'learning_materials',
                'extraDescriptionParentAccordion' => 'learning_materials_accordion',
            ])
        </div>

        <div class="col-lg-6 mt-16">
            @if(!empty($learningMaterials) and count($learningMaterials))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.learning_materials') }}</h3>

                    <ul class="draggable-content-lists learning_materials-draggable-lists" data-path="" data-drag-class="learning_materials-draggable-lists">
                        @foreach($learningMaterials as $learningMaterial)
                            @include('design_1.panel.events.create.includes.accordions.extra_description',
                                    [
                                        'event' => $event,
                                        'extraDescription' => $learningMaterial,
                                        'extraDescriptionType' => 'learning_materials',
                                        'extraDescriptionParentAccordion' => 'learning_materials_accordion',
                                    ])
                        @endforeach
                    </ul>

                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-teacher class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.learning_materials_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.learning_materials_no_result_hint') !!}</p>
                </div>
            @endif

        </div>
    </div>

    {{-- Requirements --}}
    <div class="d-flex align-items-center justify-content-between mt-32 p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-bill class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.requirements') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.add_FAQ_and_display_them_on_the_course_page') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            @include('design_1.panel.events.create.includes.accordions.extra_description', [
                'extraDescriptionType' => 'requirements',
                'extraDescriptionParentAccordion' => 'requirements_accordion',
            ])
        </div>

        <div class="col-lg-6 mt-16">
            @if(!empty($requirements) and count($requirements))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.requirements') }}</h3>

                    <ul class="draggable-content-lists requirements-draggable-lists" data-path="" data-drag-class="requirements-draggable-lists">
                        @foreach($requirements as $requirement)
                            @include('design_1.panel.events.create.includes.accordions.extra_description',
                                    [
                                        'event' => $event,
                                        'extraDescription' => $requirement,
                                        'extraDescriptionType' => 'requirements',
                                        'extraDescriptionParentAccordion' => 'requirements_accordion',
                                    ])
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-shield-tick class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.requirements_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.requirements_no_result_hint') !!}</p>
                </div>
            @endif

        </div>
    </div>

    {{-- Company Logos --}}
    <div class="d-flex align-items-center justify-content-between mt-32 p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-shapes class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.company_logos') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.display_company_logos_on_the_course_page') }}</p>
            </div>
        </div>
    </div>


    <div class="border-dashed border-gray-300 rounded-16 p-28 mt-16">
        @include("design_1.panel.events.create.includes.accordions.company_logos")
    </div>

    {{-- Location --}}
    @if($event->type == "in_person")
        @include('design_1.panel.events.create.includes.accordions.location')
    @endif

</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script>
        var leafletApiPath = '{{ getLeafletApiPath() }}';
        var selectRegionDefaultVal = '';
        var selectStateLang = '{{ trans('update.choose_a_state') }}';
        var selectCityLang = '{{ trans('update.choose_a_city') }}';
        var selectDistrictLang = '{{ trans('update.all_districts') }}';
    </script>

    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>
    <script src="{{ getDesign1ScriptPath("leaflet_map") }}"></script>
    <script src="/assets/design_1/js/parts/get_regions.min.js"></script>
@endpush
