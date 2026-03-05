@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
@endpush

@section('content')
    {{-- Stats --}}
    @include('design_1.panel.certificates.my_achievements.top_stats')

    {{-- Recent Student Certificates --}}
    @include('design_1.panel.certificates.my_achievements.potential_certificates')

    {{-- Lists --}}
    @if($userHaveCertificatesItems)
        <div class="bg-white rounded-24 pt-16 mt-28">
            <div class="px-16">
                <h4 class="font-16 font-weight-bold">{{ trans('update.active_certificates') }}</h4>
                <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_and_manage_quiz_and_completion_certificates') }}</p>
            </div>

            <div class="d-flex align-items-center border-bottom-gray-100 border-top-gray-100 mt-16 px-16">

                @php
                    $tabs = [
                        'quiz' => 'clipboard-tick',
                        'completion' => 'tick-circle',
                    ];
                @endphp

                @foreach($tabs as $tabName => $tabIcon)
                    <div class="js-get-view-data-by-tab navbar-item navbar-item-h-52 d-flex align-items-center mr-20 mr-md-40 cursor-pointer {{ $loop->first ? 'active' : '' }}"
                         data-filter-name="source" data-filter-value="{{ $tabName }}"
                         data-container-id="tableListContainer"
                    >
                        @svg("iconsax-lin-{$tabIcon}", ['width' => '20px', 'height' => '20px', 'class' => 'icons'])

                        <span class="ml-4">{{ trans("update.{$tabName}_certificates") }}</span>
                    </div>
                @endforeach

            </div>


            {{-- Filters --}}
            @include('design_1.panel.certificates.my_achievements.filters')


            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/certificates/my-achievements">

                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.certificate_title') }}</th>
                        <th>{{ trans('update.certificate_id') }}</th>
                        <th class="js-specific-th js-quiz-th">{{ trans('quiz.minimum_grade') }}</th>
                        <th class="js-specific-th js-quiz-th">{{ trans('quiz.my_grade') }}</th>
                        <th class="js-specific-th js-quiz-th">{{ trans('update.total_grade') }}</th>
                        <th>{{ trans('update.last_certificate') }}</th>
                        <th class="text-right">{{ trans('controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-tbody-lists">
                    @if(!empty($certificatesItems) and $certificatesItems->isNotEmpty())
                        @foreach($certificatesItems as $certificateItem)
                            @include('design_1.panel.certificates.my_achievements.quiz_item_table',['quizItem' => $certificateItem])
                        @endforeach
                    @endif
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                     data-container-items=".js-table-tbody-lists">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'certificates_list.svg',
            'title' => trans('quiz.my_certificates_no_result'),
            'hint' => nl2br(trans('quiz.my_certificates_no_result_hint')),
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    <script src="/assets/design_1/js/panel/certificates.min.js"></script>

@endpush
