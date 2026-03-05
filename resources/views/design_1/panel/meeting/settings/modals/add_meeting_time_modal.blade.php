<form class="js-meeting-time-form" action="" method="post">

    <div class="d-flex-center flex-column text-center">
        <div class="d-flex-center size-64 rounded-16 bg-info-gradient">
            <x-iconsax-bul-clock-1 class="icons text-white" width="32px" height="32px"/>
        </div>

        <h4 class="font-14 font-weight-bold mt-12">{{ trans('update.meeting_time_slot') }}</h4>
    </div>

    <div class="form-group mt-24">
        <label class="form-group-label">{{ trans('update.start_time') }}</label>
        <span class="has-translation "><x-iconsax-lin-clock-1 class="icons text-gray-400" width="24px" height="24px"/></span>
        <input type="text" name="start_time" class="js-ajax-start_time form-control date-clock-picker">
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.end_time') }}</label>
        <span class="has-translation "><x-iconsax-lin-clock-1 class="icons text-gray-400" width="24px" height="24px"/></span>
        <input type="text" name="end_time" class="js-ajax-end_time form-control date-clock-picker">
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group ">
        <label class="form-group-label">{{ trans('update.end_time') }}</label>
        <select name="meeting_type" class="js-ajax-meeting_type form-control select2" data-minimum-results-for-search="Infinity">
            <option value="all">{{ trans('update.both') }}</option>
            <option value="in_person">{{ trans('update.in_person') }}</option>
            <option value="online">{{ trans('update.online') }}</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.description') }} ({{ trans('public.optional') }})</label>
        <textarea name="description" class="form-control" rows="5"></textarea>
    </div>
</form>
