@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <form action="{{ getAdminPanelUrl('/attendances/settings') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="page" value="general">
                                        <input type="hidden" name="name" value="{{ \App\Models\Setting::$attendanceSettingsName }}">
                                        <input type="hidden" name="locale" value="{{ \App\Models\Setting::$defaultSettingsLocale }}">

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[status]" value="0">
                                                <input type="checkbox" name="value[status]" id="giftsStatusSwitch" value="1" {{ (!empty($settingValues) and !empty($settingValues['status']) and $settingValues['status']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="giftsStatusSwitch">{{ trans('admin/main.active') }}</label>
                                            </label>
                                            <div class="text-gray-500 text-small">{{ trans('update.attendances_settings_active_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="">{{ trans('update.time_allowed_for_attendance') }} ({{ trans('public.minutes') }})</label>
                                            <input type="number" name="value[time_allowed_for_attendance]" class="form-control" value="{{ (!empty($settingValues) and !empty($settingValues['time_allowed_for_attendance'])) ? $settingValues['time_allowed_for_attendance'] : '' }}">
                                            <div class="text-muted mt-8">{{ trans('update.time_allowed_for_attendance_input_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="">{{ trans('update.time_allowed_for_delay') }} ({{ trans('public.minutes') }})</label>
                                            <input type="number" name="value[time_allowed_for_delay]" class="form-control" value="{{ (!empty($settingValues) and !empty($settingValues['time_allowed_for_delay'])) ? $settingValues['time_allowed_for_delay'] : '' }}">
                                            <div class="text-muted mt-8">{{ trans('update.time_allowed_for_delay_input_hint') }}</div>
                                        </div>

                                        @php
                                            $otherSwitches = ['allow_instructor_to_change_attendance_status'];
                                        @endphp

                                        @foreach($otherSwitches as $otherSwitch)
                                            <div class="form-group custom-switches-stacked">
                                                <label class="custom-switch pl-0 d-flex align-items-center">
                                                    <input type="hidden" name="value[{{ $otherSwitch }}]" value="0">
                                                    <input type="checkbox" name="value[{{ $otherSwitch }}]" id="{{ $otherSwitch }}Switch" value="1" {{ (!empty($settingValues) and !empty($settingValues[$otherSwitch]) and $settingValues[$otherSwitch]) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                    <span class="custom-switch-indicator"></span>
                                                    <label class="custom-switch-description mb-0 cursor-pointer" for="{{ $otherSwitch }}Switch">{{ trans("update.{$otherSwitch}") }}</label>
                                                </label>
                                                <div class="text-gray-500 text-small">{{ trans("update.{$otherSwitch}_hint") }}</div>
                                            </div>
                                        @endforeach

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
                                        </div>

                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
