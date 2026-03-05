<div class="bg-white pt-16 pb-24 rounded-24">
    <div class="px-16">
        <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.month_sales') }}</h4>
    </div>

    <div class="d-grid grid-columns-2 grid-columns-lg-3 gap-16 mt-16 px-16">
        {{-- Totla Sales --}}
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">{{ trans('financial.total_sales') }}</span>
                <span class="font-24 font-weight-bold mt-16 text-dark">{{ $monthlySalesChart['topStats']['total'] }}</span>
            </div>

            <div class="d-flex-center size-48 rounded-12 bg-primary-30">
                <x-iconsax-bul-moneys class="icons text-primary" width="24px" height="24px"/>
            </div>
        </div>

        {{-- Month Sales --}}
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">{{ trans('update.month_sales') }}</span>
                <span class="font-24 font-weight-bold mt-16 text-dark">{{ $monthlySalesChart['topStats']['month'] }}</span>
            </div>

            <div class="d-flex-center size-48 rounded-12 bg-warning-30">
                <x-iconsax-bul-money-2 class="icons text-warning" width="24px" height="24px"/>
            </div>
        </div>

        {{-- Year sales --}}
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">{{ trans('update.year_sales') }}</span>
                <span class="font-24 font-weight-bold mt-16 text-dark">{{ $monthlySalesChart['topStats']['year'] }}</span>
            </div>

            <div class="d-flex-center size-48 rounded-12 bg-success-30">
                <x-iconsax-bul-money-3 class="icons text-success" width="24px" height="24px"/>
            </div>
        </div>


    </div>

    {{-- Have Data & Chart --}}
    <div class="px-16 mt-24 pt-24 border-top-gray-100">

        {{-- Chart --}}
        @push('scripts_bottom')
            <script>
                var monthlySalesChartLabels = @json($monthlySalesChart['chart']['labels']);
                var monthlySalesChartSeriesData = @json($monthlySalesChart['chart']['data']);
            </script>
        @endpush

        <div id="monthlySalesChart"
             class="js-monthly-sales-chart course-statistic__chart-card"
             data-series-name="{{ trans('panel.sales') }}"
             data-prefix="{{ $currency }}"
             data-id="monthlySalesChart"
        ></div>


    </div>

</div>
