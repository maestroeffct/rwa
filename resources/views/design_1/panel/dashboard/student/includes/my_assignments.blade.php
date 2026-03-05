<div class="bg-white p-16 rounded-24 w-100 mt-24">
    <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.my_assignments') }}</h4>

    @if(!empty($myAssignments) and count($myAssignments))
        <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16">
            @foreach($myAssignments as $myAssignment)
                <a href="{{ "{$myAssignment->webinar->getLearningPageUrl()}?type=assignment&item={$myAssignment->id}" }}" target="_blank" class="text-decoration-none">
                    <div class="student-dashboard__my-assignment-box d-flex flex-column bg-gray-100 p-16 rounded-16">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="size-48 rounded-8 bg-gray-100">
                                <img src="{{ $myAssignment->webinar->getIcon() }}" alt="" class="img-cover rounded-8">
                            </div>

                            @if(empty($myAssignment->assignmentHistory) or ($myAssignment->assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$notSubmitted))
                                <div class="p-8 rounded-8 bg-danger-30 text-danger font-8">{{ trans('update.not_submitted') }}</div>
                            @else
                                @switch($myAssignment->assignmentHistory->status)
                                    @case(\App\Models\WebinarAssignmentHistory::$passed)
                                        <div class="p-8 rounded-8 bg-success-30 text-success font-8">{{ trans('quiz.passed') }}</div>
                                        @break
                                    @case(\App\Models\WebinarAssignmentHistory::$pending)
                                        <div class="p-8 rounded-8 bg-warning-30 text-warning font-8">{{ trans('update.pending_review') }}</div>
                                        @break
                                    @case(\App\Models\WebinarAssignmentHistory::$notPassed)
                                        <div class="p-8 rounded-8 bg-warning-30 text-warning font-8">{{ trans('quiz.failed') }}</div>
                                        @break
                                @endswitch
                            @endif
                        </div>

                        <h5 class="font-13 text-dark mt-12 mb-24">{{ $myAssignment->title }}</h5>

                        @php
                            $myAssignmentTeacher = $myAssignment->webinar->teacher;
                        @endphp

                        <div class="d-flex align-items-center mt-auto">
                            <div class="size-32 rounded-circle">
                                <img src="{{ $myAssignmentTeacher->getAvatar(32) }}" alt="" class="img-cover rounded-circle">
                            </div>
                            <div class="ml-8">
                                <span class="d-block font-weight-bold font-12 text-dark">{{ truncate($myAssignmentTeacher->full_name, 16) }}</span>
                                <span class="d-block font-12 text-gray-500 mt-4">{{ dateTimeFormat($myAssignment->created_at, 'j M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="d-flex-center flex-column text-center p-32 bg-gray-100 border-dashed border-gray-200 rounded-16 mt-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-clipboard-text class="icons text-primary" width="24px" height="24px"/>
            </div>

            <h5 class="font-14 text-dark mt-12">{{ trans('update.no_assignment') }}</h5>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.you_dont_have_not_submitted_or_pending_review_assignments') }}</div>
        </div>
    @endif

</div>
