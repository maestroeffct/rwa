@if(!empty($registrationPlan))
    <div class="instructor-dashboard__active-registration-card position-relative">
        <div class="instructor-dashboard__active-registration-card-bg rounded-24"></div>

        <div class="position-relative z-index-2 p-16 rounded-24 w-100 h-100">
            <h3 class="font-16 text-white">{{ $registrationPlan->title }}</h3>
            <p class="font-12 text-white mt-8 opacity-75">{{ trans('update.active_service_package') }}</p>

            <div class="mt-16">
                <div class="d-flex align-items-center gap-4 font-12 text-white">
                    <span class="font-weight-bold">{{ $registrationPlan->days_remained ?? trans('update.unlimited') }}</span>
                    <span class="">{{ trans('update.remaining_days') }}</span>
                </div>

                <div class="progress-card d-flex bg-white mt-8">
                    <div class="progress-bar bg-primary" style="width: {{ $registrationPlan->remained_days_percent ?? 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="instructor-dashboard__no-registration-card position-relative">
        <div class="instructor-dashboard__no-registration-card-bg rounded-24"></div>

        <div class="position-relative z-index-2 p-16 rounded-24">
            <h3 class="font-16 text-white">{{ trans('update.upgrade_to_pro!') }}</h3>
            <p class="font-12 text-white mt-8 opacity-75">{!! nl2br(trans('update.purchase_a_subscription_plan_and_get_courses_for_free')) !!}</p>

            <a href="/panel/financial/registration-packages" target="_blank" class="d-inline-flex-center px-16 py-8 rounded-32 bg-white text-primary mt-16">{{ trans('update.upgrade') }}</a>
        </div>
    </div>
@endif
