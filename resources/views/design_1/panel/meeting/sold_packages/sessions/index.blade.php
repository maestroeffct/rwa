@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.meeting.sold_packages.sessions.package_details')

    @if(!empty($sessions) and !$sessions->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.meeting_package_sessions') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_and_manage_sessions_for_this_meeting_package') }}</p>
                </div>
            </div>

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/meetings/sold-packages/{{ $meetingPackageSold->id }}/sessions">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.session_number') }}</th>
                        <th class="text-center">{{ trans('update.session_date') }}</th>
                        <th class="text-center">{{ trans('update.session_time') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-right">{{ trans('update.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($sessions as $sessionRow)
                        @include('design_1.panel.meeting.sold_packages.sessions.table_items', ['session' => $sessionRow])
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
            'file_name' => 'sold_meeting_packages.svg',
            'title' => trans('update.sold_meeting_packages_no_result'),
            'hint' => nl2br(trans('update.sold_meeting_packages_no_result_hint')),
        ])
    @endif


@endsection

@push('scripts_bottom')
    <script>
        var hoursLang = '{{ trans('home.hours') }}';
        var saveLang = '{{ trans('public.save') }}';
        var sessionDurationLang = '{{ trans('update.session_duration') }}';
        var createSessionLang = '{{ trans('update.create_session') }}';
        var passwordLang = '{{ trans('auth.password') }}';
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}';
        var finishLang = '{{ trans('public.finish') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/meeting_sold_packages.min.js"></script>
@endpush
