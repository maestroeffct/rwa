@php
    $subscribeSpecialOffer = $subscribe->activeSpecialOffer();
@endphp

<div class="subscription-plan-card position-relative h-100">
    <div class="subscription-plan-card__mask"></div>

    <div class="position-relative z-index-2 d-flex flex-column bg-white rounded-24 p-16 pt-32 w-100 h-100">

        {{-- Popular --}}
        @if($subscribe->is_popular)
            <div class="subscription-plan-card__popular-badge d-inline-flex-center gap-4 p-4 pr-8 rounded-32 bg-primary fon12 text-white">
                <x-iconsax-bul-verify class="icons text-white" width="20px" height="20px"/>
                <span class="font-12">{{ trans('panel.popular') }}</span>
            </div>
        @endif

        <div class="d-flex-center size-100 bg-gray-100 rounded-circle mx-auto">
            <div class="d-flex-center size-68 rounded-circle">
                <img src="{{ $subscribe->icon }}" alt="{{ $subscribe->title }}" class="img-cover rounded-circle">
            </div>
        </div>

        <h3 class="font-16 font-weight-bold mt-16">{{ $subscribe->title }}</h3>
        <p class="font-12 text-gray-500 mt-4">{{ $subscribe->subtitle }}</p>

        <div class="mt-20">
            @if(!empty($subscribe->price) and $subscribe->price > 0)
                @if(!empty($subscribeSpecialOffer))
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-end line-height-1">
                            <span class="font-44 font-weight-bold text-dark">{{ handlePrice($subscribe->getPrice(), true, true, false, null, true) }}</span>
                            <span class="font-14 text-gray-500 ml-12 text-decoration-line-through">{{ handlePrice($subscribe->price, true, true, false, null, true) }}</span>
                        </div>

                        <div class="d-flex align-items-center px-8 py-4 rounded-32 bg-accent  font-12 text-white">{{ trans('update.percent_off', ['percent' => $subscribeSpecialOffer->percent]) }}</div>
                    </div>
                @else
                    <span class="font-44 font-weight-bold text-dark">{{ handlePrice($subscribe->price, true, true, false, null, true) }}</span>
                @endif
            @else
                <span class="font-44 font-weight-bold text-dark">{{ trans('public.free') }}</span>
            @endif
        </div>

        @if(!empty($subscribe->description))
            <div class="mt-16 p-12 rounded-16 bg-gray-100 font-12 text-gray-500">{!! nl2br(truncate($subscribe->description, 120)) !!}</div>
        @endif

        <ul class="my-16">
            <li class="d-flex align-items-center">
                <x-tick-icon class="icons text-success" width="16" height="16"/>
                <div class="ml-4 text-gray-500">{{ $subscribe->days }} {{ trans('financial.days_of_subscription') }}</div>
            </li>

            <li class="d-flex align-items-center mt-12">
                <x-tick-icon class="icons text-success" width="16" height="16"/>
                <div class="ml-4 text-gray-500">
                    @if($subscribe->infinite_use)
                        {{ trans('update.unlimited') }}
                    @else
                        {{ $subscribe->usable_count }}
                    @endif

                    <span class="ml-4">{{ trans('update.subscribes') }}</span>
                </div>
            </li>
        </ul>

        @php
            $subscribeHasInstallment = $subscribe->hasInstallment();
        @endphp

        <div class="d-flex align-items-center gap-8 w-100">
            <a href="/subscribes/{{ $subscribe->id }}/details" target="_blank" class="btn btn-primary btn-lg flex-1 {{ $subscribeHasInstallment ? '' : 'btn-block' }}">{{ trans('update.purchase') }}</a>

            @if($subscribeHasInstallment)
                <a href="/panel/financial/subscribes/{{ $subscribe->id }}/installments" class="d-flex-center size-48 rounded-12 border-2 border-gray-400 bg-white" data-tippy-content="{{ trans('update.installments') }}">
                    <x-iconsax-lin-moneys class="icons text-gray-500" width="24px" height="24px"/>
                </a>
            @endif
        </div>

    </div>
</div>
