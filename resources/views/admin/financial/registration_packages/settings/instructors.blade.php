

<div class="tab-pane mt-3 fade " id="instructors" role="tabpanel" aria-labelledby="instructors-tab">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="{{ getAdminPanelUrl() }}/settings/main" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="page" value="financial">
                <input type="hidden" name="name" value="{{ \App\Models\Setting::$registrationPackagesInstructorsName }}">

                <div class="form-group custom-switches-stacked">
                    <label class="custom-switch pl-0 d-flex align-items-center">
                        <input type="hidden" name="value[status]" value="0">
                        <input type="checkbox" name="value[status]" id="instructorsStatusSwitch" value="1" {{ (!empty($instructorsSettings) and !empty($instructorsSettings['status']) and $instructorsSettings['status']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description mb-0 cursor-pointer" for="instructorsStatusSwitch">{{ trans('update.registration_packages_instructors_status') }}</label>
                    </label>
                    <div class="text-gray-500 text-small">{{ trans('update.registration_packages_instructors_status_hint') }}</div>
                </div>
                <h2 class="section-title">{{ trans('update.instructor_default_values') }}</h2>

                @foreach(['courses_capacity','courses_count','meeting_count','product_count', 'events_count', 'meeting_packages_count'] as $str)
                    <div class="form-group">
                        <label>{{ trans('update.'.$str) }}</label>
                        <input type="text" class="form-control" name="value[{{ $str }}]" value="{{ (!empty($instructorsSettings) and isset($instructorsSettings[$str])) ? $instructorsSettings[$str] : '' }}">
                    </div>
                @endforeach
                <div class="text-gray-500 text-small mt-1">{{ trans('update.saas_package_condition_hint') }}</div>

                <div class="form-group mt-3">
                    <label class="input-label">{{ trans('admin/main.icon') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager " data-input="instructorIcon" data-preview="holder">
                                <i class="fa fa-upload"></i>
                            </button>
                        </div>
                        <input type="text" name="value[icon]" id="instructorIcon" value="{{ (!empty($instructorsSettings) and !empty($instructorsSettings['icon'])) ? $instructorsSettings['icon'] : old('icon') }}" class="form-control"/>
                    </div>
                </div>

                <div class="text-right">
                <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>
