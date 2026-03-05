<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}" class="accordion bg-white border-gray-200 p-12 rounded-16 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between " role="tab" id="quiz_{{ !empty($quizInfo) ? $quizInfo->id :'record' }}">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseQuiz{{ !empty($quizInfo) ? $quizInfo->id :'record' }}" aria-controls="collapseQuiz{{ !empty($quizInfo) ? $quizInfo->id :'record' }}" data-parent="#{{ !empty($chapter) ? 'chapterContentAccordion'.$chapter->id : 'quizzesAccordion' }}" role="button" data-toggle="collapse" aria-expanded="true">
            <div class="d-flex mr-8">
                <x-iconsax-lin-award class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ !empty($quizInfo) ? $quizInfo->title : trans('public.add_new_quizzes') }}</div>
        </div>

        <div class="d-flex align-items-center">
            @if(!empty($quizInfo))

                @if($quizInfo->status != \App\Models\WebinarChapter::$chapterActive)
                    <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8">{{ trans('public.disabled') }}</span>
                @endif

                <div class="js-change-content-chapter cursor-pointer mr-12" data-item-id="{{ $quizInfo->id }}" data-item-type="{{ \App\Models\WebinarChapterItem::$chapterQuiz }}" data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}" data-tippy-content="{{ trans('public.edit_chapter') }}">
                    <x-iconsax-lin-category-2 class="icons text-gray-500" width="20px" height="20px"/>
                </div>

                <div class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="{{ trans('update.sort') }}">
                    <x-iconsax-lin-arrow-3 class="icons text-gray-500" width="20px" height="20px"/>
                </div>

                <a href="/panel/quizzes/{{ $quizInfo->id }}/delete" class="delete-action d-flex text-gray-500 mr-12">
                    <x-iconsax-lin-trash class="icons text-gray-500" width="20px" height="20px"/>
                </a>
            @endif


            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseQuiz{{ !empty($quizInfo) ? $quizInfo->id :'record' }}" aria-controls="collapseQuiz{{ !empty($quizInfo) ? $quizInfo->id :'record' }}" data-parent="#quizzesAccordion" role="button" data-toggle="collapse" aria-expanded="true">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="20px" height="20px"/>
            </div>
        </div>
    </div>

    <div id="collapseQuiz{{ !empty($quizInfo) ? $quizInfo->id :'record' }}" class=" collapse @if(empty($quizInfo)) show @endif" role="tabpanel">

        <div class="row">
            {{-- Form --}}
            <div class="col-12 col-lg-6">

                @include('design_1.panel.quizzes.create.quiz_form',
                    [
                        'inWebinarPage' => true,
                        'selectedWebinar' => $webinar,
                        'quiz' => $quizInfo ?? null,
                        'chapters' => $webinar->chapters,
                        'webinarChapterPages' => !empty($webinarChapterPages)
                    ]
                )
            </div>

            <div class="col-12 col-lg-6 pt-16">
                @include('design_1.panel.quizzes.create.questions_list', [
                        'inWebinarPage' => true,
                        'selectedWebinar' => $webinar,
                        'quiz' => $quizInfo ?? null,
                        'quizQuestions' => !empty($quizInfo) ? $quizInfo->quizQuestions : [],
                        'chapters' => $webinar->chapters,
                        'webinarChapterPages' => !empty($webinarChapterPages)
                ])
            </div>
        </div>
    </div>
</li>
