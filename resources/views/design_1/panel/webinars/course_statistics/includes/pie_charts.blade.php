<div class="course-statistic__pie-chart-card bg-white p-16 rounded-24">
    <h3 class="font-14 text-dark">{{ $cardTitle }}</h3>

    @if(!empty($pieCharts) and !empty($pieCharts[$cardId]) and $pieCharts[$cardId]['hasData'])
        <div class="d-flex-center my-24">
            <div class="pie-chart-card js-pie-chart" data-labels='@json($pieCharts[$cardId]['labels'])' data-series='@json($pieCharts[$cardId]['data'])' data-id="{{ $cardId }}" id="{{ $cardId }}">

            </div>
        </div>
    @else
        <div class="pie-chart-no-data d-flex-center flex-column bg-gray-100 border-dashed border-gray-200 mt-16 p-32 rounded-16 text-center w-100">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-graph class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h5 class="font-14 text-dark mt-12">{{ trans('update.no_data') }}</h5>
            <p class="mt-4 font-12 text-gray-500">{{ trans('update.there_is_no_data_to_display') }}</p>
        </div>
    @endif
</div>
