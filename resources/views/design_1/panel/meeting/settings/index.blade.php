@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/design_1/vendor/persian-datepicker/persian-datepicker.min.css">
@endpush


@section('content')
    <div class="bg-white p-16 rounded-16 mb-56">
        <h2 class="font-16 font-weight-bold">{{ trans('update.meeting_settings') }}</h2>
        <p class="mt-4 text-gray-500">{{ trans('update.manage_your_meeting_settings') }}</p>

        @php
            $activeTab = request()->get('tab', 'basic');
        @endphp

        <div class="custom-tabs mt-16">
            <div class="custom-tabs-items-scrollable-mobile d-flex align-items-center gap-16 gap-lg-40 border-bottom-gray-200 border-top-gray-200">
                <div class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($activeTab == "basic") ? 'active' : '' }}" data-tab-toggle data-tab-href="#basicSettingsTab">
                    <x-iconsax-lin-profile-2user class="icons" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.basic_settings') }}</span>
                </div>

                <div class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($activeTab == "timesheet") ? 'active' : '' }}" data-tab-toggle data-tab-href="#timesheetTab">
                    <x-iconsax-lin-clock-1 class="icons" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.timesheet') }}</span>
                </div>

                @if(!empty($meetingPackagesFeatureStatus))
                    <div class="navbar-item navbar-item-h-52 d-inline-flex-center cursor-pointer {{ ($activeTab == "packages") ? 'active' : '' }}" data-tab-toggle data-tab-href="#meetingPackagesTab">
                        <x-iconsax-lin-box-time class="icons" width="20px" height="20px"/>
                        <span class="ml-4">{{ trans('update.meeting_packages') }}</span>
                    </div>
                @endif
            </div>

            <div class="custom-tabs-body mt-16">

                <div class="custom-tabs-content {{ ($activeTab == "basic") ? 'active' : '' }}" id="basicSettingsTab">
                    @include('design_1.panel.meeting.settings.tabs.basic_settings')
                </div>

                <div class="custom-tabs-content {{ ($activeTab == "timesheet") ? 'active' : '' }}" id="timesheetTab">
                    @include('design_1.panel.meeting.settings.tabs.timesheet')
                </div>

                @if(!empty($meetingPackagesFeatureStatus))
                    <div class="custom-tabs-content {{ ($activeTab == "packages") ? 'active' : '' }}" id="meetingPackagesTab">
                        @include('design_1.panel.meeting.settings.tabs.meeting_packages.index')
                    </div>
                @endif

            </div>

        </div>


    </div>
@endsection

@push('scripts_bottom')
    <script type="text/javascript">
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var successDeleteTime = '{{ trans('meeting.success_delete_time') }}';
        var errorDeleteTime = '{{ trans('meeting.error_delete_time') }}';
        var successSavedTime = '{{ trans('meeting.success_save_time') }}';
        var errorSavingTime = '{{ trans('meeting.error_saving_time') }}';
        var noteToTimeMustGreater = '{{ trans('meeting.note_to_time_must_greater_from_time') }}';
        var requestSuccess = '{{ trans('public.request_success') }}';
        var requestFailed = '{{ trans('public.request_failed') }}';
        var saveMeetingSuccessLang = '{{ trans('meeting.save_meeting_setting_success') }}';
    </script>

    <script src="/assets/design_1/vendor/persian-datepicker/persian-date.min.js"></script>
    <script src="/assets/design_1/vendor/persian-datepicker/persian-datepicker.min.js"></script>

    <script src="/assets/design_1/js/panel/meeting_settings.min.js"></script>
@endpush
