<div class="installment-card d-flex flex-column flex-lg-row align-items-start gap-24 position-relative bg-white p-16 rounded-16 mb-48 {{ !empty($className) ? $className : '' }}">
    {{-- Installment Details --}}
    <div class="flex-1">
        <h4 class="font-16 font-weight-bold">{{ $installment->main_title }}</h4>
        <p class="text-gray-500 font-12 mt-4">{{ nl2br($installment->description) }}</p>

        @if(!empty($installment->capacity))
            @php
                $reachedCapacityPercent = $installment->reachedCapacityPercent();
            @endphp

            @if($reachedCapacityPercent > 0)
                <div class="mt-20 d-flex align-items-center my-12">
                    <div class="progress card-progress bg-gray-100 rounded-4 flex-grow-1">
                        <span class="progress-bar rounded-4 {{ $reachedCapacityPercent > 50 ? 'bg-danger' : 'bg-success' }}" style="width: {{ $reachedCapacityPercent }}%"></span>
                    </div>

                    <div class=" d-flex-center bg-gray-100 rounded-16 p-8 ml-12 font-12 text-gray-500">
                        <x-iconsax-lin-profile-2user class="icons text-gray-500 mr-4" width="24px" height="24px"/>
                        {!! trans('update.percent_capacity_reached',['percent' => $reachedCapacityPercent]) !!}
                    </div>
                </div>
            @endif
        @endif


        @if(!empty($installment->banner))
            <div class="installment-card__image overflow-hidden mt-16">
                <img src="{{ $installment->banner }}" alt="{{ $installment->main_title }}" class="img-fluid">
            </div>
        @endif

        @if(!empty($installment->options))
            <div class="">
                @php
                    $installmentOptions = explode(\App\Models\Installment::$optionsExplodeKey, $installment->options);
                @endphp

                @foreach($installmentOptions as $installmentOption)
                    <div class="d-flex align-items-center {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                        <x-tick-icon class="icons text-success" width="24px" height="24px"/>
                        <span class="ml-4 font-14 text-gray-500">{{ $installmentOption }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Right Card (Payment Details) --}}
    <div class="installment-card__payments position-relative">
        <div class="installment-card__payments-mask"></div>

        @php
            $totalPayments = $installment->totalPayments($itemPrice ?? 1);
            $installmentTotalInterest = $installment->totalInterest($itemPrice, $totalPayments);
        @endphp

        <div class="position-relative d-flex flex-column bg-primary rounded-16 z-index-2 h-100">
            <div class="d-flex-center flex-column text-center pt-28">
                <div class="font-24 font-weight-bold text-white">{{ handlePrice($totalPayments) }}</div>
                <div class="mt-4 text-white">
                    {{ trans('update.total_payment') }}
                    @if($installmentTotalInterest > 0)
                        ({!! trans('update.percent_interest_bold',['percent' => $installmentTotalInterest]) !!})
                    @endif
                </div>
            </div>

            <div class="divider mt-16"></div>

            {{-- Payment Steps --}}
            <div class="d-flex flex-column px-16 mb-16">

                {{-- Upfront --}}
                <div class="d-flex align-items-center justify-content-between mt-16">
                    <div class="d-flex align-items-center">
                        <x-iconsax-bol-card-tick class="icons text-white" width="24px" height="24px"/>
                        <div class="ml-8">
                            <span class="d-block font-weight-bold text-white">{{ !empty($installment->upfront) ? handlePrice($installment->getUpfront($itemPrice)) : trans('update.no') }}</span>
                            <span class="mt-4 font-12 text-white opacity-50">{{ trans('update.upfront') }}</span>
                        </div>
                    </div>

                    @if($installment->upfront_type == "percent")
                        <div class="installment-card__percent-box px-8 py-4 rounded-16 font-12 text-white   ">{{ $installment->upfront }}%</div>
                    @endif
                </div>

                {{-- Other Step --}}
                @foreach($installment->steps as $installmentStep)
                    <div class="d-flex align-items-center justify-content-between mt-16">
                        <div class="d-flex align-items-center">
                            <x-iconsax-bol-card-tick class="icons text-white" width="24px" height="24px"/>
                            <div class="ml-8">
                                <span class="d-block font-weight-bold text-white">{{ handlePrice($installmentStep->getPrice($itemPrice)) }}</span>
                                <span class="mt-4 font-12 text-white opacity-50">{{ trans('update.after_n_days', ['days' => $installmentStep->deadline]) }}</span>
                            </div>
                        </div>

                        @if($installmentStep->amount_type == "percent")
                            <div class="installment-card__percent-box px-8 py-4 rounded-16 font-12 text-white   ">{{ $installmentStep->amount }}%</div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="p-12 pt-0 mt-auto">
                <a href="/installments/{{ $installment->id }}?item={{ $itemId }}&item_type={{ $itemType }}&{{ http_build_query(request()->all()) }}" target="_blank" class="btn btn-lg btn-white text-primary btn-block">{{ trans('update.select_plan') }}</a>
            </div>

        </div>
    </div>
</div>
