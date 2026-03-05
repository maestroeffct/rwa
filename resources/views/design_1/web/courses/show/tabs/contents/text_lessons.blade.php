@php
    $checkSequenceContent = $textLesson->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));
@endphp

<div class="accordion bg-gray-100 border-gray-200 p-16 rounded-12 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseTextLessons{{ $textLesson->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <div class="d-flex mr-8">
                <x-iconsax-lin-archive-book class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ $textLesson->title }}</div>
        </div>

        <div class="d-flex align-items-center gap-12">
            @if($textLesson->accessibility == 'free')
                <span class="px-8 py-4 bg-primary-20 text-primary font-12 rounded-8">{{ trans('public.free') }}</span>
            @endif

            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseTextLessons{{ $textLesson->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </div>
        </div>
    </div>

    <div id="collapseTextLessons{{ $textLesson->id }}" class="accordion__collapse border-0 " role="tabpanel">
        <div class="p-16 rounded-12 border-gray-200 bg-gray-100">
            <div class="font-14 text-gray-500">
                {!! nl2br(clean($textLesson->summary)) !!}
            </div>

            @if(!empty($user) and $hasBought)
                <div class="d-flex align-items-center form-group mb-0 mt-20">
                    <div class="custom-switch mr-8">
                        <input type="checkbox"
                               name="passed_section_toggle[]"
                               id="textLessonReadToggle{{ $textLesson->id }}"
                               data-item-name="text_lesson_id"
                               data-course-slug="{{ $course->slug }}"
                               value="{{ $textLesson->id }}"
                               class="js-passed-section-toggle custom-control-input"
                            {{ ($sequenceContentHasError) ? 'disabled' : '' }}
                            {{ (!empty($textLesson->checkPassedItem())) ? 'checked' : '' }}
                        >
                        <label class="custom-control-label cursor-pointer" for="textLessonReadToggle{{ $textLesson->id }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer text-gray-500" for="textLessonReadToggle{{ $textLesson->id }}">{{ trans('public.i_passed_this_lesson') }}</label>
                    </div>
                </div>
            @endif
        </div>


        <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-24 p-16 bg-white border-gray-200 rounded-12">
            <div class="course-content-separator-with-circles">
                <span class="circle-top"></span>
                <span class="circle-bottom"></span>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-40">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-timer-1 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.study_time') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $textLesson->study_time }} {{ trans('public.minutes') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-document-download class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.attachments') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $textLesson->attachments_count ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-16 mt-lg-0">
                @if(!empty($checkSequenceContent) and $sequenceContentHasError)
                    <button
                        type="button"
                        class="btn btn-lg bg-gray-300 disabled js-sequence-content-error-modal"
                        data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
                        data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
                    >
                        <x-iconsax-lin-book-1 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('public.read') }}</span>
                    </button>
                @elseif($textLesson->accessibility == 'paid')
                    @if(!empty($user) and $hasBought)
                        <a href="{{ $course->getLearningPageUrl() }}?type=text_lesson&item={{ $textLesson->id }}" target="_blank" class="btn btn-primary btn-lg">
                            <x-iconsax-lin-book-1 class="icons text-white" width="16px" height="16px"/>
                            <span class="ml-4 text-white">{{ trans('public.read') }}</span>
                        </a>
                    @else
                        <button type="button" class="btn btn-lg bg-gray-300 disabled {{ ((empty($user)) ? 'not-login-toast' : (!$hasBought ? 'not-access-toast' : '')) }}">
                            <x-iconsax-lin-book-1 class="icons text-gray-500" width="16px" height="16px"/>
                            <span class="ml-4 text-gray-500">{{ trans('public.read') }}</span>
                        </button>
                    @endif
                @else
                    @if(!empty($user) and $hasBought)
                        <a href="{{ $course->getLearningPageUrl() }}?type=text_lesson&item={{ $textLesson->id }}" target="_blank" class="btn btn-primary btn-lg">
                            <x-iconsax-lin-book-1 class="icons text-white" width="16px" height="16px"/>
                            <span class="ml-4 text-white">{{ trans('public.read') }}</span>
                        </a>
                    @else
                        <a href="{{ $course->getUrl() }}/lessons/{{ $textLesson->id }}/read" target="_blank" class="btn btn-primary btn-lg">
                            <x-iconsax-lin-book-1 class="icons text-white" width="16px" height="16px"/>
                            <span class="ml-4 text-white">{{ trans('public.read') }}</span>
                        </a>
                    @endif
                @endif
            </div>

        </div>

    </div>
</div>
