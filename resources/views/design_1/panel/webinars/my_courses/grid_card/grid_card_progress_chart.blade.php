@if(!is_null($course->capacity))
    @php
        $percent = 0;

        $salesCount = $course->sales()->count();

        if ($salesCount > 0) {
            $percent = (!empty($course->capacity) and $course->capacity > 0) ? (($salesCount * 100) / $course->capacity) : 0;
        }
    @endphp

    @if($percent < 100)
        <div class="d-flex align-items-center">
            <div class="js-course-chart d-flex-center size-48" data-id="courseChart_{{ $course->id }}" data-percent="{{ $percent }}">
                <canvas id="courseChart_{{ $course->id }}" width="48" height="48"></canvas>
            </div>

            <div class="ml-8">
                <h5 class="font-12 font-weight-bold">{{ round($percent,1) }}%</h5>
                <p class="font-12 text-gray-500">{{ trans('update.capacity_reached') }}</p>
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
                    <h5 class="font-12 font-weight-bold">{{ trans('update.course_finished!') }}</h5>
                    <p class="font-12 text-gray-500">{{ trans('update.you_did_it_perfectly...') }}</p>
                </div>
            </div>
        @else
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-36 bg-success rounded-circle">
                    <x-iconsax-bul-tick-circle class="icons text-white" width="36px" height="36px"/>
                </div>

                <div class="ml-8">
                    <h5 class="font-12 font-weight-bold">{{ trans('update.capacity_reached') }}!</h5>
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

    <div class="d-flex align-items-center">
        <div class="js-course-chart d-flex-center size-48" data-id="courseChart_{{ $course->id }}" data-percent="{{ $avgLearningPercent }}">
            <canvas id="courseChart_{{ $course->id }}" width="48" height="48"></canvas>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold">{{ $avgLearningPercent }}%</h5>
            <p class="font-12 text-gray-500">{{ trans('update.av._learning_progress') }}</p>
        </div>
    </div>

@endif
