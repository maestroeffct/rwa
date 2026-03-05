@if(!empty($mostActiveAssignments) and count($mostActiveAssignments))
    <div class="mt-28">
        <div class="">
            <h3 class="font-16 text-dark">{{ trans('update.most_active_assignments') }}</h3>
            <p class="mt-4 font-12 text-gray-500">{{ trans('update.check_assignments_with_the_most_submitted_cases') }}</p>
        </div>

        <div class="row">

            @foreach($mostActiveAssignments as $mostActiveAssignment)
                <a href="/panel/assignments/{{ $mostActiveAssignment->id }}/students" class="col-12 col-md-6 col-lg-3 mt-16">
                    <div class="position-relative most-active-assignment-card">
                        <div class="most-active-assignment-card__mask"></div>

                        <div class="position-relative z-index-2 bg-white p-20 rounded-16">
                            <div class="d-flex align-items-center">
                                @if(!empty($mostActiveAssignment->icon))
                                    <div class="d-flex-center size-64">
                                        <img src="{{ $mostActiveAssignment->icon }}" alt="" class="img-fluid">
                                    </div>
                                @else
                                    <div class="d-flex-center size-64 rounded-circle bg-gray-100">
                                        <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
                                    </div>
                                @endif

                                <div class="ml-8">
                                    <h6 class="font-14 font-weight-bold text-dark">{{ truncate($mostActiveAssignment->title, 28) }}</h6>

                                    @if(!empty($mostActiveAssignment->webinar))
                                        <p class="font-12 text-gray-500 mt-4">{{ truncate($mostActiveAssignment->webinar->title, 32) }}</p>
                                    @endif
                                </div>
                            </div>
 
                            <div class="d-flex align-items-center justify-content-between mt-20 pt-20 border-top-gray-100">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center overlay-avatars overlay-avatars-24">
                                        @if(!empty($mostActiveAssignment->someStudents) and count($mostActiveAssignment->someStudents))
                                            @foreach($mostActiveAssignment->someStudents as $someStudent)
                                                <div class="overlay-avatars__item size-40 rounded-circle border-0">
                                                    <img src="{{ $someStudent->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="ml-8">
                                        <span class="d-block font-weight-bold text-dark">{{ $mostActiveAssignment->instructor_assignment_histories_count }}</span>
                                        <span class="d-block font-12 text-gray-500 mt-4">{{ trans('update.submissions') }}</span>
                                    </div>
                                </div>

                                <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
                            </div>
                        </div>
                    </div>
</a>
            @endforeach

        </div>
    </div>
@endif
