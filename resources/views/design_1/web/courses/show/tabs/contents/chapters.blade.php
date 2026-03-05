<div id="chaptersAccordion">
    @foreach($course->chapters as $chapter)
        @if((!empty($chapter->chapterItems) and count($chapter->chapterItems)) or (!empty($chapter->quizzes) and count($chapter->quizzes)))
            <div class="accordion p-12 rounded-12 border-gray-200 bg-white mt-16">
                <div class="accordion__title d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center cursor-pointer" href="#collapseChapter{{ $chapter->id }}" data-parent="#chaptersAccordion" role="button" data-toggle="collapse">
                        <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                            <x-iconsax-bul-category class="icons text-primary" width="24px" height="24px"/>
                        </div>
                        <div class="ml-8">
                            <div class="font-14 font-weight-bold">{{ $chapter->title }}</div>
                            <div class="d-flex align-items-center mt-4 font-12 text-gray-500">{{ $chapter->getTopicsCount(true) }} {{ trans('public.parts') }} {{ !empty($chapter->getDuration()) ? ' | ' . convertMinutesToHourAndMinute($chapter->getDuration()) .' '. trans('home.hours') : '' }}</div>
                        </div>
                    </div>

                    <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseChapter{{ $chapter->id }}" data-parent="#chaptersAccordion" role="button" data-toggle="collapse">
                        <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
                    </div>
                </div>

                <div id="collapseChapter{{ $chapter->id }}" class="accordion__collapse border-0 " role="tabpanel">
                    @if(!empty($chapter->chapterItems) and count($chapter->chapterItems))
                        @foreach($chapter->chapterItems as $chapterItem)
                            @if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession and !empty($chapterItem->session) and $chapterItem->session->status == 'active')
                                @include('design_1.web.courses.show.tabs.contents.sessions' , ['session' => $chapterItem->session, 'accordionParent' => 'chaptersAccordion'])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file) and $chapterItem->file->status == 'active')
                                @include('design_1.web.courses.show.tabs.contents.files' , ['file' => $chapterItem->file, 'accordionParent' => 'chaptersAccordion'])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson and !empty($chapterItem->textLesson) and $chapterItem->textLesson->status == 'active')
                                @include('design_1.web.courses.show.tabs.contents.text_lessons' , ['textLesson' => $chapterItem->textLesson, 'accordionParent' => 'chaptersAccordion'])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment and !empty($chapterItem->assignment) and $chapterItem->assignment->status == 'active')
                                @include('design_1.web.courses.show.tabs.contents.assignment' ,['assignment' => $chapterItem->assignment, 'accordionParent' => 'chaptersAccordion'])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz and !empty($chapterItem->quiz) and $chapterItem->quiz->status == 'active')
                                @include('design_1.web.courses.show.tabs.contents.quiz' ,['quiz' => $chapterItem->quiz, 'accordionParent' => 'chaptersAccordion'])
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    @endforeach
</div>

