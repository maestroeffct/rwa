@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.assignments.my_assignments.top_stats')

    {{-- Pending Assignments --}}
    @include('design_1.panel.assignments.my_assignments.pending_assignments')


    @if(!empty($assignments) and !$assignments->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.my_assignments') }}</h3>

                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.assignments.my_assignments.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/assignments/my-requests">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('update.title_and_course') }}</th>
                        <th class="text-center">{{ trans('update.deadline') }}</th>
                        <th class="text-center">{{ trans('update.first_submission') }}</th>
                        <th class="text-center">{{ trans('update.last_submission') }}</th>
                        <th class="text-center">{{ trans('update.attempts') }}</th>
                        <th class="text-center">{{ trans('quiz.grade') }}</th>
                        <th class="text-center">{{ trans('update.pass_grade') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($assignments as $assignment)
                        @include('design_1.panel.assignments.my_assignments.table_items', ['assignment' => $assignment])
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
            'title' => trans('update.my_assignments_no_result'),
            'hint' => nl2br(trans('update.my_assignments_no_result_hint')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var notAccessToastTitleLang = '{{ trans('public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('public.not_access_toast_msg_lang') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/my_assignments.min.js"></script>
@endpush
