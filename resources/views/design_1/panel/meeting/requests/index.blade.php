@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.meeting.requests.top_stats')

    @if(!empty($reserveMeetings) and !$reserveMeetings->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('panel.meeting_list') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_blog_posts_and_related_statistics') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.meeting.requests.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/meetings/requests">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('quiz.student') }}</th>
                        <th class="text-center">{{ trans('update.meeting_type') }}</th>
                        <th class="text-center">{{ trans('public.day') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-center">{{ trans('public.time') }}</th>
                        <th class="text-center">{{ trans('public.paid_amount') }}</th>
                        <th class="text-center">{{ trans('update.students_count') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($reserveMeetings as $reserveMeeting)
                        @include('design_1.panel.meeting.requests.table_items', ['reserveMeeting' => $reserveMeeting])
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
            'file_name' => 'meeting_requests.svg',
            'title' => trans('panel.meeting_no_result'),
            'hint' => nl2br(trans('panel.meeting_no_result_hint')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var createASessionLang = '{{ trans('update.create_a_session') }}';
        var createSessionLang = '{{ trans('update.create_session') }}';
        var saveLang = '{{ trans('public.save') }}';
        var cancelLang = '{{ trans('public.cancel') }}';
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}';
        var joinTheMeetingLang = '{{ trans('update.join_the_meeting') }}';
        var passwordLang = '{{ trans('auth.password') }}';
        var finishLang = '{{ trans('public.finish') }}';

    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/meeting_requests.min.js"></script>
@endpush
