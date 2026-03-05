<form action="" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-6 col-md-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-500" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.from') }}</label>
                <input type="text" name="from" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('from') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-500" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.to') }}</label>
                <input type="text" name="to" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('to') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.day') }}</label>

                <select class="form-control select2" id="day" name="day" data-minimum-results-for-search="Infinity">
                    <option value="all">{{ trans('public.all_days') }}</option>
                    <option value="saturday" {{ (request()->get('day') === "saturday") ? 'selected' : '' }}>{{ trans('public.saturday') }}</option>
                    <option value="sunday" {{ (request()->get('day') === "sunday") ? 'selected' : '' }}>{{ trans('public.sunday') }}</option>
                    <option value="monday" {{ (request()->get('day') === "monday") ? 'selected' : '' }}>{{ trans('public.monday') }}</option>
                    <option value="tuesday" {{ (request()->get('day') === "tuesday") ? 'selected' : '' }}>{{ trans('public.tuesday') }}</option>
                    <option value="wednesday" {{ (request()->get('day') === "wednesday") ? 'selected' : '' }}>{{ trans('public.wednesday') }}</option>
                    <option value="thursday" {{ (request()->get('day') === "thursday") ? 'selected' : '' }}>{{ trans('public.thursday') }}</option>
                    <option value="friday" {{ (request()->get('day') === "friday") ? 'selected' : '' }}>{{ trans('public.friday') }}</option>
                </select>

            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.instructor') }}</label>

                <select name="instructor_id" class="form-control select2">
                    <option value="all">{{ trans('webinars.all_instructors') }}</option>

                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" @if(request()->get('instructor_id') == $instructor->id) selected @endif>{{ $instructor->full_name }}</option>
                    @endforeach
                </select>

            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.meeting_type') }}</label>

                <select name="meeting_type" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option>{{ trans('public.all') }}</option>
                    <option value="online" {{ (request()->get('meeting_type') === "online") ? 'selected' : '' }}>{{ trans('update.online') }}</option>
                    <option value="in_person" {{ (request()->get('meeting_type') === "in_person") ? 'selected' : '' }}>{{ trans('update.in_person') }}</option>
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.meeting_population') }}</label>

                <select name="population" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option>{{ trans('public.all') }}</option>
                    <option value="individual" {{ (request()->get('population') === "individual") ? 'selected' : '' }}>{{ trans('update.individual') }}</option>
                    <option value="group" {{ (request()->get('population') === "group") ? 'selected' : '' }}>{{ trans('update.group') }}</option>
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.status') }}</label>

                <select name="status" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option>{{ trans('public.all') }}</option>
                    <option value="open" {{ (request()->get('status') === "open") ? 'selected' : '' }}>{{ trans('public.open') }}</option>
                    <option value="finished" {{ (request()->get('status') === "finished") ? 'selected' : '' }}>{{ trans('public.finished') }}</option>
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
        </div>
    </div>
</form>
