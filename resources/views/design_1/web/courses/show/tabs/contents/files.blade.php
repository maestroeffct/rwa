@php
    $checkSequenceContent = $file->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));
@endphp

<div class="accordion bg-gray-100 border-gray-200 p-16 rounded-12 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseFiles{{ $file->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <div class="d-flex mr-8">
                @php
                    $fileIcon = !empty($file) ? $file->getIconXByType() : 'document';
                @endphp

                @svg("iconsax-lin-{$fileIcon}", ['height' => 20, 'width' => 20, 'class' => 'text-gray-500'])
            </div>

            <div class="font-14 font-weight-bold d-block">{{ $file->title }}</div>
        </div>

        <div class="d-flex align-items-center gap-12">
            @if($file->accessibility == 'free')
                <span class="px-8 py-4 bg-primary-20 text-primary font-12 rounded-8">{{ trans('public.free') }}</span>
            @endif

            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseFiles{{ $file->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </div>
        </div>
    </div>

    <div id="collapseFiles{{ $file->id }}" class="accordion__collapse border-0 " role="tabpanel">
        <div class="p-16 rounded-12 border-gray-200 bg-gray-100 mt-16">
            <div class="font-14 text-gray-500">
                {!! nl2br(clean($file->description)) !!}
            </div>

            @if(!empty($user) and $hasBought)
                <div class="d-flex align-items-center form-group mb-0 mt-20">
                    <div class="custom-switch mr-8">
                        <input type="checkbox"
                               name="passed_section_toggle[]"
                               id="fileReadToggle{{ $file->id }}"
                               data-item-name="file_id"
                               data-course-slug="{{ $course->slug }}"
                               value="{{ $file->id }}"
                               class="js-passed-section-toggle custom-control-input"
                            {{ ($sequenceContentHasError) ? 'disabled' : '' }}
                            {{ (!empty($file->checkPassedItem())) ? 'checked' : '' }}
                        >
                        <label class="custom-control-label cursor-pointer" for="fileReadToggle{{ $file->id }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer text-gray-500" for="fileReadToggle{{ $file->id }}">{{ trans('public.i_passed_this_lesson') }}</label>
                    </div>
                </div>
            @endif
        </div>

        <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-24 p-16 bg-white border-gray-200 rounded-12">
            <div class="course-content-separator-with-circles">
                <span class="circle-top"></span>
                <span class="circle-bottom"></span>
            </div>


            <div class="d-flex align-items-center">
                <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                    <x-iconsax-lin-ram-2 class="icons text-gray-500" width="16px" height="16px"/>
                </div>
                <div class="ml-8">
                    <span class="d-block font-12 text-gray-400">{{ trans('public.volume') }}</span>
                    <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ ($file->volume > 0) ? $file->getVolume() : '-' }}</span>
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
                        <x-iconsax-lin-play-circle class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('public.play') }}</span>
                    </button>
                @elseif($file->accessibility == 'paid')
                    @if(!empty($user) and $hasBought)
                        @if($file->downloadable)
                            <a href="{{ $course->getUrl() }}/file/{{ $file->id }}/download" class="btn btn-lg btn-primary">
                                <x-iconsax-lin-direct-inbox class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 text-white">{{ trans('home.download') }}</span>
                            </a>
                        @else
                            <a href="{{ $course->getLearningPageUrl() }}?type=file&item={{ $file->id }}" target="_blank" class="btn btn-lg btn-primary">
                                <x-iconsax-lin-play-circle class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 text-white">{{ trans('public.play') }}</span>
                            </a>
                        @endif
                    @else
                        <button type="button" class="btn btn-lg bg-gray-300 disabled {{ ((empty($user)) ? 'not-login-toast' : (!$hasBought ? 'not-access-toast' : '')) }}">
                            @if($file->downloadable)
                                <x-iconsax-lin-direct-inbox class="icons text-gray-500" width="16px" height="16px"/>
                                <span class="ml-4 text-gray-500">{{ trans('home.download') }}</span>
                            @else
                                <x-iconsax-lin-play-circle class="icons text-gray-500" width="16px" height="16px"/>
                                <span class="ml-4 text-gray-500">{{ trans('public.play') }}</span>
                            @endif
                        </button>
                    @endif
                @else
                    @if($file->downloadable)
                        <a href="{{ $course->getUrl() }}/file/{{ $file->id }}/download" class="btn btn-lg btn-primary">
                            <x-iconsax-lin-direct-inbox class="icons text-white" width="16px" height="16px"/>
                            <span class="ml-4 text-white">{{ trans('home.download') }}</span>
                        </a>
                    @else
                        @if(!empty($user) and $hasBought)
                            <a href="{{ $course->getLearningPageUrl() }}?type=file&item={{ $file->id }}" target="_blank" class="btn btn-lg btn-primary">
                                <x-iconsax-lin-play-circle class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 text-white">{{ trans('public.play') }}</span>
                            </a>
                        @elseif($file->storage == 'upload_archive')
                            <a href="/course/{{ $course->slug }}/file/{{ $file->id }}/showHtml" target="_blank" class="btn btn-lg btn-primary">
                                <x-iconsax-lin-play-circle class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 text-white">{{ trans('public.play') }}</span>
                            </a>
                        @elseif(in_array($file->storage, ['iframe', 'google_drive', 'dropbox']))
                            <a href="/course/{{ $course->slug }}/file/{{ $file->id }}/play" target="_blank" class="btn btn-lg btn-primary">
                                <x-iconsax-lin-play-circle class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 text-white">{{ trans('public.play') }}</span>
                            </a>
                        @elseif($file->isVideo())
                            <button type="button" data-id="{{ $file->id }}" data-title="{{ $file->title }}" class="js-play-video btn btn-lg btn-primary">
                                <x-iconsax-lin-play-circle class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 text-white">{{ trans('public.play') }}</span>
                            </button>
                        @else
                            <a href="{{ $file->file }}" target="_blank" class="btn btn-lg btn-primary">
                                <x-iconsax-lin-play-circle class="icons text-white" width="16px" height="16px"/>
                                <span class="ml-4 text-white">{{ trans('public.play') }}</span>
                            </a>
                        @endif
                    @endif
                @endif
            </div>

        </div>

    </div>
</div>
