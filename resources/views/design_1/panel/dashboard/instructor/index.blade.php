<div class="row">
    <div class="col-12 col-lg-6">
        {{-- Hello Box --}}
        @include('design_1.panel.dashboard.instructor.includes.hello_box')

        {{-- Courses Overview --}}
        <div class="{{ (!empty($isRtl) ? 'mt-160' : 'mt-128') }}">
            @include('design_1.panel.dashboard.instructor.includes.courses_overview')
        </div>

        {{-- Sales Overview --}}
        @include('design_1.panel.dashboard.instructor.includes.sales_overview')

        {{-- Pending Student Assignments --}}
        @include('design_1.panel.dashboard.instructor.includes.pending_student_assignments')

    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        {{-- Registration Plan --}}
        @include('design_1.panel.dashboard.instructor.includes.registration_plan')

        {{-- Current Balance (No different with Student Dashboard) --}}
        @include('design_1.panel.dashboard.student.includes.current_balance')

        {{-- Noticeboard (No different with Student Dashboard) --}}
        @include('design_1.panel.dashboard.student.includes.noticeboard')

        {{-- Support Messages --}}
        @include('design_1.panel.dashboard.instructor.includes.support_messages')

        {{-- Visitors Statistics --}}
        @include('design_1.panel.dashboard.instructor.includes.visitors_statistics')

    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        {{-- Events Calendar  (No different with Student Dashboard) --}}
        @include('design_1.panel.dashboard.student.includes.events_calendar')

        @if($authUser->isTeacher())
            {{-- Upcoming Live Sessions --}}
            @include('design_1.panel.dashboard.instructor.includes.upcoming_live_sessions')

            {{-- Review Student Quizzes --}}
            @include('design_1.panel.dashboard.instructor.includes.review_student_quizzes')

            {{-- Open Meetings --}}
            @include('design_1.panel.dashboard.instructor.includes.open_meetings')

        @else
            {{-- Organization --}}

            {{-- Top Instructors --}}
            @include('design_1.panel.dashboard.instructor.includes.top_instructors')

            {{-- Top Students --}}
            @include('design_1.panel.dashboard.instructor.includes.top_students')

        @endif
    </div>
</div>
