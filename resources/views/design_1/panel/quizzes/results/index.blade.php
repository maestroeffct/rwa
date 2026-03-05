@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    {{-- Stats --}}
    @include('design_1.panel.quizzes.results.top_stats')

    {{-- Pending Review Quizzes --}}
    @include('design_1.panel.quizzes.results.pending_reviews')

    @if(!empty($quizzesResults) and !$quizzesResults->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-28">
            <div class="px-16 mb-24 pb-16 border-bottom-gray-200">
                <h2 class="font-16 text-dark">{{ trans("update.student_results") }}</h2>
                <p class="mt-4 text-gray-500">{{ trans('update.view_and_manage_your_students_quiz_results') }}</p>
            </div>

            {{-- Most Active Assignments --}}
            @include('design_1.panel.quizzes.results.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/quizzes/results">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('quiz.student') }}</th>
                        <th class="text-left">{{ trans('quiz.quiz') }}</th>
                        <th class="text-center">{{ trans('update.total_grade') }}</th>
                        <th class="text-center">{{ trans('update.pass_grade') }}</th>
                        <th class="text-center">{{ trans('quiz.student_grade') }}</th>
                        <th class="text-center">{{ trans('quiz.attempts') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-body-lists">
                    @foreach($quizzesResults as $quizResultRow)
                        @include('design_1.panel.quizzes.results.item_table', ['quizResult' => $quizResultRow])
                    @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-body-lists">
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
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

@endpush
