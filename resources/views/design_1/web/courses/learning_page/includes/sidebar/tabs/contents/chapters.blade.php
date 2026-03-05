@if(!empty($course->chapters) and count($course->chapters))
    <div id="chaptersAccordion">
        @foreach($course->chapters as $chapter)
            <div class="js-accordion-parent accordion p-12 rounded-20 bg-gray-100 mb-16">
                <div class="accordion__title d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center cursor-pointer" href="#collapseChapter{{ $chapter->id }}" data-parent="#chaptersAccordion" role="button" data-toggle="collapse">
                        <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                            <x-iconsax-bul-category class="icons text-primary" width="24px" height="24px"/>
                        </div>
                        <div class="ml-8">
                            <div class="font-14 font-weight-bold">{{ $chapter->title }}</div>
                            <div class="d-flex align-items-center mt-4 font-12 text-gray-500">{{ $chapter->getTopicsCount(true) }} {{ trans('public.parts') }}</div>
                        </div>
                    </div>

                    <div class="js-accordion-collapse-arrow collapse-arrow-icon d-flex cursor-pointer" href="#collapseChapter{{ $chapter->id }}" data-parent="#chaptersAccordion" role="button" data-toggle="collapse">
                        <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
                    </div>
                </div>

                <div id="collapseChapter{{ $chapter->id }}" class="js-accordion-collapse accordion__collapse pt-0 mt-20 border-0 " role="tabpanel">
                    @if(!empty($chapter->chapterItems) and count($chapter->chapterItems))
                        @foreach($chapter->chapterItems as $chapterItem)
                            @if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession and !empty($chapterItem->session) and $chapterItem->session->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.session' , ['session' => $chapterItem->session, 'type' => \App\Models\WebinarChapter::$chapterSession])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file) and $chapterItem->file->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.file' , ['file' => $chapterItem->file, 'type' => \App\Models\WebinarChapter::$chapterFile])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson and !empty($chapterItem->textLesson) and $chapterItem->textLesson->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.text_lesson' , ['textLesson' => $chapterItem->textLesson, 'type' => \App\Models\WebinarChapter::$chapterTextLesson])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment and !empty($chapterItem->assignment) and $chapterItem->assignment->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.assignment' ,['assignment' => $chapterItem->assignment])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz and !empty($chapterItem->quiz) and $chapterItem->quiz->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.quiz' ,['quiz' => $chapterItem->quiz, 'type' => 'quiz'])
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
