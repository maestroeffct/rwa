@if(!empty($course->quizzes) and $course->quizzes->count())
    @foreach($course->quizzes as $quizRow)
        @php
            $checkSequenceContent = $quizRow->checkSequenceContent();
            $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));
            $hasSequenceContentError = (!empty($checkSequenceContent) and $sequenceContentHasError);

            $quizStatus = $quizRow->getStatusByUser();
        @endphp


        <div
                class="sidebar-content-item d-flex align-items-center justify-content-between mb-12 p-12 rounded-20 bg-gray-100 cursor-pointer {{ $hasSequenceContentError ? 'js-sequence-content-error-modal' : 'js-content-tab-item' }}"
                data-type="quiz"
                data-id="{{ $quizRow->id }}"
                data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
                data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
        >
            <div class="d-flex align-items-center">
                <div class="position-relative d-flex-center size-48 rounded-12 bg-primary-20">
                    <x-iconsax-bul-clipboard-tick class="icons text-primary" width="24px" height="24px"/>

                    @if($hasSequenceContentError)
                        <div class="sidebar-item-lock-icon d-flex-center rounded-circle bg-white">
                            <x-iconsax-bol-lock-circle class="icons text-danger" width="16px" height="16px"/>
                        </div>
                    @endif
                </div>

                <div class="ml-8">
                    <div class="font-weight-bold font-14 text-dark">{{ truncate($quizRow->title, 27) }}</div>

                    <div class="d-flex align-items-center mt-4 font-12 text-gray-500">
                        <span class="">{{ $quizRow->quizQuestions->count() }} {{ trans('public.questions') }}</span>
                        <span class="sidebar-item-dot-separator mx-4 bg-gray-300"></span>
                        <span class="">{{ $quizRow->time }} {{ trans('update.mins') }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-8">
                @if($quizStatus == "passed")
                    <div class="d-flex-center px-8 py-4 rounded-32 bg-success-30 text-success font-12">{{ trans('quiz.passed') }}</div>
                @elseif($quizStatus == "failed")
                    <div class="d-flex-center px-8 py-4 rounded-32 bg-danger-30 text-danger font-12">{{ trans('quiz.failed') }}</div>
                @elseif($quizStatus == "waiting")
                    <div class="d-flex-center px-8 py-4 rounded-32 bg-warning-30 text-warning font-12">{{ trans('quiz.waiting') }}</div>
                @elseif($quizStatus == "not_participated")
                    <div class="d-flex-center px-8 py-4 rounded-32 bg-gray-200 text-gray-500 font-12">{{ trans('update.not_participated') }}</div>
                @endif
            </div>
        </div>

    @endforeach
@endif
