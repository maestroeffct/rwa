<div class="card-with-mask position-relative">
    <div class="mask-8-white"></div>

    <div class="position-relative bg-white p-16 rounded-24 z-index-3">
        <h3 class="font-14 text-dark mb-16">{{ $assignment->title }}</h3>

        {{-- Top Status --}}
        @include('design_1.web.courses.learning_page.includes.contents.assignment.top_status')

        {{-- Top Stats --}}
        @include('design_1.web.courses.learning_page.includes.contents.assignment.top_stats')

        {{-- About Assignment --}}
        <h4 class="mt-16 font-14 text-dark">{{ trans('update.about_assignment') }}</h4>
        <div class="mt-8 text-gray-500">{!! $assignment->description !!}</div>

        {{-- Attachments --}}
        @include('design_1.web.courses.learning_page.includes.contents.assignment.attachments')

        {{-- Instructor Rate --}}
        @include('design_1.web.courses.learning_page.includes.contents.assignment.instructor_rate')
    </div>
</div>

<div class="card-with-mask position-relative mt-28 mb-56">
    <div class="mask-8-white"></div>

    <div class="position-relative bg-white pt-16 rounded-24 z-index-3">
        <div class="px-16">
            <h3 class="font-14 text-dark mb-16">{{ trans('update.assignment_history') }}</h3>
        </div>

        <div class="learning-page-assignment-history d-flex align-items-start flex-column flex-lg-row mt-16 border-top-gray-200">
            {{-- Left --}}
            <div class="learning-page-assignment-history-left-side p-16 h-100">
                {{-- Status --}}
                @if(
                        $user->id != $assignment->creator_id and
                        (
                            $assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$passed or
                            $assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$notPassed or
                            !$assignmentDeadline or
                            (
                                !$checkHasAttempts and !empty($assignment->attempts) and $submissionTimes >= $assignment->attempts
                            )
                        )
                    )
                    @include('design_1.web.courses.learning_page.includes.contents.assignment.left_status')
                @else
                    {{-- Send Assignment Form --}}
                    @include('design_1.web.courses.learning_page.includes.contents.assignment.form')
                @endif
            </div>

            {{-- Right --}}
            <div class="learning-page-assignment-history-right-side border-left-gray-200 h-100 py-16">
                {{-- messages --}}
                @include('design_1.web.courses.learning_page.includes.contents.assignment.messages')
            </div>
        </div>
    </div>
</div>
