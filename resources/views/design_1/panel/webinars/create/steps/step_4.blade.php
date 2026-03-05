@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">


    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-category-2 class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('public.chapters') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.define_different_sections_and_organize_the_content_inside_them') }}</p>
            </div>
        </div>

        <div class="js-add-chapter d-flex align-items-center cursor-pointer" data-webinar-id="{{ $webinar->id }}">
            <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
            <span class="text-primary ml-4">{{ trans('public.new_chapter') }}</span>
        </div>
    </div>

    {{-- Chapter Items --}}
    @include('design_1.panel.webinars.create.includes.chapter_contents')

</div>



@if($webinar->isWebinar())
    <div id="newSessionForm" class="d-none">
        @include('design_1.panel.webinars.create.includes.accordions.session',['webinar' => $webinar])
    </div>
@endif

<div id="newFileForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.file',['webinar' => $webinar])
</div>

@if(getFeaturesSettings('new_interactive_file'))
    <div id="newInteractiveFileForm" class="d-none">
        @include('design_1.panel.webinars.create.includes.accordions.interactive_file',['webinar' => $webinar])
    </div>
@endif

<div id="newTextLessonForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.text_lesson',['webinar' => $webinar])
</div>

@if(getFeaturesSettings('webinar_assignment_status'))
    <div id="newAssignmentForm" class="d-none">
        @include('design_1.panel.webinars.create.includes.accordions.assignment',['webinar' => $webinar])
    </div>
@endif

<div id="newQuizForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.quiz',['webinar' => $webinar, 'quizInfo' => null, 'webinarChapterPages' => true])
</div>

<div id="changeChapterModalHtml" class="d-none">
    @include("design_1.panel.webinars.create.modals.change_chapter")
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
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    <script src="/assets/design_1/js/panel/quiz_create.min.js"></script>
@endpush
