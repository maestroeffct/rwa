@extends('design_1.panel.layouts.panel')

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
@endpush

@section('content')

    <div class="card-with-dashed-mask position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-16 rounded-24 mb-28">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-56 rounded-12 bg-gray-100">
                <img src="{{ $organization->getAvatar(56) }}" alt="{{ $organization->full_name }}" class="img-cover rounded-12">
            </div>

            <div class="ml-8">
                <h6 class="font-14 font-weight-bold">{{ $organization->full_name }}</h6>
                <p class="font-12 mt-4 text-gray-500">{{ $coursesCount }} {{ trans('product.courses') }} | {{ $instructorsCount }} {{ trans('home.instructors') }} | {{ $studentsCount }} {{ trans('public.students') }}</p>
            </div>
        </div>

        <a href="{{ $organization->getProfileUrl() }}" class="btn btn-primary mt-12 mt-lg-0">{{ trans('update.organization_profile') }}</a>
    </div>


    {{-- List Table --}}
    @if(!empty($organizationHaveItems))

        <div class="d-flex align-items-center gap-20 gap-lg-40 border-bottom-gray-100">

            @php
                $tabs = [
                    'courses' => 'teacher',
                    'bundles' => 'box',
                ];
            @endphp

            @foreach($tabs as $tabName => $tabIcon)
                <div class="js-get-view-data-by-tab navbar-item navbar-item-h-52 d-flex align-items-center  cursor-pointer {{ $loop->first ? 'active' : '' }}"
                     data-filter-name="source" data-filter-value="{{ $tabName }}"
                     data-container-id="tableListContainer"
                >
                    @svg("iconsax-lin-{$tabIcon}", ['width' => '20px', 'height' => '20px', 'class' => 'icons'])

                    <span class="ml-4">{{ trans("update.{$tabName}") }}</span>
                </div>
            @endforeach

        </div>


        <div id="tableListContainer" class="" data-view-data-path="/panel/courses/organization_classes" data-body=".js-page-courses-lists">
            <div class="js-page-courses-lists row mt-20">
                @foreach($courses as $courseItem)
                    <div class="col-12 col-md-4 col-lg-3 col-xl-2 mt-20">
                        @include("design_1.panel.webinars.organization_classes.grid_card", ['course' => $courseItem])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                 data-container-items=".js-page-courses-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'organization_courses.svg',
            'title' => trans('panel.you_not_have_any_webinar'),
            'hint' =>  trans('panel.no_result_hint') ,
            'btn' => ['url' => '/panel/webinar/new','text' => trans('panel.create_a_webinar') ]
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var undefinedActiveSessionLang = '{{ trans('webinars.undefined_active_session') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var selectChapterLang = '{{ trans('update.select_chapter') }}';
        var liveSessionInfoLang = '{{ trans('update.live_session_info') }}';
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}';
    </script>

    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>
    <script src="/assets/design_1/js/panel/my_course_lists.min.js"></script>
    <script src="/assets/design_1/js/panel/make_next_session.min.js"></script>

@endpush
