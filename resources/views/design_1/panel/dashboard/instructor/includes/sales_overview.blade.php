<div class="bg-white p-16 rounded-24 w-100 mt-24">
    <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.sales_overview') }}</h4>

    @if(!empty($salesOverview['hasSalesOverviewData']))
        <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16">
            {{-- Live Courses --}}
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12">{{ trans('update.course_sales') }}</span>
                    <span class="font-24 font-weight-bold mt-16 text-dark">{{ !empty($salesOverview['totalCourseSales']) ? handlePrice($salesOverview['totalCourseSales']) : '-' }}</span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                    <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            {{-- Video Courses --}}
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12">{{ trans('update.product_sales') }}</span>
                    <span class="font-24 font-weight-bold mt-16 text-dark">{{ !empty($salesOverview['totalProductSales']) ? handlePrice($salesOverview['totalProductSales']) : '-' }}</span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-success-40">
                    <x-iconsax-bul-box-1 class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            {{-- Text Courses --}}
            <div class="d-flex align-items-start justify-content-between p-16 rounded-16 bg-gray-100">
                <div class="d-flex flex-column pt-8">
                    <span class="text-gray-500 font-12">{{ trans('update.meeting_sales') }}</span>
                    <span class="font-24 font-weight-bold mt-16 text-dark">{{ !empty($salesOverview['totalMeetingSales']) ? handlePrice($salesOverview['totalMeetingSales']) : '-' }}</span>
                </div>

                <div class="d-flex-center size-48 rounded-12 bg-warning-40">
                    <x-iconsax-bul-video class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="d-flex align-items-center gap-40 gap-lg-80 mt-24 py-20 border-top-gray-100 border-bottom-gray-100">
            {{-- Month Sales --}}
            <div class="">
                <span class="d-block font-24 font-weight-bold text-dark">{{ !empty($salesOverview['monthSalesAmount']) ? handlePrice($salesOverview['monthSalesAmount']) : '-' }}</span>
                <span class="d-block text-gray-500 mt-8">{{ trans('update.month_sales') }}</span>
            </div>

            {{-- Year Sales --}}
            <div class="">
                <span class="d-block font-24 font-weight-bold text-dark">{{ !empty($salesOverview['yearSalesAmount']) ? handlePrice($salesOverview['yearSalesAmount']) : '-' }}</span>
                <span class="d-block text-gray-500 mt-8">{{ trans('update.year_sales') }}</span>
            </div>

            {{-- Total Sales --}}
            <div class="">
                <span class="d-block font-24 font-weight-bold text-dark">{{ !empty($salesOverview['totalSalesAmount']) ? handlePrice($salesOverview['totalSalesAmount']) : '-' }}</span>
                <span class="d-block text-gray-500 mt-8">{{ trans('financial.total_sales') }}</span>
            </div>
        </div>

        {{-- Chart --}}
        <div id="instructorSalesOverviewChart" class="instructor-dashboard__sales-overview-chart mt-24"></div>

        @push('scripts_bottom')
            <script>
                var courseSalesLang = '{{ trans('update.course_sales') }}';
                var meetingSalesLang = '{{ trans('update.meeting_sales') }}';
                var productSalesLang = '{{ trans('update.product_sales') }}';
                var instructorSalesOverviewChartLabels = @json($salesOverview['chart']['labels']);
                var instructorSalesOverviewChartCourseSales = @json($salesOverview['chart']['courseSales']);
                var instructorSalesOverviewChartMeetingSales = @json($salesOverview['chart']['meetingSales']);
                var instructorSalesOverviewChartProductSales = @json($salesOverview['chart']['productSales']);
            </script>
        @endpush

    @else
        {{-- If Empty --}}
        <div class="d-flex-center flex-column text-center bg-gray-100 border-dashed border-gray-200 rounded-16 mt-16 p-60">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-money-2 class="icons text-primary" width="24px" height="24px"/>
            </div>

            <h4 class="mt-12 font-14 text-dark">{{ trans('update.no_sale!') }}</h4>
            <div class="font-12 text-gray-500 mt-4">{{ trans('update.instructor_dashboard_sales_overview_no_sales_hint') }}</div>

            <div class="d-grid grid-columns-2 gap-8 mt-28 p-8 rounded-16 bg-white">
                {{-- Create a Course --}}
                <a href="/panel/courses/new" target="_blank" class="btn btn-xlg border-dashed border-gray-200 rounded-16 bg-white bg-hover-gray-100">
                    <x-iconsax-bul-play-add class="icons text-primary" width="24px" height="24px"/>
                    <span class="ml-8 text-dark">{{ trans('update.create_a_course') }}</span>
                </a>

                {{-- Create a Product --}}
                <a href="/panel/store/products/new" target="_blank" class="btn btn-xlg border-dashed border-gray-200 rounded-16 bg-white bg-hover-gray-100">
                    <x-iconsax-bul-document-upload class="icons text-primary" width="24px" height="24px"/>
                    <span class="ml-8 text-dark">{{ trans('update.create_a_product') }}</span>
                </a>

                {{-- Create a Bundle --}}
                <a href="/panel/bundles/new" target="_blank" class="btn btn-xlg border-dashed border-gray-200 rounded-16 bg-white bg-hover-gray-100">
                    <x-iconsax-bul-box-1 class="icons text-primary" width="24px" height="24px"/>
                    <span class="ml-8 text-dark">{{ trans('update.create_a_bundle') }}</span>
                </a>

                {{-- Meeting Settings --}}
                <a href="/panel/meetings/settings" target="_blank" class="btn btn-xlg border-dashed border-gray-200 rounded-16 bg-white bg-hover-gray-100">
                    <x-iconsax-bul-profile-2user class="icons text-primary" width="24px" height="24px"/>
                    <span class="ml-8 text-dark">{{ trans('update.meeting_settings') }}</span>
                </a>

            </div>
        </div>
    @endif

</div>
