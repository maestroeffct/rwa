@php
    $cardUser = !empty($answer) ? $answer->user : $courseForum->user;
@endphp

<div class="card-with-mask position-relative mt-24">
    <div class="mask-8-white"></div>

    <div class="position-relative learning-page__forum-answer-card z-index-3 d-flex bg-white p-12 rounded-16">
        {{-- User Info --}}
        <div class="learning-page__forum-answer-card-user-info position-relative d-flex-center flex-column text-center bg-gray-100 rounded-12 py-40 px-24 h-100">

            <div class="d-flex-center size-96 rounded-circle {{ (!empty($answer) and $answer->resolved) ? 'bg-primary' : 'bg-transparent' }}">
                <div class="d-flex-center size-80 rounded-circle bg-white">
                    <div class="size-64 rounded-circle bg-gray-100">
                        <img src="{{ $cardUser->getAvatar(64) }}" class="img-cover rounded-circle" alt="{{ $cardUser->full_name }}">
                    </div>
                </div>
            </div>

            <h4 class="font-14 text-dark mt-8 font-weight-bold">{{ $cardUser->full_name }}</h4>

            <div class="d-flex-center mt-8 py-4 px-8 rounded-16 border-gray-200 backdrop-filter-blur-4 font-12 text-gray-500">
                @if($cardUser->isUser())
                    {{ trans('quiz.student') }}
                @elseif($cardUser->isTeacher())
                    {{ trans('public.instructor') }}
                @elseif($cardUser->isOrganization())
                    {{ trans('home.organization') }}
                @elseif($cardUser->isAdmin())
                    {{ trans('panel.staff') }}
                @endif
            </div>

            @if(!empty($answer) and $answer->pin)
                <div class="answer-pined" data-tippy-content="{{ trans('update.pined') }}">
                    <x-iconsax-lin-paperclip-2 class="icons text-warning" width="20px" height="20px"/>
                </div>
            @endif
        </div>
        {{-- Content --}}
        <div class="d-flex flex-column flex-1 py-8 pl-16 pr-8">
            @if(empty($answer))
                <h4 class="font-14 text-dark mb-12">{{ $courseForum->title }}</h4>
            @endif

            <div class="text-gray-500 white-space-nowrap">{{ !empty($answer) ? $answer->description : $courseForum->description }}</div>

            <div class="mt-auto">

                @if(empty($answer) and !empty($courseForum->attach))
                    <div class="d-flex flex-wrap gap-8">
                        <a href="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/downloadAttach" target="_blank" class="d-flex-center p-8 rounded-8 bg-white border-gray-300 font-12 text-gray-500">
                            <x-iconsax-lin-document-download class="icons text-gray-400" width="16px" height="16px"/>
                            <span class="ml-4">{{ trans('update.attachment') }}</span>
                        </a>
                    </div>
                @endif

                @if(!empty($answer) and $answer->resolved)
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="d-flex-center p-4 pr-8 rounded-32 bg-primary">
                            <x-iconsax-lin-verify class="icons text-white" width="16px" height="16px"/>
                            <span class="ml-4 font-12 text-white">{{ trans('update.resolved') }}</span>
                        </div>
                    </div>
                @endif

                <div class="d-flex align-items-center justify-content-between mt-16 pt-16 border-top-gray-200">
                    <span class="font-12 text-gray-500">{{ dateTimeFormat(!empty($answer) ? $answer->created_at : $courseForum->created_at,'j M Y | H:i') }}</span>

                    <div class="d-flex align-items-center gap-20">
                        @if(empty($answer) and $user->id == $courseForum->user_id)
                            <div class="js-forum-question-action cursor-pointer"
                                 data-title="{{ trans('update.edit_question') }}"
                                 data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/edit"
                            >
                                <x-iconsax-lin-edit class="icons text-gray-500" width="20px" height="20px"/>
                            </div>
                        @elseif(!empty($answer))
                            @if($course->isOwner($user->id))
                                @if($answer->pin)
                                    <div class="js-answer-action-btn cursor-pointer"
                                         data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/un_pin"
                                    >
                                        <x-iconsax-lin-paperclip-2 class="icons text-primary" width="20px" height="20px"/>
                                    </div>
                                @else
                                    <div class="js-answer-action-btn cursor-pointer"
                                         data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/pin"
                                    >
                                        <x-iconsax-lin-paperclip-2 class="icons text-gray-500" width="20px" height="20px"/>
                                    </div>
                                @endif
                            @endif

                            @if($course->isOwner($user->id) or $user->id == $courseForum->user_id)
                                @if($answer->resolved)
                                    <div class="js-answer-action-btn cursor-pointer"
                                         data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/mark_as_not_resolved"
                                    >
                                        <x-iconsax-lin-verify class="icons text-primary" width="20px" height="20px"/>
                                    </div>
                                @else
                                    <div class="js-mark-as-resolved-btn cursor-pointer"
                                         data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/mark-as-resolved"
                                         data-msg="{{ trans('update.mark_as_resolved') }}"
                                         data-confirm="{{ trans('update.confirm') }}"
                                    >
                                        <x-iconsax-lin-verify class="icons text-gray-500" width="20px" height="20px"/>
                                    </div>
                                @endif
                            @endif

                            @if($user->id == $answer->user_id)
                                <div class="js-edit-forum-answer cursor-pointer"
                                     data-action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers/{{ $answer->id }}/edit"
                                     data-msg="{{ trans('update.edit_answer') }}"
                                     data-confirm="{{ trans('update.submit_reply') }}"
                                >
                                    <x-iconsax-lin-edit class="icons text-gray-500" width="20px" height="20px"/>
                                </div>
                            @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
