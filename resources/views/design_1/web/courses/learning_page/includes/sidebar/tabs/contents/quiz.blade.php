@php
    $checkSequenceContent = $quiz->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

    $quizPersonalNote = $quiz->personalNote()->where('user_id', $authUser->id)->first();
    $hasPersonalNote = (!empty($quizPersonalNote) and !empty($quizPersonalNote->note));

    $hasSequenceContentError = (!empty($checkSequenceContent) and $sequenceContentHasError);
@endphp


<div class="sidebar-content-item d-flex align-items-center justify-content-between mb-12 p-12 rounded-16 cursor-pointer js-content-tab-item {{ $hasSequenceContentError ? 'js-sequence-content-error-modal' : '' }}"
     data-type="{{ $type }}"
     data-id="{{ $quiz->id }}"
     data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
     data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
>
    <div class="d-flex align-items-center">
        <div class="position-relative d-flex-center size-48 rounded-12 bg-gray-200">
            <x-iconsax-bul-clipboard-tick class="icons text-gray-500" width="24px" height="24px"/>

            @if($hasSequenceContentError)
                <div class="sidebar-item-lock-icon d-flex-center rounded-circle bg-white">
                    <x-iconsax-bol-lock-circle class="icons text-danger" width="16px" height="16px"/>
                </div>
            @endif
        </div>

        <div class="ml-8">
            <span class=" d-block font-weight-bold font-14 text-dark">{{ truncate($quiz->title, 27) }}</span>
            <span class=" d-block font-12 text-gray-500 mt-4">{{ trans('public.quiz') }}</span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-8">
        @if($hasPersonalNote)
            <div class="">
                <x-iconsax-bul-note class="icons text-gray-500" width="16px" height="16px"/>
            </div>
        @endif

    </div>
</div>
