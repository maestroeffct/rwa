@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    {{-- Stats --}}
    @include('design_1.panel.assignments.histories.top_stats')

    {{-- Most Active Assignments --}}
    @include('design_1.panel.assignments.histories.pending_reviews')

    @if(!empty($assignmentHistories) and !$assignmentHistories->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="pb-16 px-16 border-bottom-gray-100">
                <h2 class="font-16 text-dark">{{ trans("update.student_assignments") }}</h2>
                <p class="mt-4 text-gray-500">{{ trans('update.view_and_manage_your_student_assignments') }}</p>
            </div>

            {{-- Most Active Assignments --}}
            @include('design_1.panel.assignments.histories.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/assignments/histories">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('quiz.student') }}</th>
                        <th class="text-left">{{ trans('update.assignment_title') }}</th>
                        <th class="text-center">{{ trans('update.purchase_date') }}</th>
                        <th class="text-center">{{ trans('update.first_submission') }}</th>
                        <th class="text-center">{{ trans('update.last_submission') }}</th>
                        <th class="text-center">{{ trans('update.attempts') }}</th>
                        <th class="text-center">{{ trans('quiz.grade') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($assignmentHistories as $assignmentHistory)
                        @include('design_1.panel.assignments.histories.item_table', ['assignmentHistory' => $assignmentHistory])
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
            'file_name' => 'assignments.svg',
            'title' => trans('update.assignments_history_no_result'),
            'hint' => nl2br(trans('update.assignments_history_no_result_hint')),
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

@endpush
