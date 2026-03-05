@if(!empty($webinar->chapters) and count($webinar->chapters))
    <ul class="draggable-content-lists draggable-webinar-chapters"
        data-path="/panel/courses/order-items"
        data-order-table="webinar_chapters"
        data-drag-class="draggable-webinar-chapters"
    >

        @foreach($webinar->chapters as $chapter)
            <li data-id="{{ $chapter->id }}" data-chapter-order="{{ $chapter->order }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
                <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="webinar_chapter_{{ $chapter->id }}">

                    <div class="d-flex align-items-center cursor-pointer" href="#collapsePricePlan{{ $chapter->id }}" data-parent="#webinar_chaptersAccordion" role="button" data-toggle="collapse">
                        <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                            <x-iconsax-bul-category-2 class="icons text-primary" width="24px" height="24px"/>
                        </div>

                        <div class="ml-8">
                            <h5 class="font-14 font-weight-bold">{{ $chapter->title }}</h5>
                            <p class="mt-4 font-12 text-gray-500">{{ !empty($chapter->chapterItems) ? count($chapter->chapterItems) : 0 }} {{ trans('public.topic') }} | {{ convertMinutesToHourAndMinute($chapter->getDuration()) }} {{ trans('public.hr') }}</p>
                        </div>
                    </div>


                    <div class="d-flex align-items-center">

                        @if($chapter->status != \App\Models\WebinarChapter::$chapterActive)
                            <span class="px-8 py-4 bg-danger-30 text-danger font-12 mr-12">{{ trans('public.disabled') }}</span>
                        @endif

                        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center mr-12">
                            <button type="button" class="d-flex-center btn-transparent">
                                <x-iconsax-lin-add class="icons text-primary" width="20px" height="20px"/>
                            </button>

                            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
                                <ul class="my-8">

                                    @if($webinar->isWebinar())
                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <button type="button" class="js-add-course-content-btn" data-webinar-id="{{ $webinar->id }}" data-type="session" data-chapter="{{ $chapter->id }}">
                                                {{ trans('public.add_session') }}
                                            </button>
                                        </li>
                                    @endif

                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <button type="button" class="js-add-course-content-btn" data-webinar-id="{{ $webinar->id }}" data-type="file" data-chapter="{{ $chapter->id }}">
                                            {{ trans('public.add_file') }}
                                        </button>
                                    </li>

                                    @if(getFeaturesSettings('new_interactive_file'))
                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <button type="button" class="js-add-course-content-btn" data-webinar-id="{{ $webinar->id }}" data-type="new_interactive_file" data-chapter="{{ $chapter->id }}">
                                                {{ trans('update.new_interactive_file') }}
                                            </button>
                                        </li>
                                    @endif

                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <button type="button" class="js-add-course-content-btn" data-webinar-id="{{ $webinar->id }}" data-type="text_lesson" data-chapter="{{ $chapter->id }}">
                                            {{ trans('public.add_text_lesson') }}
                                        </button>
                                    </li>

                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <button type="button" class="js-add-course-content-btn" data-webinar-id="{{ $webinar->id }}" data-type="quiz" data-chapter="{{ $chapter->id }}">
                                            {{ trans('public.add_quiz') }}
                                        </button>
                                    </li>

                                    @if(getFeaturesSettings('webinar_assignment_status'))
                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <button type="button" class="js-add-course-content-btn" data-webinar-id="{{ $webinar->id }}" data-type="assignment" data-chapter="{{ $chapter->id }}">
                                                {{ trans('update.add_new_assignments') }}
                                            </button>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>

                        <button type="button" class="js-add-chapter btn-transparent text-gray-500 mr-12" data-webinar-id="{{ $webinar->id }}" data-chapter="{{ $chapter->id }}" data-tippy-content="{{ trans('public.edit_chapter') }}">
                            <x-iconsax-lin-edit-2 class="icons text-gray-500" width="20px" height="20px"/>
                        </button>

                        <a href="/panel/chapters/{{ $chapter->id }}/delete" class="delete-action text-gray-500 mr-12" data-tippy-content="{{ trans('public.delete') }}">
                            <x-iconsax-lin-trash class="icons text-gray-500" width="20px" height="20px"/>
                        </a>

                        <span class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="{{ trans('update.sort') }}">
                            <x-iconsax-lin-arrow-3 class="icons text-gray-500" width="20px" height="20px"/>
                        </span>

                        <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapsePricePlan{{ $chapter->id }}" data-parent="#webinar_chaptersAccordion" role="button" data-toggle="collapse">
                            <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="20px" height="20px"/>
                        </span>
                    </div>

                </div>

                <div id="collapsePricePlan{{ $chapter->id }}" class="accordion__collapse show" role="tabpanel">


                    <div class="accordion-content-wrapper mt-20" id="chapterContentAccordion{{ $chapter->id }}" role="tablist" aria-multiselectable="true">
                        @if(!empty($chapter->chapterItems) and count($chapter->chapterItems))
                            <ul class="draggable-content-lists draggable-lists-chapter-{{ $chapter->id }}"
                                data-path="/panel/courses/order-items"
                                data-order-table="webinar_chapter_items"
                                data-drag-class="draggable-lists-chapter-{{ $chapter->id }}"
                            >
                                @foreach($chapter->chapterItems as $chapterItem)
                                    @if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession and !empty($chapterItem->session))
                                        @include('design_1.panel.webinars.create.includes.accordions.session' ,['session' => $chapterItem->session , 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar])
                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file))
                                        @include('design_1.panel.webinars.create.includes.accordions.file' ,['file' => $chapterItem->file , 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar])
                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson and !empty($chapterItem->textLesson))
                                        @include('design_1.panel.webinars.create.includes.accordions.text_lesson' ,['textLesson' => $chapterItem->textLesson, 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar])
                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment and !empty($chapterItem->assignment))
                                        @include('design_1.panel.webinars.create.includes.accordions.assignment' ,['assignment' => $chapterItem->assignment , 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar])
                                    @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz and !empty($chapterItem->quiz))
                                        @include('design_1.panel.webinars.create.includes.accordions.quiz' ,['quizInfo' => $chapterItem->quiz , 'chapter' => $chapter, 'chapterItem' => $chapterItem, 'webinar' => $webinar])
                                    @endif

                                @endforeach
                            </ul>
                        @else
                            <div class="d-flex-center flex-column px-32 py-120 text-center">
                                <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                                    <x-iconsax-bul-note-2 class="icons text-primary" width="32px" height="32px"/>
                                </div>
                                <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.chapter_content_no_result') }}</h3>
                                <p class="mt-4 font-12 text-gray-500">{!! trans('update.chapter_content_no_result_hint') !!}</p>
                            </div>
                        @endif
                    </div>

                </div>
            </li>
        @endforeach

    </ul>
@else
    <div class="d-flex-center flex-column px-32 py-120 text-center">
        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
            <x-iconsax-bul-document class="icons text-primary" width="32px" height="32px"/>
        </div>
        <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.chapter_no_result') }}</h3>
        <p class="mt-4 font-12 text-gray-500">{!! trans('update.chapter_no_result_hint') !!}</p>
    </div>
@endif
