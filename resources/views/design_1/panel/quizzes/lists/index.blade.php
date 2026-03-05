@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.quizzes.lists.top_stats')

    @if(!empty($quizzes) and !$quizzes->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
             <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('quiz.quizzes') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.manage_all_quizzes_in_a_single_place') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.quizzes.lists.filters')


            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/quizzes">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('public.title') }}</th>
                        <th class="text-center">{{ trans('public.questions') }}</th>
                        <th class="text-center">{{ trans('public.time') }} <span class="braces">({{ trans('public.min') }})</span></th>
                        <th class="text-center">{{ trans('public.total_mark') }}</th>
                        <th class="text-center">{{ trans('public.pass_mark') }}</th>
                        <th class="text-center">{{ trans('quiz.students') }}</th>
                        {{--<th>{{ trans('quiz.average') }}</th>--}}
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-center">{{ trans('public.date_created') }}</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($quizzes as $quiz)
                        @include('design_1.panel.quizzes.lists.table_items', ['quiz' => $quiz])
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
            'file_name' => 'quizzes.svg',
            'title' => trans('quiz.quiz_no_result'),
            'hint' => nl2br(trans('quiz.quiz_no_result_hint')),
            'btn' => ['url' => '/panel/quizzes/new','text' => trans('quiz.create_a_quiz')]
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
