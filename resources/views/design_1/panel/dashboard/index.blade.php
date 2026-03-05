@extends("design_1.panel.layouts.panel")

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>
@endpush

@section("content")
    <div class="dashboard-body">
        @if($authUser->isUser())
            <div class="student-dashboard">
                @include('design_1.panel.dashboard.student.index')
            </div>
        @else
            <div class="instructor-dashboard">
                @include('design_1.panel.dashboard.instructor.index')
            </div>
        @endif
    </div>
@endsection

@push("scripts_bottom")
    <script>
        var learningActivityLang = '{{ trans('update.learning_activity') }}';
        var minsLang = '{{ trans('update.mins') }}';
        var noticeLang = '{{ trans('update.notice') }}';
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}';
        var joinTheMeetingLang = '{{ trans('update.join_the_meeting') }}';
        var passwordLang = '{{ trans('auth.password') }}';

        var $eventsWithTimestamp = @json((!empty($eventsWithTimestamp) and count($eventsWithTimestamp)) ? $eventsWithTimestamp : []);
    </script>

    <script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
    <script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>
    <script src="/assets/design_1/vendor/apexcharts/apexcharts.js"></script>

    <script src="/assets/design_1/js/panel/meeting_requests.min.js"></script>
    <script src="/assets/design_1/js/panel/events_calendar.min.js"></script>
    <script src="/assets/design_1/js/panel/dashboard.min.js"></script>
@endpush

@if(!empty($giftModal))
    @push('scripts_bottom2')
        <script>
            (function () {
                "use strict";

                handleFireSwalModal('{!! $giftModal !!}', 32)
            })(jQuery)
        </script>
    @endpush
@endif
