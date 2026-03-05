@extends('admin.layouts.app')


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.meeting_packages_settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.meeting_packages_settings') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <form action="{{ getAdminPanelUrl("/meeting-packages/settings") }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="page" value="general">
                                        <input type="hidden" name="name" value="{{ \App\Models\Setting::$meetingPackagesSettingsName }}">

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[status]" value="0">
                                                <input type="checkbox" name="value[status]" id="eventsStatusSwitch" value="1" {{ (!empty($values) and !empty($values['status'])) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="eventsStatusSwitch">{{ trans('admin/main.active') }}</label>
                                            </label>
                                            <div class="text-gray-500 text-small">{{ trans('update.meeting_packages_setting_active_hint') }}</div>
                                        </div>


                                        <div class="form-group">
                                            <label class="input-label">{{ trans('update.default_icon') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager" data-input="default_icon" data-preview="holder">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="value[default_icon]" id="default_icon" value="{{ (!empty($values) and !empty($values['default_icon'])) ? $values['default_icon'] : old('value.default_icon') }}" class="form-control " />
                                                <div class="input-group-append">
                                                    <button type="button" class="input-group-text admin-file-view" data-input="default_icon">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>


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
