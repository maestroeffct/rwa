<div class="bg-white pt-16 pb-24 rounded-24">
    <div class="px-16">
        <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.students_progress') }}</h4>
    </div>

    <div class="d-grid grid-columns-3 gap-16 mt-16 px-16">
        {{-- Daily Time --}}
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">{{ trans('update.not_started') }}</span>
                <span class="font-24 font-weight-bold mt-16 text-dark">{{ $courseProgressLineChart['topStats']['notStarted'] }}</span>
            </div>

            <div class="d-flex-center size-48 rounded-12 bg-primary-30">
                <x-iconsax-bul-flag class="icons text-primary" width="24px" height="24px"/>
            </div>
        </div>

        {{-- Month Time --}}
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">{{ trans('update.in_progress') }}</span>
                <span class="font-24 font-weight-bold mt-16 text-dark">{{ $courseProgressLineChart['topStats']['pending'] }}</span>
            </div>

            <div class="d-flex-center size-48 rounded-12 bg-warning-30">
                <x-iconsax-bul-trend-up class="icons text-warning" width="24px" height="24px"/>
            </div>
        </div>

        {{-- Total Time --}}
        <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
            <div class="d-flex flex-column pt-8">
                <span class="text-gray-500 font-12">{{ trans('update.completed') }}</span>
                <span class="font-24 font-weight-bold mt-16 text-dark">{{ $courseProgressLineChart['topStats']['completed'] }}</span>
            </div>

            <div class="d-flex-center size-48 rounded-12 bg-success-30">
                <x-iconsax-bul-tick-circle class="icons text-success" width="24px" height="24px"/>
            </div>
        </div>
    </div>

    {{-- Have Data & Chart --}}
    <div class="px-16 mt-24 pt-24 border-top-gray-100">

        {{-- Chart --}}
        @push('scripts_bottom')
            <script>
                var studentsProgressChartLabels = @json($courseProgressLineChart['chart']['labels']);
                var studentsProgressChartSeriesData = @json($courseProgressLineChart['chart']['data']);
            </script>
        @endpush

        <div id="studentsProgressChart"
             class="js-students-progress-chart course-statistic__chart-card"
             data-series-name="{{ trans('update.progress') }}"
             data-id="studentsProgressChart"
        ></div>

    </div>

</div>
