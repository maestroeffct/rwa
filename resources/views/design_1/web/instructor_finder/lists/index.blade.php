@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.markercluster/markerCluster.css">
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.markercluster/markerCluster.Default.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("instructor_finder") }}">
@endpush

@section('content')
    <div class="container instructor-finder mt-80 pb-60">

        {{-- top hero --}}
        <div class="position-relative z-index-15">
            <div class="instructor-finder__top-hero-mask rounded-32"></div>

            <div class="position-relative bg-white py-16 py-md-32 rounded-32 z-index-2">
                <div class="d-flex align-items-center px-16 px-md-32">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-teacher class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <div class="ml-8">
                        <h1 class="font-24 font-weight-bold">{{ trans('home.instructors') }}</h1>
                        <p class="mt-4 font-12 text-gray-500">{{ trans('update.explore_instructor_profiles_and_book_meetings') }}</p>
                    </div>
                </div>

                {{-- Featured Instructors --}}
                @include('design_1.web.instructor_finder.lists.top_featured_instructors')
            </div>
        </div>

        {{-- Map --}}
        @include('design_1.web.instructor_finder.lists.map')

        <form id="filtersForm" action="/instructor-finder" method="get">

            {{-- Top Filters --}}
            @include('design_1.web.instructor_finder.lists.top_filters')


            <div class="row flex-md-row-reverse">
                {{-- Instructors Card --}}
                <div class="col-12 col-md-8 col-lg-9 mt-20">
                    <div id="instructorsList">
                        @if(!empty($instructors) and $instructors->isNotEmpty())
                            @foreach($instructors as $instructor)
                                @include('design_1.web.instructor_finder.lists.instructor_card', ['instructor' => $instructor])
                            @endforeach
                        @else
                            @include('design_1.panel.includes.no-result',[
                               'file_name' => 'instructor-finder.svg',
                               'title' => trans('update.instructor_finder_no_result'),
                               'hint' => nl2br(trans('update.instructor_finder_no_result_hint')),
                           ])
                        @endif
                    </div>

                    <div class="text-center">
                        <button type="button" id="loadMoreInstructors" data-url="/instructor-finder" class="btn btn-outline-gray-500 mt-48 {{ ($instructors->lastPage() <= $instructors->currentPage()) ? ' d-none' : '' }}">{{ trans('site.load_more_instructors') }}</button>
                    </div>

                </div>

                {{-- Left Side --}}
                <div class="col-12 col-md-4 col-lg-3 mt-20">

                    {{-- Top Mentors --}}
                    @include('design_1.web.instructor_finder.lists.left_side.top_mentors')

                    {{-- Filters --}}
                    @include('design_1.web.instructor_finder.lists.left_side.filters')

                    {{-- Location --}}
                    @include('design_1.web.instructor_finder.lists.left_side.location')

                    {{-- Others --}}
                    @include('design_1.web.instructor_finder.lists.left_side.other')

                </div>
            </div>

        </form>
    </div>
@endsection


@push('scripts_bottom')
    <script>
        var leafletApiPath = '{{ getLeafletApiPath() }}';
        var selectRegionDefaultVal = '';
        var selectStateLang = '{{ trans('update.choose_a_state') }}';
        var selectCityLang = '{{ trans('update.choose_a_city') }}';
        var selectDistrictLang = '{{ trans('update.all_districts') }}';
        var bookAMeetingLang = '{{ trans('public.book_a_meeting') }}';
        var profileLang = '{{ trans('public.profile') }}';
        var hourLang = '{{ trans('update.hour') }}';
        var freeLang = '{{ trans('public.free') }}';
        var noResultTitle = '{{ trans('update.instructor_finder_no_result') }}';
        var noResultHint = '{!! trans('update.instructor_finder_no_result_hint') !!}';
        var currency = '{{ $currency }}';
        var mapUsers = JSON.parse(@json($mapUsers->toJson()));

        var starIcon = `<x-iconsax-bol-star-1 class="icons" width="14" height="14"/>`;
        var frameIcon = `<x-iconsax-bol-frame class="icons text-gray-500" width="20px" height="20px"/>`;
        var calendarIcon = `<x-iconsax-bol-calendar-2 class="icons text-white" width="20px" height="20px"/>`;
    </script>

    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>
    <script src="/assets/vendors/leaflet/leaflet.markercluster/leaflet.markercluster-src.js"></script>
    <script src="{{ getDesign1ScriptPath("leaflet_map") }}"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>

    <script src="{{ getDesign1ScriptPath("get_regions") }}"></script>
    <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>
    <script src="{{ getDesign1ScriptPath("range_slider_helpers") }}"></script>

    <script src="{{ getDesign1ScriptPath("instructor_finder") }}"></script>
@endpush
