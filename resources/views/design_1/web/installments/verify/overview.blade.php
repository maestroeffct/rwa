<div class="installment-overview position-relative">
    <div class="installment-overview__mask"></div>

    <div class="position-relative bg-primary p-4 rounded-16 z-index-2">
        <div class="px-4 py-8">
            <h4 class="font-14 font-weight-bold text-white">{{ trans('update.installment_overview') }}</h4>
        </div>

        <div class="position-relative bg-white rounded-12 p-16 mt-8 ">

            <div class="d-flex align-items-center">
                <div class="position-relative">
                    <div class="installment-overview__cup-icon-mask"></div>
                    <div class="position-relative d-flex-center size-40 rounded-circle bg-primary z-index-2">
                        <x-iconsax-bul-moneys class="icons text-white" width="24px" height="24px"/>
                    </div>
                </div>
                <div class="ml-16">
                    <h5 class="font-14 font-weight-bold">{{ $installment->title }}</h5>

                    @if($itemType == 'course')
                        <a href="{{ $item->getUrl() }}" target="_blank" class="font-12 text-gray-500 mt-4">{{ $item->title }}</a>
                    @else
                        <div class="font-12 text-gray-500 mt-4">{{ $item->title }}</div>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-64 gap-lg-120 mt-24 pt-16 border-top-gray-200">

                {{-- upfront_amount --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-dollar-circle class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.upfront_amount') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ !empty($installment->upfront) ? handlePrice($installment->getUpfront($itemPrice)) : trans('update.no_upfront') }}</span>
                    </div>
                </div>

                {{-- installments --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-moneys class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.installments') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ $installment->steps_count }} ({{ handlePrice($installment->totalPayments($itemPrice, false)) }})</span>
                    </div>
                </div>

                {{-- total_amount --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-money-2 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('financial.total_amount') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ handlePrice($installment->totalPayments($itemPrice)) }}</span>
                    </div>
                </div>

                {{-- duration --}}
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.duration') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ trans("update.n_day", ["day" => $installment->steps->max('deadline')]) }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
