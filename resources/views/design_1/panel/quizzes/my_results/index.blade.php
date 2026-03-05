@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.quizzes.my_results.top_stats')


    {{-- Pending Quizzes --}}
    @if(!empty($pendingQuizzes) and count($pendingQuizzes))
        @include('design_1.panel.quizzes.my_results.pending')
    @endif


    @if(!empty($quizzesResults) and !$quizzesResults->isEmpty())
         <div class="bg-white pt-16 rounded-24 mt-28">

             <div class="px-16 mb-24 pb-16 border-bottom-gray-200">
                <h3 class="font-16">{{ trans('update.my_results') }}</h3>
                <p class="mt-4 text-gray-500">{{ trans('update.view_and_manage_your_quiz_results') }}</p>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.quizzes.my_results.filters')


            {{-- List Table --}}
             <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/quizzes/my-results">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.instructor') }}</th>
                        <th class="text-left">{{ trans('quiz.quiz') }}</th>
                        <th class="text-center">{{ trans('quiz.quiz_grade') }}</th>
                        <th class="text-center">{{ trans('quiz.student_grade') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">

                    @foreach($quizzesResults as $result)
                        @include('design_1.panel.quizzes.my_results.table_items', ['quizResult' => $result])
                    @endforeach
                    </tbody>
                </table>

                 {{-- Pagination --}}
                 <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                     {!! $pagination !!}
                 </div>
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'my_results.svg',
            'title' => trans('quiz.quiz_result_no_result'),
            'hint' => trans('quiz.quiz_result_no_result_hint'),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/quiz_list.min.js"></script>
@endpush
