<form class="js-finish-meeting-reserve-form" action="/panel/meetings/{{ $ReserveMeeting->id }}/finish" method="post">
    {{ csrf_field() }}

    <div class="d-flex-center flex-column text-center my-16">
        <div class="">
            <img src="/assets/design_1/img/panel/meeting/finish_modal.svg" alt="" class="img-fluid" width="215px" height="160px">
        </div>

        <h4 class="font-14 mt-12">{{ trans('update.are_you_sure_to_finish_the_meeting?') }}</h4>
        <p class="font-12 mt-8 text-gray-500">{!! nl2br(trans('update.are_you_sure_to_finish_the_meeting_modal_hint')) !!}</p>
    </div>
</form>
