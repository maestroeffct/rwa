<div class="registration-current-package position-relative mt-24 mb-32">
    <div class="registration-current-package__mask"></div>

    @if(!empty(getRegistrationPackagesGeneralSettings("right_float_image")))
        <div class="registration-current-package__right-float-image">
            <img src="{{ getRegistrationPackagesGeneralSettings("right_float_image") }}" alt="{{ trans('update.registration_plans_overview') }}" class="img-cover">
        </div>
    @endif

    <div class="position-relative bg-primary p-4 rounded-16 z-index-2">
        <div class="px-4 py-8">
            <h4 class="font-14 font-weight-bold text-white">{{ trans('update.registration_plans_overview') }}</h4>
        </div>

        <div class="position-relative bg-white rounded-12 p-16 mt-8 ">

            <div class="d-flex align-items-center">
                <div class="position-relative">
                    <div class="registration-current-package__cup-icon-mask"></div>
                    <div class="position-relative d-flex-center size-40 rounded-circle bg-primary z-index-2">
                        <x-iconsax-bul-cup class="icons text-white" width="24px" height="24px"/>
                    </div>
                </div>
                <div class="ml-16">
                    <h5 class="font-14 font-weight-bold">{{ trans('update.default_package') }}</h5>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.default_package_hint') }}</p>
                </div>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-64 gap-lg-120 mt-24 pt-16 border-top-gray-200">

                {{-- Courses --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-video-play class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('product.courses') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ $defaultPackage->courses_count ?? trans('update.unlimited') }}</span>
                    </div>
                </div>

                {{-- Meeting Hours --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-video class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.meeting_hours') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ $defaultPackage->meeting_count ?? trans('update.unlimited') }}</span>
                    </div>
                </div>

                {{-- Live Courses Capacity --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-profile-2user class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.live_students') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ $defaultPackage->courses_capacity ?? trans('update.unlimited') }}</span>
                    </div>
                </div>

                {{-- products --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-box class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.products') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ $defaultPackage->product_count ?? trans('update.unlimited') }}</span>
                    </div>
                </div>

                @if($selectedRole == 'organizations')
                    {{-- Organization Instructors --}}
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                            <x-iconsax-lin-briefcase class="icons text-gray-500" width="20px" height="20px"/>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-12 text-gray-400">{{ trans('home.instructors') }}</span>
                            <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ $defaultPackage->instructors_count ?? trans('update.unlimited') }}</span>
                        </div>
                    </div>

                    {{-- Organization Students --}}
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                            <x-iconsax-lin-teacher class="icons text-gray-500" width="20px" height="20px"/>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-12 text-gray-400">{{ trans('public.students') }}</span>
                            <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ $defaultPackage->students_count ?? trans('update.unlimited') }}</span>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
