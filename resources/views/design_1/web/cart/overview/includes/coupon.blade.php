<div class="card-with-mask position-relative mt-28">
    <div class="mask-8-white"></div>

    <div class="position-relative z-index-2 row align-items-center bg-white rounded-16 py-16 px-8 w-100 h-100">
        <div class="col-12 col-lg-4">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-56 bg-primary-20 rounded-circle">
                    <div class="d-flex-center size-40 bg-primary rounded-circle">
                        <x-iconsax-bul-ticket class="icons text-white" width="24px" height="24px"/>
                    </div>
                </div>

                <div class="ml-8">
                    <h5 class="font-14">{{ trans('update.have_a_coupon') }}</h5>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.validate_it_using_the_following_input') }}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8 mt-16 mt-lg-0">
            <div class="d-flex align-items-center">
                <input type="text" name="coupon" class="js-ajax-coupon form-control mr-12" placeholder="{{ trans('cart.enter_your_code_here') }}">

                <button type="button" class="js-validate-coupon-btn cart-coupon-btn btn btn-primary btn-lg">{{ trans('cart.validate') }}</button>
                <button type="button" class="js-remove-coupon-btn d-none cart-coupon-btn btn btn-danger btn-lg">{{ trans('public.remove') }}</button>
            </div>
        </div>
    </div>
</div>
