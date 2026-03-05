@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    {{-- Stats --}}
    @include('design_1.panel.certificates.students.top_stats')

    {{-- Most Active Courses --}}
    @include('design_1.panel.certificates.students.most_active_courses')

    {{-- Lists --}}
    @if(!empty($certificates) and $certificates->isNotEmpty())
        <div class="bg-white rounded-24 pt-16 mt-28">

              <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.generated_certificates') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_and_manage_certificates_generated_for_your_courses') }}</p>
                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.certificates.students.filters')


            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/certificates/students">

                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('quiz.student') }}</th>
                        <th>{{ trans('update.certificate_id') }}</th>
                        <th class="text-left">{{ trans('update.certification_reason') }}</th>
                        <th>{{ trans('update.certification_type') }}</th>
                        <th>{{ trans('update.certificate_date') }}</th>
                        <th class="text-right">{{ trans('update.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-tbody-lists">
                    @foreach($certificates as $certificateItem)
                        @include('design_1.panel.certificates.students.item_table',['certificate' => $certificateItem])
                    @endforeach
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
            'title' => trans('update.student_certificates_no_result'),
            'hint' => nl2br(trans('update.student_certificates_no_result_hint')),
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

@endpush
