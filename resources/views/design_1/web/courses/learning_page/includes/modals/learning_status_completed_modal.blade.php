<div class="course-completed-modal position-relative">

    <div class="svg-covered-modal-body">
        <img src="/assets/design_1/img/courses/completed/2.svg" alt="" class="img-fluid">
    </div>

    <div class="position-relative z-index-3">
        <div class="d-flex-center flex-column text-center pt-48 pb-24 px-48">

            <div class="position-relative">
                <div class="svg-around-avatar">
                    <img src="/assets/design_1/img/courses/completed/1.svg" alt="" class="img-fluid">
                </div>

                <div class="position-relative z-index-3 d-flex-center size-96 bg-gray-100 rounded-circle">
                    <div class="d-flex-center size-80 bg-gray-200 rounded-circle">
                        <img src="{{ $user->getAvatar(80) }}" alt="" class="img-cover rounded-circle">
                    </div>
                </div>
            </div>


            <h3 class="mt-40 font-14 text-dark">{{ trans('update.you_did_it_perfectly') }} 🎉</h3>
            <div class="mt-8 font-12 text-gray-500">{{ trans('update.course_completed_modal_hint') }}</div>

            @if(!empty($courseCertificate))
                <a href="/panel/certificates/my-achievements/{{ $courseCertificate->id }}/show" target="_blank" class="btn btn-primary mt-16">{{ trans('update.download_certificate') }}</a>
            @else
                <a href="/panel/courses/purchases" target="_blank" class="btn btn-primary mt-16">{{ trans('update.my_courses') }}</a>
            @endif
        </div>
    </div>
</div>
