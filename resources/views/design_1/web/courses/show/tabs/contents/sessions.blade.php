@php
    $checkSequenceContent = $session->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));
@endphp

<div class="accordion bg-gray-100 border-gray-200 p-16 rounded-12 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseSession{{ $session->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <div class="d-flex mr-8">
                <x-iconsax-lin-video class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ $session->title }}</div>
        </div>

        <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseSession{{ $session->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
        </div>
    </div>

    <div id="collapseSession{{ $session->id }}" class="accordion__collapse border-0 " role="tabpanel">
        <div class="p-16 rounded-12 border-gray-200 bg-gray-100 text-gray-500">
            <div class="font-14 text-gray-500">
                {!! nl2br(clean($session->description)) !!}
            </div>

            @if(!empty($user) and $hasBought)
                <div class="d-flex align-items-center form-group mb-0 mt-20">
                    <div class="custom-switch mr-8">
                        <input type="checkbox"
                               name="passed_section_toggle[]"
                               id="sessionReadToggle{{ $session->id }}"
                               data-item-name="session_id"
                               data-course-slug="{{ $course->slug }}"
                               value="{{ $session->id }}"
                               class="js-passed-section-toggle custom-control-input"
                            {{ (($session->date < time()) or $sequenceContentHasError) ? 'disabled' : '' }}
                            {{ (!empty($session->checkPassedItem())) ? 'checked' : '' }}
                        >
                        <label class="custom-control-label cursor-pointer" for="sessionReadToggle{{ $session->id }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer text-gray-500" for="sessionReadToggle{{ $session->id }}">{{ trans('public.i_passed_this_lesson') }}</label>
                    </div>
                </div>
            @endif
        </div>


        <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-24 p-16 bg-white border-gray-200 rounded-12">
            <div class="course-content-separator-with-circles">
                <span class="circle-top"></span>
                <span class="circle-bottom"></span>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-20 gap-lg-40">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.start_date') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ dateTimeFormat($session->date, 'j M Y | H:i') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.duration') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $session->duration }} {{ trans('public.minutes') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-16 mt-lg-0">
                @if($session->isFinished())
                    <button type="button" class="btn btn-lg bg-gray-300 disabled session-finished-toast">
                        <x-iconsax-lin-clock-1 class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('public.finished') }}</span>
                    </button>
                @elseif(empty($user))
                    <button type="button" class="btn btn-lg bg-gray-300 disabled not-login-toast">
                        <x-iconsax-lin-login class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('public.go_to_class') }}</span>
                    </button>
                @elseif($hasBought)
                    @if(!empty($checkSequenceContent) and $sequenceContentHasError)
                        <button
                            type="button"
                            class="btn btn-lg bg-gray-300 disabled js-sequence-content-error-modal"
                            data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
                            data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
                        >
                            <x-iconsax-lin-login class="icons text-gray-500" width="16px" height="16px"/>
                            <span class="ml-4 text-gray-500">{{ trans('public.go_to_class') }}</span>
                        </button>
                    @else
                        <a href="{{ $course->getLearningPageUrl() }}?type=session&item={{ $session->id }}" target="_blank" class="btn btn-primary btn-lg">
                            <x-iconsax-lin-login class="icons text-white" width="16px" height="16px"/>
                            <span class="ml-4 text-white">{{ trans('public.go_to_class') }}</span>
                        </a>
                    @endif
                @else
                    <button type="button" class="btn btn-lg bg-gray-300 disabled not-access-toast">
                        <x-iconsax-lin-login class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('public.go_to_class') }}</span>
                    </button>
                @endif
            </div>
        </div>

    </div>
</div>
