@if($upcomingCourse->webinar_id)
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-success rounded-circle">
            <x-iconsax-bul-tick-circle class="icons text-white" width="36px" height="36px"/>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.course_published') }}!</h5>
            <p class="font-12 text-gray-500">{{ trans('update.you_did_it_perfectly...') }}</p>
        </div>
    </div>
@else
    @php
        $percent = !empty($upcomingCourse->course_progress) ? $upcomingCourse->course_progress : 0;
    @endphp

    <div class="d-flex align-items-center">
        <div class="js-course-chart d-flex-center size-48" data-id="courseChart_{{ $upcomingCourse->id }}" data-percent="{{ $percent }}">
            <canvas id="courseChart_{{ $upcomingCourse->id }}" width="48" height="48"></canvas>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark">{{ round($percent,1) }}%</h5>
            <p class="font-12 text-gray-500">{{ trans('update.course_progress') }}</p>
        </div>
    </div>
@endif
