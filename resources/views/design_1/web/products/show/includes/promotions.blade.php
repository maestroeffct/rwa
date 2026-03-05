@php
    $showGiftCard = ($product->isVirtual() and $productAvailability > 0 and !empty(getGiftsGeneralSettings('status')) and !empty(getGiftsGeneralSettings('allow_sending_gift_for_products')));
    $showCashbackAlert = (!empty($cashbackRules) and count($cashbackRules));
    $showInstructorDiscountBeforeContent = (
       !empty(getFeaturesSettings("frontend_coupons_display_type")) and
       getFeaturesSettings("frontend_coupons_display_type") == "before_content" and
       !empty($instructorDiscounts) and
       count($instructorDiscounts)
    );

@endphp

@if($showGiftCard or $showCashbackAlert or $showInstructorDiscountBeforeContent)
    <div class="wrapped-two-card gap-24 mt-16">
        {{-- Gift Card --}}
        @if($showGiftCard)
            <a href="/gift/product/{{ $product->slug }}">
                <div class="card-with-mask position-relative wrapped-item">
                    <div class="mask-8-white z-index-1"></div>
                    <div class="position-relative d-flex align-items-center justify-content-between bg-white p-16 rounded-24 z-index-2">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-56 rounded-circle bg-accent-20">
                                <div class="d-flex-center size-40 rounded-circle bg-accent">
                                    <x-iconsax-bul-gift class="icons text-white" width="24px" height="24px"/>
                                </div>
                            </div>
                            <div class="ml-8">
                                <h6 class="font-14 font-weight-bold text-dark">{{ trans('update.send_product_as_a_gift') }}</h6>
                                <p class="mt-4 font-12 text-gray-500">{{ trans('update.send_it_as_gift_to_your_friends') }}</p>
                            </div>
                        </div>

                        <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                </div>
            </a>
        @endif

        @if($showCashbackAlert)
            @include('design_1.web.cashback.alert_card', [
                'cashbackRules' => $cashbackRules,
                'itemPrice' => $product->price,
                'cashbackRulesCardClassName' => "wrapped-item"
            ])
        @endif

        @if($showInstructorDiscountBeforeContent)
            @include('design_1.web.instructor_discounts.cards', ['allDiscounts' => $instructorDiscounts, 'discountCardClassName2' => "wrapped-item"])
        @endif
    </div>
@endif
