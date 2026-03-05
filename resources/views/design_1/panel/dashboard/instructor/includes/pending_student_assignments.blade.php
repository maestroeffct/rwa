<div class="bg-white p-16 rounded-24 w-100 mt-24">
    <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.pending_student_assignments') }}</h4>

    @if(!empty($pendingStudentAssignments) and count($pendingStudentAssignments))
        <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16">
            @foreach($pendingStudentAssignments as $pendingAssignmentHistory)
                <div class="student-dashboard__my-assignment-box d-flex flex-column bg-gray-100 p-16 rounded-16">
                    <div class="size-48 rounded-8 bg-gray-100">
                        <img src="{{ $pendingAssignmentHistory->assignment->webinar->getIcon() }}" alt="" class="img-cover rounded-8">
                    </div>

                    <a href="{{ "{$pendingAssignmentHistory->assignment->webinar->getLearningPageUrl()}?type=assignment&item={$pendingAssignmentHistory->assignment->id}&student={$pendingAssignmentHistory->student_id}" }}" target="_blank" class="">
                        <h5 class="font-13 text-dark mt-12 mb-24 ">{{ $pendingAssignmentHistory->assignment->title }}</h5>
                    </a>

                    @php
                        $pendingAssignmentHistoryStudent = $pendingAssignmentHistory->student;
                    @endphp

                    <div class="d-flex align-items-center mt-auto">
                        <div class="size-32 rounded-circle">
                            <img src="{{ $pendingAssignmentHistoryStudent->getAvatar(32) }}" alt="" class="img-cover rounded-circle">
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-weight-bold font-12 text-dark">{{ truncate($pendingAssignmentHistoryStudent->full_name, 16) }}</span>
                            <span class="d-block font-12 text-gray-500 mt-4">{{ dateTimeFormat($pendingAssignmentHistory->created_at, 'j M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="d-flex-center flex-column text-center p-32 bg-gray-100 border-dashed border-gray-200 rounded-16 mt-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-clipboard-text class="icons text-primary" width="24px" height="24px"/>
            </div>

            <h5 class="font-14 text-dark mt-12">{{ trans('update.no_assignment') }}</h5>
            <div class="mt-4 font-12 text-gray-500">{!! nl2br(trans('update.instructor_dashboard_not_assignment_desc_hint')) !!}</div>
        </div>
    @endif

</div>
