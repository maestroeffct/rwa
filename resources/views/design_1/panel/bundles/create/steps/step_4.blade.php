@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

<div class="bg-white rounded-16 p-16 mt-32">


    {{-- Courses --}}
    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed mt-32">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-video-tick class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('product.courses') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.display_courses_on_the_bundle_page') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mt-20">
            @include('design_1.panel.bundles.create.includes.accordions.courses')
        </div>

        <div class="col-lg-6 mt-36">
            @if(!empty($bundle->bundleWebinars) and count($bundle->bundleWebinars))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('product.courses') }}</h3>

                    <ul class="draggable-content-lists related_courses-draggable-lists" data-path="" data-drag-class="related_courses-draggable-lists">
                        @foreach($bundle->bundleWebinars as $bundleWebinarInfo)
                            @include('design_1.panel.bundles.create.includes.accordions.courses',['bundleWebinar' => $bundleWebinarInfo])
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-video-play class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.bundle_webinar_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.bundle_webinar_no_result_hint') !!}</p>
                </div>
            @endif
        </div>
    </div>


    {{-- Related Courses --}}
    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed mt-32">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-video-tick class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.related_courses') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.display_related_courses_on_the_course_page') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mt-20">
            @include('design_1.panel.bundles.create.includes.accordions.related_courses')
        </div>

        <div class="col-lg-6 mt-36">
            @if(!empty($bundle->relatedCourses) and count($bundle->relatedCourses))
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.related_courses') }}</h3>

                    <ul class="draggable-content-lists related_courses-draggable-lists" data-path="" data-drag-class="related_courses-draggable-lists">
                        @foreach($bundle->relatedCourses as $relatedCourseInfo)
                            @include('design_1.panel.bundles.create.includes.accordions.related_courses',['relatedCourse' => $relatedCourseInfo])
                        @endforeach
                    </ul>

                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-arrange-circle-2 class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.related_courses_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.related_courses_no_result_hint') !!}</p>
                </div>
            @endif

        </div>
    </div>

</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

@endpush
