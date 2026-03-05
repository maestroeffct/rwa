@extends('design_1.web.layouts.app', ['appFooter' => false])

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("registration-packages") }}">
@endpush

@section("content")
    <div class="container mt-56 mb-120">
        <div class="d-flex-center flex-column text-center">
            <h1 class="font-32 font-weight-bold">{{ trans('update.registration_packages') }}</h1>
            <p class="mt-8 font-16 text-gray-500">{{ trans('update.select_registration_package_hint') }}</p>

            @if(empty($becomeInstructor))
                <a href="/become-instructor" class="btn btn-primary btn-lg mt-16">{{ trans('site.become_instructor') }}</a>
            @endif
        </div>

        @if(!empty($defaultPackage))
            @include("design_1.web.become_instructor.packages.current_package", ['currentPackage' => $defaultPackage])
        @endif

        <form action="{{ route('payRegistrationPackage') }}" method="post">
            {{ csrf_field() }}

            @if(!empty($becomeInstructor))
                <input type="hidden" name="become_instructor_id" value="{{ $becomeInstructor->id }}"/>
            @endif

            <div class="row">
                @foreach($packages as $package)
                    @php
                        $specialOffer = $package->activeSpecialOffer();
                    @endphp
                    <div class="col-12 col-md-6 col-lg-3 mt-24 {{ !empty($becomeInstructor) ? 'registration-package-radio' : '' }}">
                        @if(!empty($becomeInstructor))
                            <input type="radio" name="id" class="js-registration-package-input" id="package{{ $package->id }}" value="{{ $package->id }}">
                        @endif

                        <label for="package{{ $package->id }}" class="position-relative">
                            <div class="card-mask"></div>

                            @if(!empty($activePackage) and $activePackage->package_id == $package->id)
                                <span class="registration-package-badge-popular d-inline-flex-center rounded-12 px-12 py-8 bg-primary text-white">{{ trans('update.activated') }}</span>
                            @elseif(!empty($specialOffer))
                                <span class="registration-package-badge-popular d-inline-flex-center rounded-12 px-12 py-8 bg-danger text-white">{{ trans('update.percent_off', ['percent' => $specialOffer->percent]) }}</span>
                            @endif

                            <div class="registration-package-label-card position-relative bg-white p-16 rounded-16  z-index-2">
                                <div class="registration-package-card__icon position-relative d-flex-center mt-32">
                                    <div class="position-relative d-flex-center size-68 rounded-circle bg-primary">
                                        <img src="{{ $package->icon }}" class="img-cover rounded-circle" alt="">
                                    </div>
                                </div>

                                <h3 class="mt-32 font-16 font-weight-bold">{{ $package->title }}</h3>
                                <p class="font-12 text-gray-500 mt-4">{{ $package->description }}</p>

                                <div class="d-flex align-items-start mt-20">
                                    @if(!empty($package->price) and $package->price > 0)
                                        @if(!empty($specialOffer))
                                            <div class="d-flex align-items-end">
                                                <span class="font-44 font-weight-bold">{{ handlePrice($package->getPrice(), true, true, false, null, true) }}</span>
                                                <span class="font-14 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($package->price, true, true, false, null, true) }}</span>
                                            </div>
                                        @else
                                            <span class="font-44 font-weight-bold">{{ handlePrice($package->price, true, true, false, null, true) }}</span>
                                        @endif
                                    @else
                                        <span class="font-44 font-weight-bold">{{ trans('public.free') }}</span>
                                    @endif
                                </div>

                                @php
                                    $items = [
                                         ['name' => trans('public.days'), 'value' => $package->days],
                                         ['name' => trans('product.courses'), 'value' => $package->courses_count],
                                         ['name' => trans('update.live_students'), 'value' => $package->courses_capacity],
                                         ['name' => trans('update.meeting_hours'), 'value' => $package->instructors_count],
                                         ['name' => trans('update.products'), 'value' => $package->product_count],
                                    ];
                                    if($selectedRole == 'organizations') {
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

                            </div>
                        </label>
                    </div>
                @endforeach

            </div>

            @if(!empty($becomeInstructor))
                <div class="fixed-bottom z-index-15 w-100 bg-white">
                    <div class="container d-flex align-items-center justify-content-between py-16">
                        <a href="/become-instructor" class="btn btn-lg bg-gray-300">{{ trans('update.back') }}</a>

                        <div class="">
                            @if(!getRegistrationPackagesGeneralSettings('force_user_to_select_a_package'))
                                <a href="/panel" class="btn btn-lg btn-outline-primary mr-12">{{ trans('update.skip') }}</a>
                            @endif

                            <a href="" class="js-installment-btn d-none btn btn-primary btn-lg">
                                {{ trans('update.pay_with_installments') }}
                            </a>

                            <button type="submit" id="paymentSubmit" disabled class="btn btn-lg btn-primary">{{ trans('cart.checkout') }}</button>
                        </div>
                    </div>
                </div>
            @endif

        </form>
    </div>
@endsection

@push("scripts_bottom")

    <script src="{{ getDesign1ScriptPath("registration_packages") }}"></script>
@endpush
