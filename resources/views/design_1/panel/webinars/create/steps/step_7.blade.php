@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('quiz.new_quiz') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.create_a_quiz_and_assign_it_to_the_course') }}</p>
            </div>
        </div>

        <div class="js-add-course-content-btn d-flex align-items-center cursor-pointer" data-type="quiz" data-chapter="" data-target-el-id="jsQuizzesAccordionsLists">
            <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
            <span class="ml-4 text-primary font-14">{{ trans('quiz.new_quiz') }}</span>
        </div>
    </div>


    <div id="jsQuizzesAccordionsLists" class="js-quizzes-accordions-lists mt-16">
        @if(!empty($webinar->quizzes) and count($webinar->quizzes))
            <ul class="draggable-content-lists quizzes-draggable-lists" data-path="" data-drag-class="quizzes-draggable-lists">
                @foreach($webinar->quizzes as $quizInfo)
                    @include('design_1.panel.webinars.create.includes.accordions.quiz',['quizInfo' => $quizInfo])
                @endforeach
            </ul>
        @else
            <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                    <x-iconsax-bul-clipboard-tick class="icons text-primary" width="32px" height="32px"/>
                </div>
                <h3 class="font-16 font-weight-bold mt-12">{{ trans('public.quizzes_no_result') }}</h3>
                <p class="mt-4 font-12 text-gray-500">{!! trans('public.quizzes_no_result_hint') !!}</p>
            </div>
        @endif
    </div>

</div>

<div id="newQuizForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.quiz',['webinar' => $webinar, 'quizInfo' => null, 'webinarChapterPages' => true])
</div>


@push('scripts_bottom')
    <script>
        var newChapterLang = '{{ trans('public.new_chapter') }}';
        var editChapterLang = '{{ trans('public.edit_chapter') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var quizzesSectionLang = '{{ trans('quiz.quizzes_section') }}';
        var newQuestionLang = '{{ trans('update.new_question') }}';
        var editQuestionLang = '{{ trans('update.edit_question') }}';
        var changeChapterLang = '{{ trans('update.change_chapter') }}';

    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script src="/assets/design_1/js/panel/quiz_create.min.js"></script>
@endpush
