@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Scheduled Sessions --}}
    @if(!empty($meetingPackageScheduledSessions) and count($meetingPackageScheduledSessions))
        <div class="sold-tickets-event-card position-relative mb-20">
            <div class="sold-tickets-event-card__mask"></div>
            <div class="position-relative z-index-2 d-flex align-items-center justify-content-between p-20 rounded-16 bg-white ">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-64 bg-gray-200 rounded-circle">
                        <x-iconsax-lin-box-time class="icons text-gray-500" width="32px" height="32px"/>
                    </div>
                    <div class="ml-8">
                        <h4 class="font-14">{{ trans('update.n_scheduled_package_sessions', ['count' => count($meetingPackageScheduledSessions)]) }}</h4>
                        <p class="font-12 text-gray-500 mt-4">{{ trans('update.n_scheduled_package_sessions_msg_hint') }}</p>
                    </div>
                </div>

                <a href="/panel/meetings/purchased-packages" target="_blank" class="d-flex align-items-center gap-4 text-primary font-12">
                    <span class="font-weight-bold">{{ trans('update.meeting_packages') }}</span>
                    <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                </a>
            </div>
        </div>
    @endif

    {{-- Top Stats --}}
    @include('design_1.panel.meeting.reservation.top_stats')

    @if(!empty($reserveMeetings) and !$reserveMeetings->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('panel.meeting_list') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_blog_posts_and_related_statistics') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.meeting.reservation.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/meetings/reservation">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('public.instructor') }}</th>
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
                        @include('design_1.panel.meeting.reservation.table_items', ['reserveMeeting' => $reserveMeeting])
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
            'file_name' => 'meeting_reservations.svg',
            'title' => trans('panel.meeting_no_result'),
            'hint' => nl2br(trans('panel.meeting_no_result_hint')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var createASessionLang = '{{ trans('update.create_a_session') }}';
        var createSessionLang = '{{ trans('update.create_session') }}';
        var saveLang = '{{ trans('public.save') }}'
        var cancelLang = '{{ trans('public.cancel') }}'
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}'
        var joinTheMeetingLang = '{{ trans('update.join_the_meeting') }}'
        var passwordLang = '{{ trans('auth.password') }}'
        var finishLang = '{{ trans('public.finish') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/meeting_requests.min.js"></script>
@endpush
