@php
    $canSale = ($bundle->canSale() and !$hasBought);
@endphp

<div class="js-enroll-actions-card mt-20 d-flex flex-column px-16">
    @if($hasBought or !empty($bundle->getInstallmentOrder()))
        <button type="button" class="btn btn-block btn-primary btn-lg disabled" disabled="disabled">{{ trans('panel.purchased') }}</button>
    @elseif(!isFreeModeEnabled() and !empty($bundle->price) and $bundle->price > 0)
        <button type="button" class="btn btn-block btn-primary btn-lg {{ $canSale ? 'js-course-add-to-cart-btn' : ' disabled ' }}">
            @if(!$canSale)
                {{ trans('update.disabled_add_to_cart') }}
            @else
                {{ trans('public.add_to_cart') }}
            @endif
        </button>

        @if(!isFreeModeEnabled() and $canSale and !empty($bundle->points))
            <a href="{{ !(auth()->check()) ? '/login' : '#' }}" class="{{ (auth()->check()) ? 'js-buy-with-point' : '' }} btn btn-outline-warning btn-block btn-lg mt-14 {{ (!$canSale) ? 'disabled' : '' }}" rel="nofollow" data-path="/bundles/{{ $bundle->slug }}/points/get-modal">
                {!! trans('update.buy_with_n_points',['points' => $bundle->points]) !!}
            </a>
        @endif

        @if(!isFreeModeEnabled() and $canSale and !empty(getFeaturesSettings('direct_bundles_payment_button_status')))
            <button type="button" class="btn btn-outline-accent btn-block btn-lg mt-14 js-bundle-direct-payment">
                {{ trans('update.buy_now') }}
            </button>
        @endif

    @else
        <a href="{{ $canSale ? ('/bundles/'. $bundle->slug .'/free') : '#' }}" class="btn btn-primary btn-block btn-lg {{ (!$canSale) ? 'disabled' : '' }}">
            {{ trans('update.enroll_on_bundle') }}
        </a>
    @endif

    @if(!isFreeModeEnabled() and $canSale and $bundle->canUseSubscribe())
        <a href="/subscribes/apply/bundle/{{ $bundle->slug }}" class="btn btn-outline-accent btn-block btn-lg mt-14 @if(!$canSale) disabled @endif">{{ trans('public.subscribe') }}</a>
    @endif

</div>
