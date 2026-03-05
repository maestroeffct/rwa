@if(!empty($mostActiveCourses) and count($mostActiveCourses))
    <div class="mt-28">
        <div class="">
            <h2 class="font-16 text-dark">{{ trans('update.most_active_courses') }}</h2>
            <p class="font-12 mt-4 text-gray-500">{{ trans('update.check_courses_with_most_generated_certificates') }}</p>
        </div>

        <div class="row">

            <div class="col-12 col-md-6 col-lg-3 mt-16">
                <div class="position-relative most-active-assignment-card">
                    <div class="most-active-assignment-card__mask rounded-32"></div>

                    <div class="position-relative z-index-2 bg-white p-20 rounded-16">
                        <div class="d-flex align-items-center p-4">
                            <div class="size-48 rounded-8 bg-gray-100">
                                <img src="" alt="course image" class="img-cover rounded-circle">
                            </div>
                            <div class="ml-8">
                                <h4 class="font-14 text-dark">Laravel Fundamentals Introduction</h4>
                                @include('design_1.web.components.rate', ['rate' => 3, 'rateClassName' => 'mt-4'])
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-20 pt-20 border-top-gray-100">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-40 rounded-circle bg-primary-20">
                                    <x-iconsax-bul-award class="icons text-primary" width="24px" height="24px"/>
                                </div>
                                <div class="ml-8">
                                    <h5 class="font-14 text-dark">24</h5>
                                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.certificates_generated') }}</p>
                                </div>
                            </div>

                            <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="20px" height="20px"/>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif
