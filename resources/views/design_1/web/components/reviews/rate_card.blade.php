<div class="d-flex">
    <div class="review-rate-box position-relative">
        <div class="review-rate-box__mask"></div>

        <div class="position-relative d-flex-center flex-column text-center bg-gray-100 border-gray-200 rounded-12 p-14 p-lg-20 z-index-2 w-100 h-100">
            <div class="font-44 font-weight-bold">{{ $itemRow->getRate() }}</div>

            @include('design_1.web.components.rate', [
                 'rate' => $itemRow->getRate(),
                 'rateCount' => false,
                 'rateClassName' => 'mt-8'
             ])

            <div class="mt-8 font-12 text-gray-500">{{ $itemRow->getRateCount() }}  {{ trans('product.reviews') }}</div>
        </div>
    </div>

    <div class="flex-1 ml-24">

        @foreach($reviewOptions as $reviewOption)
            @php
                $optionRate = $itemRow->reviews->avg($reviewOption) ?? 0;
                $ratePercent = ($optionRate > 0) ? ($optionRate / 5 * 100) : 0;
                $rateCount = ($optionRate > 0) ? round($optionRate, 1) : 0;
            @endphp

            <div class="mt-16">
                <div class="font-12 text-gray-500">{{ trans("product.{$reviewOption}") }} ({{ $rateCount }})</div>
                <div class="review-progress position-relative mt-8 rounded-4 bg-gray-100">
                    <span class="review-progress__bar rounded-4 bg-warning" style="width: {{ $ratePercent }}%"></span>
                </div>
            </div>
        @endforeach

    </div>
</div>
