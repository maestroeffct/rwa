<div class="bg-white p-16 rounded-24">

    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between p-16 rounded-12 bg-gray-100 border-gray-200">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-40 bg-gray-300 rounded-12">
                <x-iconsax-bul-teacher class="icons text-gray-500" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.curriculum_overview') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.this_course_includes_n_modules_n_lessons_and_n_hours_of_materials', ['chapters' => $course->chapters->count(), 'sections' => $webinarContentCount, 'hours' => convertMinutesToHourAndMinute($course->getAllChaptersDurations())]) }}</p>
            </div>
        </div>

        @if($hasBought or !empty($course->getInstallmentOrder()))
            <a href="{{ $course->getLearningPageUrl() }}" class="d-flex align-items-center text-gray-500 mt-16 mt-lg-0">
                <x-iconsax-lin-arrow-right class="icons " width="16px" height="16px"/>
                <span class="ml-4">{{ trans('update.learning_page') }}</span>
            </a>
        @endif
    </div>


    {{-- Chapters --}}

    @if(!empty($course->chapters) and count($course->chapters))
        <section class="">
            @include('design_1.web.courses.show.tabs.contents.chapters')
        </section>
    @endif

    {{-- Sessions --}}
    @if(!empty($sessionsWithoutChapter) and count($sessionsWithoutChapter))
        <section class="mt-16" id="sessionsAccordion">
            @foreach($sessionsWithoutChapter as $session)
                @include('design_1.web.courses.show.tabs.contents.sessions' , ['session' => $session, 'accordionParent' => 'sessionsAccordion'])
            @endforeach
        </section>
    @endif

    {{-- Files --}}
    @if(!empty($filesWithoutChapter) and count($filesWithoutChapter))
        <section class="mt-16" id="filesAccordion">
            @foreach($filesWithoutChapter as $file)
                @include('design_1.web.courses.show.tabs.contents.files' , ['file' => $file, 'accordionParent' => 'filesAccordion'])
            @endforeach
        </section>
    @endif

    {{-- TextLessons --}}

    @if(!empty($textLessonsWithoutChapter) and count($textLessonsWithoutChapter))
        <section class="mt-16" id="textLessonsAccordion">
            @foreach($textLessonsWithoutChapter as $textLesson)
                @include('design_1.web.courses.show.tabs.contents.text_lessons' , ['textLesson' => $textLesson, 'accordionParent' => 'textLessonsAccordion'])
            @endforeach
        </section>
    @endif


    {{-- Quizzes --}}
    @if(!empty($quizzes) and $quizzes->count() > 0)
        <section class="mt-16" id="quizAccordion">
            @foreach($quizzes as $quiz)
                @include('design_1.web.courses.show.tabs.contents.quiz' , ['quiz' => $quiz, 'accordionParent' => 'quizAccordion'])
            @endforeach
        </section>
    @endif

    {{-- Certificates --}}
    @include('design_1.web.courses.show.tabs.contents.all_certificates')

</div>
