@if(!empty($overdueInstallments) and count($overdueInstallments))
    <div class="mt-28">
        <h3 class="font-16 font-weight-bold">{{ trans('update.overdue_installments') }}</h3>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.you_have_overdue_installments_please_pay_them_to_avoid_blocking_your_access') }}</p>

        <div class="position-relative mt-16">
            <div class="swiper-container js-make-swiper pending-quizzes-swiper pb-24"
                 data-item="pending-quizzes-swiper"
                 data-autoplay="false"
                 data-breakpoints="1440:3.2,769:2.6,320:1.4"
            >
                <div class="swiper-wrapper py-8">
                    @foreach($overdueInstallments as $overdueInstallment)
                        @php
                            $overdueInstallmentItem = $overdueInstallment->getItem();

                        @endphp

                        <div class="swiper-slide">
                            <a href="/panel/financial/installments/{{ $overdueInstallment->id }}/details" target="_blank" class="d-block text-decoration-none">
                                <div class="d-flex align-items-center justify-content-between p-20 bg-white rounded-16">
                                    <div class="d-flex align-items-center flex-1">
                                        <div class="d-flex-center size-64 rounded-12 bg-danger">
                                            <x-iconsax-bul-money-remove class="icons text-white" width="32px" height="32px"/>
                                        </div>

                                        <div class="ml-8 flex-1">
                                            <h5 class="font-14 font-weight-bold text-ellipsis text-dark">{{ truncate($overdueInstallment->installment->title, 32) }}</h5>
                                            <p class="mt-4 font-12 text-gray-500 text-ellipsis">{{ truncate($overdueInstallmentItem->title, 38) }}</p>

                                            <div class="d-flex align-items-center mt-12">

                                                <div class="d-flex align-items-center mr-16">
                                                    <x-iconsax-lin-money class="icons text-gray-400" width="16px" height="16px"/>
                                                    <span class="ml-4 font-12 text-gray-400">{{ handlePrice($overdueInstallment->overdue_amount) }}</span>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <x-iconsax-lin-calendar-2 class="icons text-gray-400" width="16px" height="16px"/>
                                                    <span class="ml-4 font-12 text-gray-400">{{ dateTimeFormat($overdueInstallment->overdue_timestamp, 'j M Y') }}</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="d-flex align-items-center ml-8 cursor-pointer">
                                        <span class="font-12 text-primary mr-4">{{ trans('update.pay_now') }}</span>
                                        <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                                    </div>

                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endif
