<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark">{{ trans('update.visitors_statistics') }}</h4>

    @if(!empty($visitorsStatistics['totalVisitorsCount']))
        <div class="d-flex align-items-center gap-64 mt-20">
            <div class="">
                <h6 class="font-24 text-dark">{{ shortNumbers($visitorsStatistics['totalVisitorsCount']) }}</h6>
                <div class="text-gray-500 mt-8">{{ trans('update.total_visitors') }}</div>
            </div>

            <div class="">
                <h6 class="font-24 text-dark">{{ shortNumbers($visitorsStatistics['monthVisitorsCount']) }}</h6>
                <div class="text-gray-500 mt-8">{{ trans('update.month_visitors') }}</div>
            </div>
        </div>

        {{-- Chart --}}
        <div id="visitorsStatisticsChart" class="instructor-dashboard__visitors-statistics-chart mt-24 pt-20 border-top-gray-100">

        </div>

        @push('scripts_bottom')
            <script>
                var visitorsLang = '{{ trans('update.visitors') }}'
                var instructorVisitorsChartLabels = @json($visitorsStatistics['chart']['labels']);
                var instructorVisitorsChartData = @json($visitorsStatistics['chart']['datasets']);
            </script>
        @endpush

        {{--  Top Views --}}
        @if(!empty($visitorsStatistics['topViews']) and count($visitorsStatistics['topViews']))
            <div class="bg-gray-100 p-16 rounded-16 mt-20">
                <h5 class="font-14 text-dark">{{ trans('update.top_views') }}</h5>

                {{-- Card --}}
                @foreach($visitorsStatistics['topViews'] as $topView)
                    <div class="d-flex align-items-center mt-16">
                        <div class="size-48 rounded-12">
                            <img src="{{ $topView->getItemImage() }}" alt="" class="img-cover rounded-8">
                        </div>
                        <div class="ml-8">
                            <h6 class="font-14 text-dark">{{ truncate($topView->getItemTitle(), 28) }}</h6>
                            <div class="font-12 text-gray-500 mt-4">{{ shortNumbers($topView->total) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="d-flex-center flex-column bg-gray-100 border-dashed border-gray-200 text-center mt-16 p-32 rounded-16">
            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                <x-iconsax-bul-chart-2 class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h4 class="font-14 text-dark mt-12">{{ trans('update.no_visitor!') }}</h4>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.you_don’t_have_visitors_create_various_content_types_and_attract_visitors') }}</div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h6 class="font-14 text-dark">{{ trans('update.promote_courses') }}</h6>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.promote_your_courses_and_get_more_visitors') }}</p>
            </div>

            <a href="" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
            </a>
        </div>
    @endif
</div>
