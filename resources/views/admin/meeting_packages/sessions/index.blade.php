@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>


        <div class="section-body">
            {{-- Top Stats --}}
            @include('admin.meeting_packages.sessions.package_details')

            <div class="card mt-20">
                <div class="card-header justify-content-between">
                    <div>
                        <h5 class="font-14 mb-0">{{ trans('update.meeting_package_sessions') }}</h5>
                        <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.view_and_manage_sessions_for_this_meeting_package') }}</p>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table custom-table font-14">
                            <tr>
                                <th class="text-left">{{ trans('update.session_number') }}</th>
                                <th class="text-center">{{ trans('update.session_date') }}</th>
                                <th class="text-center">{{ trans('update.session_time') }}</th>
                                <th class="text-center">{{ trans('public.status') }}</th>
                                <th class="text-right">{{ trans('update.actions') }}</th>
                            </tr>

                            @foreach($sessions as $session)
                                <tr>
                                    {{-- Session Number --}}
                                    <td class="text-left">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex-center size-48 bg-gray-300 rounded-circle">{{ $session->number_row }}</div>
                                            <div class=" ml-12">{{ trans('update.session') }} #{{ $session->number_row }}</div>
                                        </div>
                                    </td>

                                    {{-- Session Date --}}
                                    <td class="text-center">
                                        {{ !empty($session->date) ? dateTimeFormat($session->date, 'j M Y') : '-' }}
                                    </td>

                                    {{-- Session Time --}}
                                    <td class="text-center">
                                        {{ !empty($session->date) ? dateTimeFormat($session->date, 'H:i') : '-' }}
                                    </td>


                                    {{-- Status --}}
                                    <td class="text-center">
                                        <div class="d-flex-center">
                                            @if($session->status == "finished")
                                                <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-success bg-success-30">{{ trans('public.finished') }}</div>
                                            @elseif(!empty($session->date))
                                                <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-warning bg-warning-30">{{ trans('update.scheduled') }}</div>
                                            @else
                                                <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-primary bg-primary-30">{{ trans('update.not_scheduled') }}</div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="text-right" width="150px">
                                        <div class="btn-group dropdown table-actions position-relative">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right">

                                                @if($session->status != "finished")
                                                    <a href="{{ getAdminPanelUrl("/meeting-packages/sold/{$meetingPackageSold->id}/sessions/{$session->id}/set-date") }}"
                                                       data-title="{{ trans('update.set_session_time') }}"
                                                       class="js-meeting-package-session-set-time dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4"
                                                    >
                                                        <x-iconsax-lin-calendar-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">{{ trans('update.set_session_time') }}</span>
                                                    </a>

                                                    @if(!empty($session->date))
                                                        @if($session->status == "draft")
                                                            <a href="{{ getAdminPanelUrl("/meeting-packages/sold/{$meetingPackageSold->id}/sessions/{$session->id}/set-api") }}"
                                                               data-title="{{ trans('update.create_a_session') }}"
                                                               class="js-meeting-package-create-session dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4"
                                                            >
                                                                <x-iconsax-lin-monitor-recorder class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('update.create_a_session') }}</span>
                                                            </a>
                                                        @endif

                                                        @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl("/meeting-packages/sold/{$meetingPackageSold->id}/sessions/{$session->id}/finish"),
                                                           'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("panel.finish_meeting"),
                                                           'btnIcon' => 'tick-circle',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ])
                                                    @endif
                                                @endif

                                                    <a href="{{ getAdminPanelUrl("/meeting-packages/sold/{$meetingPackageSold->id}/get-student-detail") }}"
                                                       data-title="{{ trans('update.student_information') }}"
                                                       class="js-meeting-sold-package-student-detail dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4"
                                                    >
                                                        <x-iconsax-lin-profile class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">{{ trans('update.student_information') }}</span>
                                                    </a>

                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $sessions->appends(request()->input())->links() }}
                </div>

            </div>

        </div>
    </section>
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
    <script src="/assets/admin/js/parts/meeting_sold_packages.min.js"></script>
@endpush
