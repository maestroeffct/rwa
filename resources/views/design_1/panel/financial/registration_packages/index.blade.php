@extends('design_1.panel.layouts.panel')

@section('content')
    @if($activePackage)
        <div class="bg-white p-16 rounded-24">
            <h3 class="font-14 font-weight-bold">{{ trans('update.active_package') }}</h3>

            <div class="row align-items-center mt-16">

                {{-- Name --}}
                <div class="col-6 col-lg-3 d-flex align-items-center border-right-gray-200">
                    @if(!empty($activePackage->icon))
                        <div class="d-flex-center size-68">
                            <img src="{{ $activePackage->icon }}" alt="" class="img-fluid rounded-circle">
                        </div>
                    @endif

                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">{{ $activePackage->title }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('financial.active_plan') }}</p>
                    </div>
                </div>

                <div class="col-6 col-lg-2 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-timer class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">{{ $activePackage->days_remained ?? trans('update.unlimited') }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('financial.days_remained') }}</p>
                    </div>
                </div>

                <div class="col-6 col-lg-2 mt-16 mt-lg-0 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-calendar-tick class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">{{ !empty($activePackage->activation_date) ? dateTimeFormat($activePackage->activation_date , 'j M Y') : '-' }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.activation_date') }}</p>
                    </div>
                </div>

                <div class="col-6 col-lg-2 mt-16 mt-lg-0 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-calendar-tick class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">{{ (!empty($activePackage->activation_date) and !empty($activePackage->days)) ? dateTimeFormat($activePackage->activation_date + $activePackage->days * 86400 , 'j M Y') : trans('update.unlimited') }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.expiry_date') }}</p>
                    </div>
                </div>

                <div class="col-6 col-lg-3 mt-16 mt-lg-0 d-flex align-items-center border-right-gray-200">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-box class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16 font-weight-bold">
                            @if(!empty($activePackage->days))
                                {{ $activePackage->days }} {{ trans('public.days') }}
                            @else
                                {{ trans('update.unlimited') }}
                            @endif
                        </h5>

                        <p class="text-gray-500 mt-8">{{ trans('update.package_duration') }}</p>
                    </div>
                </div>

            </div>


            <div class="mt-16 pt-16 border-top-gray-100">
                <h4 class="font-14 font-weight-bold">{{ trans('update.plan_statistics') }}</h4>

                <div class="d-grid grid-columns-1 grid-lg-columns-3 gap-16 mt-16">
                    <div class="bg-white p-16 rounded-16 border-gray-200">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('product.course') }}</span>

                            <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                                <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>

                        <h5 class="font-20 mt-12 line-height-1">
                            @if(!empty($activePackage) and isset($activePackage->courses_count))
                                {{ $accountStatistics['myCoursesCount'] }}/{{ $activePackage->courses_count }}
                            @else
                                {{ trans('update.unlimited') }}
                            @endif
                        </h5>
                    </div>

                    <div class="bg-white p-16 rounded-16 border-gray-200">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('update.live_students') }}</span>

                            <div class="size-48 d-flex-center bg-danger-30 rounded-12">
                                <x-iconsax-bul-video class="icons text-danger" width="24px" height="24px"/>
                            </div>
                        </div>

                        <h5 class="font-20 mt-12 line-height-1">
                            @if(!empty($activePackage) and isset($activePackage->courses_capacity))
                                {{ $activePackage->courses_capacity }}
                            @else
                                {{ trans('update.unlimited') }}
                            @endif
                        </h5>
                    </div>

                    <div class="bg-white p-16 rounded-16 border-gray-200">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('update.meeting_hours') }}</span>

                            <div class="size-48 d-flex-center bg-success-30 rounded-12">
                                <x-iconsax-bul-clock class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>

                        <h5 class="font-20 mt-12 line-height-1">
                            @if(!empty($activePackage) and isset($activePackage->meeting_count))
                                {{ $accountStatistics['myMeetingCount'] }}/{{ $activePackage->meeting_count }}
                            @else
                                {{ trans('update.unlimited') }}
                            @endif
                        </h5>
                    </div>

                    <div class="bg-white p-16 rounded-16 border-gray-200">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('update.products') }}</span>

                            <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                                <x-iconsax-bul-box class="icons text-warning" width="24px" height="24px"/>
                            </div>
                        </div>

                        <h5 class="font-20 mt-12 line-height-1">
                            @if(!empty($activePackage) and isset($activePackage->product_count))
                                {{ $accountStatistics['myProductCount'] }}/{{ $activePackage->product_count }}
                            @else
                                {{ trans('update.unlimited') }}
                            @endif
                        </h5>
                    </div>

                    <div class="bg-white p-16 rounded-16 border-gray-200">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('update.events') }}</span>

                            <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                                <x-iconsax-bul-ticket class="icons text-warning" width="24px" height="24px"/>
                            </div>
                        </div>

                        <h5 class="font-24 mt-12 line-height-1">
                            @if(!empty($activePackage) and isset($activePackage->events_count))
                                {{ $accountStatistics['myEventsCount'] }}/{{ $activePackage->events_count }}
                            @else
                                {{ trans('update.unlimited') }}
                            @endif
                        </h5>
                    </div>


                    <div class="bg-white p-16 rounded-16 border-gray-200">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('update.meeting_packages') }}</span>

                            <div class="size-48 d-flex-center bg-accent-30 rounded-12">
                                <x-iconsax-bul-box-time class="icons text-accent" width="24px" height="24px"/>
                            </div>
                        </div>

                        <h5 class="font-24 mt-12 line-height-1">
                            @if(!empty($activePackage) and isset($activePackage->meeting_packages_count))
                                {{ $accountStatistics['myMeetingPackagesCount'] }}/{{ $activePackage->meeting_packages_count }}
                            @else
                                {{ trans('update.unlimited') }}
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="position-relative d-flex align-items-center justify-content-between bg-white p-16 rounded-16 mt-28">
            <div class="">
                <h3 class="font-14 font-weight-bold">{{ trans('update.no_service_package') }}</h3>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.no_service_package_hint') }}</p>
            </div>

            <div class="no-plan-icon">
                <img src="/assets/design_1/img/panel/no_subscription_plan.png" alt="{{ trans('update.no_service_package') }}" class="img-cover">
            </div>
        </div>
    @endif

    {{-- Packages --}}

    <h4 class="font-16 font-weight-bold mt-24">{{ trans('update.select_a_plan') }}</h4>

    <div class="row">
        @foreach($packages as $package)
            @php
                $specialOffer = $package->activeSpecialOffer();
            @endphp

            <div class="col-12 col-lg-3 mt-16">
                <div class="subscribe-plan bg-white p-16 rounded-16">

                    @if(!empty($activePackage) and $activePackage->package_id == $package->id)
                        <span class="badge-popular bg-primary-40 text-primary px-8 py-4 rounded-8">{{ trans('update.activated') }}</span>
                    @elseif(!empty($specialOffer))
                        <span class="badge-popular bg-danger-40 text-danger px-8 py-4 rounded-8">{{ trans('update.percent_off', ['percent' => $specialOffer->percent]) }}</span>
                    @endif

                    <div class="d-flex-center">
                        <div class="size-100">
                            <img src="{{ $package->icon }}" alt="" class="img-fluid rounded-circle">
                        </div>
                    </div>

                    <h3 class="font-16 font-weight-bold mt-16">{{ $package->title }}</h3>
                    <p class="font-12 text-gray-500 mt-4">{{ $package->description }}</p>

                    <div class="mt-20">
                        @if(!empty($package->price) and $package->price > 0)
                            @if(!empty($specialOffer))
                                <div class="d-flex align-items-end line-height-1">
                                    <span class="font-44 font-weight-bold text-dark">{{ handlePrice($package->getPrice(), true, true, false, null, true) }}</span>
                                    <span class="font-14 text-gray-500 ml-4 text-decoration-line-through">{{ handlePrice($package->price, true, true, false, null, true) }}</span>
                                </div>
                            @else
                                <span class="font-44 font-weight-bold text-dark">{{ handlePrice($package->price, true, true, false, null, true) }}</span>
                            @endif
                        @else
                            <span class="font-44 font-weight-bold text-dark">{{ trans('public.free') }}</span>
                        @endif
                    </div>


                    @php
                        $items = [
                             ['name' => trans('public.days'), 'value' => $package->days],
                             ['name' => trans('product.courses'), 'value' => $package->courses_count],
                             ['name' => trans('update.live_students'), 'value' => $package->courses_capacity],
                             ['name' => trans('update.meeting_hours'), 'value' => $package->instructors_count],
                             ['name' => trans('update.products'), 'value' => $package->product_count],
                             ['name' => trans('update.events'), 'value' => $package->events_count],
                             ['name' => trans('update.meeting_packages'), 'value' => $package->meeting_packages_count],
                        ];
                        if($authUser->isOrganization()) {
                            $items[] = ['name' => trans('home.instructors'), 'value' => $package->instructors_count];
                            $items[] = ['name' => trans('public.students'), 'value' => $package->students_count];
                        }
                    @endphp

                    <div class="mt-28 plan-feature">
                        @foreach($items as $item)
                            <div class="d-flex align-items-center {{ !$loop->first ? 'mt-12' : '' }}">
                                <x-tick-icon class="icons {{ ($item['value'] == '0') ? 'text-gray-400' : 'text-success' }}" width="16px" height="16px"/>
                                <span class="mx-4 font-weight-bold">{{ is_null($item['value']) ? trans('update.unlimited') : ($item['value'] > 0 ? $item['value'] : trans("update.no")) }}</span>
                                <span class="text-gray-500">{{ $item['name'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('payRegistrationPackage') }}" method="post" class="w-100 mt-32">
                        {{ csrf_field() }}
                        <input name="id" value="{{ $package->id }}" type="hidden">

                        <div class="d-flex align-items-center w-100">
                            <button type="submit" class="btn btn-sm btn-primary flex-grow-1 {{ !empty($package->has_installment) ? '' : 'btn-block' }}">{{ trans('update.upgrade') }}</button>

                            @if(!empty($package->has_installment))
                                <a href="/panel/financial/registration-packages/{{ $package->id }}/installments" class="btn btn-sm btn-outline-primary flex-grow-1 ml-12">{{ trans('update.installments') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/design_1/js/panel/subscribes.min.js"></script>
@endpush
