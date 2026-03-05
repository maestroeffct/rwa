@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    @if(!empty($quizzes) and !$quizzes->isEmpty())
        <div class="bg-white pt-16 rounded-24">
             <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.not_participated') }} {{ trans('panel.quizzes') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_not_participated_quizzes') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.quizzes.opens.filters')


            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/quizzes/opens">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('public.instructor') }}</th>
                        <th class="text-left">{{ trans('quiz.quiz') }}</th>
                        <th class="text-center">{{ trans('quiz.quiz_grade') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">

                    @foreach($quizzes as $quizRow)
                        @include('design_1.panel.quizzes.opens.table_items', ['quiz' => $quizRow])
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
            'file_name' => 'open_quizzes.svg',
            'title' => trans('quiz.quiz_result_no_result'),
            'hint' => trans('update.not_participated_quizzes_no_result_hint'),
            'extraClass' => 'mt-0',
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
