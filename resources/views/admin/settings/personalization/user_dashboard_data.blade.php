@php
    if (!empty($itemValue) and !is_array($itemValue)) {
        $itemValue = json_decode($itemValue, true);
    }

@endphp

@push('styles_top')

@endpush

<div class="mt-3" id="user_dashboard_data">

    <form action="{{ getAdminPanelUrl() }}/settings/user_dashboard_data" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="page" value="personalization">
        <input type="hidden" name="user_dashboard_data" value="user_dashboard_data">

        <div class="row">
            <div class="col-12 col-md-6">


                <div class="form-group">
                    <label class="input-label">{{ trans('update.student_enroll_on_courses') }}</label>
                    <select name="value[student_enroll_on_courses][]" multiple="multiple" class="form-control search-webinar-select2" data-placeholder="Search classes">

                        @if(!empty($itemValue) and !empty($itemValue['student_enroll_on_courses']))
                            @foreach($itemValue['student_enroll_on_courses'] as $webinarId)
                                @php
                                    $studentEnrollOnCourse = \App\Models\Webinar::query()->find($webinarId)
                                @endphp

                                @if(!empty($studentEnrollOnCourse))
                                    <option value="{{ $studentEnrollOnCourse->id }}" selected>{{ $studentEnrollOnCourse->title }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('update.student_overview_courses') }}</label>
                    <select name="value[student_overview_courses][]" multiple="multiple" class="form-control search-webinar-select2" data-placeholder="Search classes">

                        @if(!empty($itemValue) and !empty($itemValue['student_overview_courses']))
                            @foreach($itemValue['student_overview_courses'] as $webinarId)
                                @php
                                    $studentEnrollOnCourse = \App\Models\Webinar::query()->find($webinarId)
                                @endphp

                                @if(!empty($studentEnrollOnCourse))
                                    <option value="{{ $studentEnrollOnCourse->id }}" selected>{{ $studentEnrollOnCourse->title }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('update.student_when_dont_upcoming_live_session') }}</label>
                    <select name="value[student_when_dont_upcoming_live_session][]" multiple="multiple" class="form-control search-webinar-select2" data-placeholder="Search classes">

                        @if(!empty($itemValue) and !empty($itemValue['student_when_dont_upcoming_live_session']))
                            @foreach($itemValue['student_when_dont_upcoming_live_session'] as $webinarId)
                                @php
                                    $studentEnrollOnCourse = \App\Models\Webinar::query()->find($webinarId)
                                @endphp

                                @if(!empty($studentEnrollOnCourse))
                                    <option value="{{ $studentEnrollOnCourse->id }}" selected>{{ $studentEnrollOnCourse->title }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                    <div class="text-gray-500 mt-1 fs-12">{{ trans('update.student_when_dont_upcoming_live_session_hint') }}</div>
                </div>


            </div>
        </div>


        <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
    </form>

</div>


