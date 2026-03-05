@extends('design_1.panel.layouts.panel')

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
@endpush

@section('content')

    {{-- List Table --}}
    @if(!empty($courses) and $courses->isNotEmpty())
        <div id="tableListContainer" class="" data-view-data-path="/panel/bundles/{{ $bundle->id }}/courses">
            <div class="js-page-courses-lists row mt-20">
                @foreach($courses as $courseItem)
                    <div class="col-12 col-lg-6 mb-32">
                        @include("design_1.panel.webinars.my_courses.course_card.index", ['course' => $courseItem])
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
            'file_name' => 'bundle_courses.svg',
            'title' => trans('panel.you_not_have_any_webinar'),
            'hint' =>  trans('panel.no_result_hint'),
            'extraClass' => 'mt-0',
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

    <script src="/assets/design_1/js/panel/my_course_lists.min.js"></script>
    <script src="/assets/design_1/js/panel/make_next_session.min.js"></script>

@endpush
