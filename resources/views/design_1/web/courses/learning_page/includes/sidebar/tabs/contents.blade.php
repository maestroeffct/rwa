@if(
        (empty($sessionsWithoutChapter) or !count($sessionsWithoutChapter)) and
        (empty($textLessonsWithoutChapter) or !count($textLessonsWithoutChapter)) and
        (empty($filesWithoutChapter) or !count($filesWithoutChapter)) and
        (empty($course->chapters) or !count($course->chapters))
    )
    <div class="learning-page-forum-empty d-flex align-items-center justify-content-center flex-column">
        <div class="learning-page-forum-empty-icon d-flex align-items-center justify-content-center">
            <img src="/assets/default/img/learning/content-empty.svg" class="img-fluid" alt="">
        </div>

        <div class="d-flex align-items-center flex-column mt-10 text-center">
            <h3 class="font-20 font-weight-bold text-dark-blue text-center">{{ trans('update.learning_page_empty_content_title') }}</h3>
            <p class="font-14 font-weight-500 text-gray mt-5 text-center">{{ trans('update.learning_page_empty_content_hint') }}</p>
        </div>
    </div>
@else
    @if(!empty($sessionsWithoutChapter) and count($sessionsWithoutChapter))
        @foreach($sessionsWithoutChapter as $sessionRow)
            @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.session',['session' => $sessionRow, 'type' => \App\Models\WebinarChapter::$chapterSession])
        @endforeach
    @endif

    @if(!empty($textLessonsWithoutChapter) and count($textLessonsWithoutChapter))
        @foreach($textLessonsWithoutChapter as $textLessonRow)
            @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.text_lesson',['textLesson' => $textLessonRow, 'type' => \App\Models\WebinarChapter::$chapterTextLesson])
        @endforeach
    @endif

    @if(!empty($filesWithoutChapter) and count($filesWithoutChapter))
        @foreach($filesWithoutChapter as $fileRow)
            @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.file',['file' => $fileRow, 'type' => \App\Models\WebinarChapter::$chapterFile])
        @endforeach
    @endif

    @if(!empty($course->chapters) and count($course->chapters))
        @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.chapters')
    @endif
@endif
