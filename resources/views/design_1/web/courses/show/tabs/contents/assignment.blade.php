@php
    $checkSequenceContent = $assignment->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));
@endphp

<div class="accordion bg-gray-100 border-gray-200 p-16 rounded-12 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseAssignment{{ $assignment->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <div class="d-flex mr-8">
                <x-iconsax-lin-clipboard-text class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ $assignment->title }}</div>
        </div>

        <div class="d-flex align-items-center gap-12">
            @if($assignment->accessibility == 'free')
                <span class="px-8 py-4 bg-primary-20 text-primary font-12 rounded-8">{{ trans('public.free') }}</span>
            @endif

            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseAssignment{{ $assignment->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </div>
        </div>
    </div>

    <div id="collapseAssignment{{ $assignment->id }}" class="accordion__collapse border-0 " role="tabpanel">
        <div class="p-16 rounded-12 border-gray-200 bg-gray-100">
            <div class="font-14 text-gray-500">
                {!! nl2br(clean($assignment->description)) !!}
            </div>
        </div>


        <div class="position-relative d-flex align-items-center justify-content-between mt-24 p-16 bg-white border-gray-200 rounded-12">
            <div class="course-content-separator-with-circles">
                <span class="circle-top"></span>
                <span class="circle-bottom"></span>
            </div>

            <div class="d-flex align-items-center gap-40">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-verify class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('quiz.minimum_grade') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $assignment->pass_grade ?? 0 }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-book class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.total_grade') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $assignment->grade ?? 0 }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-timer-1 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.deadline') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $assignment->deadline ?? 0 }} {{ trans('public.days') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-refresh-2 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.attempts') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $assignment->attempts ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="">
                @if(!empty($checkSequenceContent) and $sequenceContentHasError)
                    <button
                        type="button"
                        class="btn btn-lg bg-gray-300 disabled js-sequence-content-error-modal"
                        data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
                        data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
                    >
                        <x-iconsax-lin-edit-2 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('update.check') }}</span>
                    </button>
                @elseif(!empty($user) and $hasBought)
                    <a href="{{ $course->getLearningPageUrl() }}?type=assignment&item={{ $assignment->id }}" target="_blank" class="btn btn-lg btn-primary">
                        <x-iconsax-lin-edit-2 class="icons text-white" width="16px" height="16px"/>
                        <span class="ml-4 text-white">{{ trans('update.check') }}</span>
                    </a>
                @else
                    <button type="button" class="btn btn-lg bg-gray-300 disabled {{ ((empty($user)) ? 'not-login-toast' : (!$hasBought ? 'not-access-toast' : '')) }}">
                        <x-iconsax-lin-edit-2 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('update.check') }}</span>
                    </button>
                @endif
            </div>

        </div>

    </div>
</div>
