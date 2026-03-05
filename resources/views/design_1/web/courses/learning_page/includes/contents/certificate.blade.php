<div class="bg-white p-16 rounded-24">
    <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160 mb-20">

        @if($userIsInstructor)
            @include('design_1.web.courses.learning_page.includes.contents.certificate.for_instructor')
        @else
            @if($type == "quiz_certificate")
                @include('design_1.web.courses.learning_page.includes.contents.certificate.quiz_certificate')
            @elseif($type == "course_certificate")
                @include('design_1.web.courses.learning_page.includes.contents.certificate.course_certificate')
            @endif
        @endif
    </div>
</div>
