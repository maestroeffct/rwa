@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    {{-- Stats --}}
    @include('design_1.panel.assignments.my-courses-assignments.top_stats')

    {{-- Most Active Assignments --}}
    @include('design_1.panel.assignments.my-courses-assignments.most_active')


    @if(!empty($assignments) and !$assignments->isEmpty())
        <div class="bg-white pt-16 rounded-16 mt-28">
            <div class="pb-16 px-16 border-bottom-gray-100">
                <h2 class="font-16 text-dark">{{ trans("update.assignments_list") }}</h2>
                <p class="mt-4 text-gray-500">{{ trans('update.view_and_manage_your_courses_assignments') }}</p>
            </div>

            {{-- Most Active Assignments --}}
            @include('design_1.panel.assignments.my-courses-assignments.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg mt-24" data-view-data-path="/panel/assignments">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.assignment_title') }}</th>
                        <th class="text-center">{{ trans('update.min_grade') }}</th>
                        <th class="text-center">{{ trans('quiz.average') }}</th>
                        <th class="text-center">{{ trans('update.submissions') }}</th>
                        <th class="text-center">{{ trans('public.pending') }}</th>
                        <th class="text-center">{{ trans('quiz.passed') }}</th>
                        <th class="text-center">{{ trans('quiz.failed') }}</th>
                        <th class="text-center">{{ trans('update.last_submission') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-body-lists">
                    @foreach($assignments as $assignment)
                        @include('design_1.panel.assignments.my-courses-assignments.item_table', ['assignment' => $assignment])
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
            'file_name' => 'assignments.svg',
            'title' => trans('update.courses_assignments_no_result'),
            'hint' => nl2br(trans('update.courses_assignments_no_result_hint')),
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

@endpush
