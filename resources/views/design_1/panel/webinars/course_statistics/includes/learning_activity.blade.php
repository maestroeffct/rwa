<div class="bg-white pt-16 pb-24 rounded-24">
    <div class="px-16">
        <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.learning_activity') }}</h4>
    </div>

    <div class="d-grid grid-columns-2 grid-columns-lg-3 gap-16 mt-16 px-16">
        {{-- Total Time --}}
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">{{ trans('update.total_activity') }}</span>
                <span class="font-24 font-weight-bold mt-16 text-dark">{{ $learningActivity['activityStats']['total'] }}</span>
            </div>

            <div class="d-flex-center size-48 rounded-12 bg-primary-30">
                <x-iconsax-bul-timer class="icons text-primary" width="24px" height="24px"/>
            </div>
        </div>

        {{-- Month Time --}}
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">{{ trans('update.month_activity') }}</span>
                <span class="font-24 font-weight-bold mt-16 text-dark">{{ $learningActivity['activityStats']['month'] }}</span>
            </div>

            <div class="d-flex-center size-48 rounded-12 bg-warning-30">
                <x-iconsax-bul-clock-1 class="icons text-warning" width="24px" height="24px"/>
            </div>
        </div>

        {{-- Year Time --}}
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">{{ trans('update.year_activity') }}</span>
                <span class="font-24 font-weight-bold mt-16 text-dark">{{ $learningActivity['activityStats']['year'] }}</span>
            </div>

            <div class="d-flex-center size-48 rounded-12 bg-success-30">
                <x-iconsax-bul-timer-1 class="icons text-success" width="24px" height="24px"/>
            </div>
        </div>


    </div>

    {{-- Have Data & Chart --}}
    <div class="px-16 mt-24 pt-24 border-top-gray-100">

        {{-- Chart --}}
        @push('scripts_bottom')
            <script>
                var learningActivityChartLabels = @json($learningActivity['learningActivityChart']['labels']);
                var learningActivityChartSeriesData = @json($learningActivity['learningActivityChart']['data']);
            </script>
        @endpush

        <div id="learningActivityChart"
             class="js-learning-activity-chart course-statistic__chart-card"
             data-series-name="{{ trans('update.learning_activity') }}"
             data-prefix="{{ trans('update.min') }}"
             data-id="learningActivityChart"
        ></div>

    </div>

</div>
