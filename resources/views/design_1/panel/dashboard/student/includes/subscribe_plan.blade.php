@if(!empty($activeSubscribe))
    <div class="student-dashboard__active-subscribe-card position-relative">
        <div class="student-dashboard__active-subscribe-card-bg rounded-24"></div>

        <div class="position-relative z-index-2 p-16 rounded-24 w-100 h-100">
            <h3 class="font-16 text-white">{{ $activeSubscribe->title }}</h3>
            <p class="font-12 text-white mt-8 opacity-75">{{ trans('update.active_subscription') }}</p>

            <div class="mt-16">
                <div class="d-flex align-items-center gap-4 font-12 text-white">
                    <span class="font-weight-bold">{{ $activeSubscribe->remained_days }}</span>
                    <span class="">{{ trans('update.remaining_days') }}</span>
                </div>

                <div class="progress-card d-flex bg-white mt-8">
                    <div class="progress-bar bg-primary" style="width: {{ $activeSubscribe->remained_days_percent }}%"></div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="student-dashboard__no-subscribe-card position-relative">
        <div class="student-dashboard__no-subscribe-card-bg rounded-24"></div>

        <div class="position-relative z-index-2 p-16 rounded-24">
            <h3 class="font-16 text-white">{{ trans('update.upgrade_to_pro!') }}</h3>
            <p class="font-12 text-white mt-8 opacity-75">{!! nl2br(trans('update.purchase_a_subscription_plan_and_get_courses_for_free')) !!}</p>

            <a href="/panel/financial/subscribes" target="_blank" class="d-inline-flex-center px-16 py-8 rounded-32 bg-white text-primary mt-16">{{ trans('update.upgrade') }}</a>
        </div>
    </div>
@endif
