@extends('design_1.panel.layouts.panel')

@section('content')
    @if($activeSubscribe)
        <div class="bg-white p-16 rounded-24">
            <h3 class="font-14 font-weight-bold">{{ trans('update.active_package') }}</h3>

            <div class="row align-items-center mt-16">

                {{-- Name --}}
                <div class="col-6 col-md-2 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-68">
                        <img src="{{ $activeSubscribe->icon }}" alt="" class="img-fluid rounded-circle">
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">{{ $activeSubscribe->title }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('financial.active_plan') }}</p>
                    </div>
                </div>

                <div class="col-6 col-md-2 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-timer class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">{{ $activeSubscribe->days - $dayOfUse }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('financial.days_remained') }}</p>
                    </div>
                </div>

                <div class="col-6 col-md-2 mt-16 mt-lg-0 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-calendar-tick class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">{{ !empty($activeSubscribe->saleCreatedAt) ? dateTimeFormat($activeSubscribe->saleCreatedAt , 'j M Y') : '-' }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.activation_date') }}</p>
                    </div>
                </div>

                <div class="col-6 col-md-2 mt-16 mt-lg-0 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-calendar-tick class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">{{ dateTimeFormat($activeSubscribe->saleCreatedAt + $activeSubscribe->days * 86400 , 'j M Y') }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.expiry_date') }}</p>
                    </div>
                </div>

                <div class="col-6 col-md-2 mt-16 mt-lg-0 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-box class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">{{ $activeSubscribe->days }} {{ trans('public.days') }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.package_duration') }}</p>
                    </div>
                </div>

                <div class="col-6 col-md-2 mt-16 mt-lg-0 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-import class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">
                            @if($activeSubscribe->infinite_use)
                                {{ trans('update.unlimited') }}
                            @else
                                {{ $activeSubscribe->usable_count - $activeSubscribe->used_count }}
                            @endif
                        </h5>

                        <p class="text-gray-500 mt-8">{{ trans('financial.remained_downloads') }}</p>
                    </div>
                </div>

            </div>
        </div>
    @else
        <div class="position-relative d-flex align-items-center justify-content-between bg-white p-16 rounded-16 mt-28">
            <div class="">
                <h3 class="font-14 font-weight-bold">{{ trans('update.no_subscription_plan!') }}</h3>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.no_subscription_plan_hint') }}</p>
            </div>

            <div class="no-plan-icon">
                <img src="/assets/design_1/img/panel/no_subscription_plan.png" alt="{{ trans('update.no_subscription_plan!') }}" class="img-cover">
            </div>
        </div>
    @endif

    {{-- Packages --}}

    <h4 class="font-16 font-weight-bold mt-24">{{ trans('update.select_a_plan') }}</h4>

    <div class="row">
        @foreach($subscribes as $subscribe)
            <div class="col-12 col-lg-3 mt-16">
                @include('design_1.panel.financial.subscribes.plan_card')
            </div>
        @endforeach
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/design_1/js/panel/subscribes.min.js"></script>
@endpush
