@php
    $canSale = ($course->canSale() and !$hasBought);
    $authUserJoinedWaitlist = false;

    if (!empty($authUser)) {
        $authUserWaitlist = $course->waitlists()->where('user_id', $authUser->id)->first();
        $authUserJoinedWaitlist = !empty($authUserWaitlist);
    }
@endphp

<div class="js-enroll-actions-card mt-20 d-flex flex-column px-16">
    @if(!$canSale and $course->canJoinToWaitlist())
        <button type="button" class="btn btn-block btn-primary btn-lg {{ (!$authUserJoinedWaitlist) ? 'js-join-waitlist-user' : 'disabled' }}" {{ $authUserJoinedWaitlist ? 'disabled' : '' }} data-path="/course/{{ $course->slug }}/waitlists/get-modal">
            @if($authUserJoinedWaitlist)
                {{ trans('update.already_joined') }}
            @else
                {{ trans('update.join_waitlist') }}
            @endif
        </button>
    @elseif($hasBought or !empty($course->getInstallmentOrder()))
        <a href="{{ $course->getLearningPageUrl() }}" class="btn btn-block btn-primary btn-lg">{{ trans('update.go_to_learning_page') }}</a>
    @elseif(!isFreeModeEnabled() and !empty($course->price) and $course->price > 0)
        <button type="button" class="btn btn-block btn-primary btn-lg {{ $canSale ? 'js-course-add-to-cart-btn' : ($course->cantSaleStatus($hasBought) .' disabled ') }}">
            @if(!$canSale)
                @if($course->checkCapacityReached())
                    {{ trans('update.capacity_reached') }}
                @else
                    {{ trans('update.disabled_add_to_cart') }}
                @endif
            @else
                {{ trans('public.add_to_cart') }}
            @endif
        </button>

        @if($canSale and !empty($course->points))
            <a href="{{ !(auth()->check()) ? '/login' : '#' }}" class="{{ (auth()->check()) ? 'js-buy-with-point' : '' }} btn btn-outline-warning btn-block btn-lg mt-14 {{ (!$canSale) ? 'disabled' : '' }}" rel="nofollow" data-path="/course/{{ $course->slug }}/points/get-modal">
                {!! trans('update.buy_with_n_points',['points' => $course->points]) !!}
            </a>
        @endif

        @if(!isFreeModeEnabled() and $canSale and !empty(getFeaturesSettings('direct_classes_payment_button_status')))
            <button type="button" class="btn btn-outline-accent btn-block btn-lg mt-14 js-course-direct-payment">
                {{ trans('update.buy_now') }}
            </button>
        @endif

        @if(!isFreeModeEnabled() and !empty($installments) and count($installments) and getInstallmentsSettings('display_installment_button'))
            <a href="/course/{{ $course->slug }}/installments" class="btn btn-outline-primary btn-block btn-lg mt-14">
                {{ trans('update.pay_with_installments') }}
            </a>
        @endif
    @else
        <a href="{{ $canSale ? '/course/'. $course->slug .'/free' : '#' }}" class="btn btn-primary btn-block btn-lg {{ (!$canSale) ? (' disabled ' . $course->cantSaleStatus($hasBought)) : '' }}">
            @if(!$canSale)
                @if($course->checkCapacityReached())
                    {{ trans('update.capacity_reached') }}
                @else
                    {{ trans('public.disabled') }}
                @endif
            @else
                {{ trans('public.enroll_on_webinar') }}
            @endif
        </a>
    @endif

    @if(!isFreeModeEnabled() and $canSale and $course->canUseSubscribe())
        <a href="/subscribes/apply/{{ $course->slug }}" class="btn btn-outline-accent btn-block btn-lg mt-14 @if(!$canSale) disabled @endif">{{ trans('public.subscribe') }}</a>
    @endif

</div>
