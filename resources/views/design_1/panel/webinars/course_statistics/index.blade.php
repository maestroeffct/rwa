@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush


@section('content')
    <div class="course-statistic">

        {{-- Top Summary --}}
        @include('design_1.panel.webinars.course_statistics.includes.top_summary')

        {{-- Avg Stats --}}
        @include('design_1.panel.webinars.course_statistics.includes.avg_stats')

        {{--  Pie Charts --}}
        <div class="row">
            {{-- Student User Roles --}}
            <div class="col-12 col-md-6 col-lg-3 mt-24">
                @include('design_1.panel.webinars.course_statistics.includes.pie_charts', [
                    'cardTitle' => trans('update.students_user_roles'),
                    'cardId' => 'studentsUserRolesChart',
                    'cardPrimaryLabel' => trans('public.students'),
                    'cardSecondaryLabel' => trans('public.instructors'),
                    'cardWarningLabel' => trans('home.organizations'),
                ])
            </div>

            {{-- Course Progress --}}
            <div class="col-12 col-md-6 col-lg-3 mt-24">
                @include('design_1.panel.webinars.course_statistics.includes.pie_charts', [
                    'cardTitle' => trans('update.course_progress'),
                    'cardId' => 'courseProgressChart',
                    'cardPrimaryLabel' => trans('update.completed'),
                    'cardSecondaryLabel' => trans('webinars.in_progress'),
                    'cardWarningLabel' => trans('update.not_started'),
                ])
            </div>

            {{-- Quiz Status --}}
            <div class="col-12 col-md-6 col-lg-3 mt-24">
                @include('design_1.panel.webinars.course_statistics.includes.pie_charts', [
                    'cardTitle' => trans('update.quiz_status'),
                    'cardId' => 'quizStatusChart',
                    'cardPrimaryLabel' => trans('quiz.passed'),
                    'cardSecondaryLabel' => trans('public.pending'),
                    'cardWarningLabel' => trans('quiz.failed'),
                ])
            </div>

            {{-- Student Assignments --}}
            <div class="col-12 col-md-6 col-lg-3 mt-24">
                @include('design_1.panel.webinars.course_statistics.includes.pie_charts', [
                    'cardTitle' => trans('update.student_assignments'),
                    'cardId' => 'assignmentsStatusChart',
                    'cardPrimaryLabel' => trans('quiz.passed'),
                    'cardSecondaryLabel' => trans('public.pending'),
                    'cardWarningLabel' => trans('quiz.failed'),
                ])
            </div>

        </div>
        {{-- .\ Pie Charts --}}

        <div class="row">
            {{-- Learning Activity --}}
            <div class="col-12 col-lg-6 mt-24">
                @include('design_1.panel.webinars.course_statistics.includes.learning_activity')
            </div>

            {{-- Students Progress --}}
            <div class="col-12 col-lg-6 mt-24">
                @include('design_1.panel.webinars.course_statistics.includes.students_progress')
            </div>

            {{-- Sales --}}
            <div class="col-12 col-lg-6 mt-24">
                @include('design_1.panel.webinars.course_statistics.includes.sales')
            </div>

            {{-- Visitors --}}
            <div class="col-12 col-lg-6 mt-24">
                @include('design_1.panel.webinars.course_statistics.includes.visitors')
            </div>
        </div>

        {{-- Course Students --}}
        @include('design_1.panel.webinars.course_statistics.students.index')

    </div>
@endsection

@push("scripts_bottom")
    <script>

    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/design_1/vendor/apexcharts/apexcharts.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/course_statistics.min.js"></script>
@endpush
