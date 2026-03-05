<div class="p-16 rounded-15 border-gray-200 table-responsive-lg">
    <table class="table text-center custom-table">
        <thead>
        <tr>
            <td class="text-left font-14 font-weight-bold">{{ trans('public.day') }}</td>
            <td class="text-left font-14 font-weight-bold">{{ trans('public.availability_times') }}</td>
            <td class="text-right font-14 font-weight-bold">{{ trans('update.controls') }}</td>
        </tr>
        </thead>
        <tbody>
        @foreach(\App\Models\MeetingTime::$days as $day)
            <tr id="{{ $day }}TimeSheet" data-day="{{ $day }}">
                <td class="text-left">
                    <span class="d-block text-dark">{{ trans('panel.'.$day) }}</span>
                    <span class="d-block font-12 text-gray-500 mt-4">{{ isset($meetingTimes[$day]) ? $meetingTimes[$day]["hours_available"] : 0 }} {{ trans('home.hours') .' '. trans('public.available') }}</span>
                </td>

                <td class="time-sheet-items text-left">
                    @if(isset($meetingTimes[$day]))
                        <div class="d-flex flex-wrap gap-12">
                            @foreach($meetingTimes[$day]["times"] as $time)
                                <div class="position-relative selected-time d-inline-flex-center p-4 p-lg-8 rounded-8 bg-gray-100">
                                    <div class="inner-time text-gray-500">
                                        {{ $time->time }}
                                        <span class="mx-8">-</span>
                                        <span class="">{{ trans('update.'.($time->meeting_type == 'all' ? 'both' : $time->meeting_type)) }}</span>
                                    </div>

                                    <div class="remove-time d-flex-center size-24 bg-danger-30 rounded-4 ml-8 cursor-pointer" data-time-id="{{ $time->id }}">
                                        <x-iconsax-lin-trash class="icons text-danger" width="16px" height="16px"/>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </td>

                <td class="text-right">

                    <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                        <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                            <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                        </button>

                        <div class="actions-dropdown__dropdown-menu dropdown-menu-top-32">
                            <ul class="my-8">

                                <li class="actions-dropdown__dropdown-menu-item">
                                    <button type="button" class="js-add-time" data-modalTitle="{{ trans('update.new_time_slot') }}">{{ trans('public.add_time') }}</button>
                                </li>

                                @if(isset($meetingTimes[$day]) and !empty($meetingTimes[$day]["hours_available"]) and $meetingTimes[$day]["hours_available"] > 0)
                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <button type="button" class="js-clear-all text-danger">{{ trans('public.clear_all') }}</button>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
