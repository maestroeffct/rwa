@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.webinars.attendances.my_attendance.top_stats')

    @if(!empty($sessions) and $sessions->isNotEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.live_sessions_history') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.review_and_manage_student_attendance_history') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.webinars.attendances.my_attendance.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/courses/my-attendances">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.course_and_session') }}</th>
                        <th class="text-left">{{ trans('update.instructor') }}</th>
                        <th class="text-center">{{ trans('public.start_date') }}</th>
                        <th class="text-center">{{ trans('update.joined_date') }}</th>
                        <th class="text-center">{{ trans('update.attendance_status') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($sessions as $sessionRow)
                        @include('design_1.panel.webinars.attendances.my_attendance.table_items', ['session' => $sessionRow])
                    @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'attendances.svg',
            'title' => trans('update.attendances_no_result'),
            'hint' =>  nl2br(trans('update.my_attendances_no_result_hint')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var commentLang = '{{ trans('panel.comment') }}';
        var replyToCommentLang = '{{ trans('panel.reply_to_the_comment') }}';
        var editCommentLang = '{{ trans('panel.edit_comment') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var failedLang = '{{ trans('quiz.failed') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var reportSuccessLang = '{{ trans('panel.report_success') }}';
        var messageToReviewerLang = '{{ trans('public.message_to_reviewer') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/courses_comments.min.js"></script>
@endpush
