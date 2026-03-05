@extends('design_1.panel.layouts.panel')

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.upcoming_courses.my_courses.top_stats')

    @if(!empty($upcomingCourses) and !$upcomingCourses->isEmpty())
        <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/ai-contents">
            <div class="js-table-body-lists row">
                @foreach($upcomingCourses as $upcomingCourseRow)
                    <div class="col-12 col-md-6 col-lg-3 mt-20">
                        @include("design_1.panel.upcoming_courses.my_courses.grid_card", ['upcomingCourse' => $upcomingCourseRow])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'upcoming_courses.svg',
            'title' => trans('update.you_not_have_any_upcoming_courses'),
            'hint' =>  trans('update.you_not_have_any_upcoming_courses_hint') ,
            'btn' => ['url' => '/panel/upcoming_courses/new','text' => trans('update.create_a_upcoming_course') ]
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var assignPublishedCourseLang = '{{ trans('update.assign_published_course') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var selectChapterLang = '{{ trans('update.select_chapter') }}';
        var liveSessionInfoLang = '{{ trans('update.live_session_info') }}';
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}';
    </script>

    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/my_course_lists.min.js"></script>
    <script src="/assets/design_1/js/panel/upcoming_course.min.js"></script>

@endpush
