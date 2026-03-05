<form class="js-meeting-package-session-time-form" action="{{ getAdminPanelUrl("/meeting-packages/sold/{$meetingPackageSold->id}/sessions/{$session->id}/set-date") }}" method="post">

    <div class="d-flex-center flex-column text-center">
        <div class="d-flex-center size-64 rounded-16 bg-info-gradient">
            <x-iconsax-bul-clock-1 class="icons text-white" width="32px" height="32px"/>
        </div>

        <h4 class="font-14 font-weight-bold mt-12">{{ trans('update.meeting_date_and_time') }}</h4>
    </div>

    <div class="form-group mt-24">
        <label class="form-group-label">{{ trans('update.meeting_date') }}</label>
        <span class="has-translation "><x-iconsax-lin-calendar-2 class="icons text-gray-400" width="24px" height="24px"/></span>
        <input type="text" name="date" class="js-ajax-date form-control datetimepicker" data-format="YYYY/MM/DD HH:mm" autocomplete="off" value="{{ (!empty($session->date)) ? dateTimeFormat($session->date, 'Y-m-d H:i', false) : '' }}">
        <div class="invalid-feedback"></div>
        <div class="font-12 text-gray-500 mt-8">{{ trans('update.select_meeting_start_date_and_clock') }}</div>
    </div>

</form>
