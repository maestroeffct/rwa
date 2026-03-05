<div class="d-flex-center flex-column text-center mt-16 mb-24">
    @if($haveEnoughPoints)
        <div class="size-136">
            <img src="/assets/design_1/img/courses/has_points.svg" alt="" class="img-cover">
        </div>

        <h6 class="font-12 font-weight-bold mt-4">{{ trans('update.you_have_enough_points') }}</h6>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.do_you_want_to_purchase_this_course_using_your_points') }}</p>
    @else
        <div class="size-136">
            <img src="/assets/design_1/img/courses/no_points.svg" alt="" class="img-cover">
        </div>

        <h6 class="font-12 font-weight-bold mt-4">{{ trans('update.you_have_no_enough_points') }}</h6>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.you_can_not_purchase_this_course_by_your_available_points') }}</p>
    @endif

    <div class="position-relative buy-with-point-modal-available-points mt-16">
        <div class="buy-with-point-modal-available-points-mask"></div>
        <div class="position-relative d-flex align-items-center justify-content-between bg-white p-16 mt-16 rounded-24 border-gray-200 w-100 h-100 z-index-2">
            <div class="d-flex-center flex-column text-center">
                <span class="font-24 font-weight-bold">{{ $course->points }}</span>
                <span class="font-12 text-gray-500 mt-4">{{ trans('update.required_points') }}</span>
            </div>

            <div class="d-flex-center size-40 rounded-circle border-gray-200">
                <x-iconsax-lin-star-1 class="icons text-primary" width="20px" height="20px"/>
            </div>

            <div class="d-flex-center flex-column text-center">
                <span class="font-24 font-weight-bold">{{ $availablePoints }}</span>
                <span class="font-12 text-gray-500 mt-4">{{ trans('update.available_points') }}</span>
            </div>
        </div>
    </div>

</div>
