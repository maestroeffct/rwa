@if($bundle->canSale() and !empty(getGiftsGeneralSettings('status')) and !empty(getGiftsGeneralSettings('allow_sending_gift_for_bundles')))
    <a href="/gift/bundle/{{ $bundle->slug }}" class="">
        <div class="course-show__gift-card card-with-mask position-relative mt-28">
            <div class="mask-8-white border-gray-200"></div>

            <div class="course-show__gift-card-box position-relative z-index-2 d-flex align-items-center p-16 rounded-16">
                <div class="course-show__gift-card-icon-1 d-flex-center size-56 rounded-circle">
                    <div class="course-show__gift-card-icon-2 d-flex-center size-40 rounded-circle">
                        <x-iconsax-bul-gift class="icon" width="24px" height="24px"/>
                    </div>
                </div>

                <div class="ml-8">
                    <h3 class="course-show__gift-card-title font-14">{{ trans('update.send_bundle_as_a_gift') }}</h3>
                    <div class="course-show__gift-card-subtitle mt-4 font-12">{{ trans('update.send_it_as_gift_to_your_friends') }}</div>
                </div>
            </div>
        </div>
    </a>
@endif
