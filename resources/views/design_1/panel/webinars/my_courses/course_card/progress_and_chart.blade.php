@if(!empty($nextSession) and $nextSession->date > time() and checkTimestampInToday($nextSession->date))
    <div class="js-next-session-info d-flex align-items-center cursor-pointer" data-webinar-id="{{ $course->id }}">
        <div class="d-flex-center">
            <x-iconsax-bol-video class="icons text-primary " width="24px" height="24px"/>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.today’s_live_session') }}</h5>
            <p class="font-12 text-gray-500">{{ trans('update.create_a_live_session_now') }}</p>
        </div>
    </div>
@elseif(!is_null($course->capacity))
    @php
        $percent = 0;

        $salesCount = $course->sales()->count();

        if ($salesCount > 0) {
            $percent = (!empty($course->capacity) and $course->capacity > 0) ? (($salesCount * 100) / $course->capacity) : 0;
        }
    @endphp

    @if($percent < 100)
        <div class="w-100">
            <div class="d-flex align-items-center gap-4 font-12">
                <span class="font-weight-bold text-dark">{{ round($percent,1) }}%</span>
                <span class="text-gray-500">{{ trans('update.capacity_reached') }}</span>
            </div>

            <div class="progress-bar d-flex mt-8 rounded-4 bg-gray-100 w-100">
                <span class="bg-success rounded-4" style="width: {{ $percent }}%"></span>
            </div>
        </div>
    @else
        {{-- if webinar and is finish --}}
        @if($course->isWebinar() and $course->start_date < time() and !$course->isProgressing())
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-36 bg-success rounded-circle">
                    <x-iconsax-bul-tick-circle class="icons text-white" width="36px" height="36px"/>
                </div>

                <div class="ml-8">
                    <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.course_finished!') }}</h5>
                    <p class="font-12 text-gray-500">{{ trans('update.you_did_it_perfectly...') }}</p>
                </div>
            </div>
        @else
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-36 bg-success rounded-circle">
                    <x-iconsax-bul-tick-circle class="icons text-white" width="36px" height="36px"/>
                </div>

                <div class="ml-8">
                    <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.capacity_reached') }}!</h5>
                    <p class="font-12 text-gray-500">{{ trans('update.all_seats_were_been_sold') }}</p>
                </div>
            </div>
        @endif
    @endif

@else
    {{-- if not capacity --}}
    @php
        $avgLearningPercent = $course->getAverageLearning();
    @endphp

    <div class="w-100">
        <div class="d-flex align-items-center gap-4 font-12">
            <span class="font-weight-bold text-dark">{{ $avgLearningPercent }}%</span>
            <span class="text-gray-500">{{ trans('update.av._learning_progress') }}</span>
        </div>

        <div class="progress-bar d-flex mt-8 rounded-4 bg-gray-100 w-100">
            <span class="bg-success rounded-4" style="width: {{ $avgLearningPercent }}%"></span>
        </div>
    </div>

@endif
