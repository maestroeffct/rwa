@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.manage.students.top_stats')

    @if(!empty($users) and !$users->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('panel.students_list') }}</h3>

                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.manage.students.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/manage/students">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('auth.name') }}</th>
                        <th class="text-left">{{ trans('auth.email') }}</th>
                        <th class="text-center">{{ trans('public.phone') }}</th>
                        <th class="text-center">{{ trans('webinars.webinars') }}</th>
                        <th class="text-center">{{ trans('quiz.quizzes') }}</th>
                        <th class="text-center">{{ trans('panel.certificates') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-center">{{ trans('update.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($users as $userRow)
                        @include('design_1.panel.manage.students.table_items', ['user' => $userRow])
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
            'file_name' => 'students.svg',
            'title' => trans('panel.students_no_result'),
            'hint' =>  nl2br(trans('panel.students_no_result_hint')),
            'btn' => ['url' => '/panel/manage/students/new','text' => trans('panel.add_an_student')]
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
