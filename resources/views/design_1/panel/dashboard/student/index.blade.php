<div class="row">
    <div class="col-12 col-lg-6">
        {{-- Hello Box --}}
        @include('design_1.panel.dashboard.student.includes.hello_box')

        {{-- Courses Overview --}}
        <div class="{{ (!empty($helloBox['continueLearningCourses']) and count($helloBox['continueLearningCourses'])) ? (!empty($isRtl) ? 'mt-160' : 'mt-128') : 'mt-84' }}">
            @include('design_1.panel.dashboard.student.includes.courses_overview')
        </div>

        {{-- My Assignments --}}
        @include('design_1.panel.dashboard.student.includes.my_assignments')

        {{-- Learning Activity --}}
        @include('design_1.panel.dashboard.student.includes.learning_activity')
    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        {{-- Subscribe Plan --}}
        @include('design_1.panel.dashboard.student.includes.subscribe_plan')

        {{-- Current Balance --}}
        @include('design_1.panel.dashboard.student.includes.current_balance')

        {{-- Noticeboard --}}
        @include('design_1.panel.dashboard.student.includes.noticeboard')

        {{-- Support Messages --}}
        @include('design_1.panel.dashboard.student.includes.support_messages')

        {{-- My Quizzes --}}
        @include('design_1.panel.dashboard.student.includes.my_quizzes')
    </div>

    <div class="col-12 col-lg-3 mt-32 mt-lg-0">
        {{-- Events Calendar --}}
        @include('design_1.panel.dashboard.student.includes.events_calendar')

        {{-- Upcoming Live Sessions --}}
        @include('design_1.panel.dashboard.student.includes.upcoming_live_sessions')

        {{-- Open Meetings --}}
        @include('design_1.panel.dashboard.student.includes.open_meetings')


    </div>
</div>
