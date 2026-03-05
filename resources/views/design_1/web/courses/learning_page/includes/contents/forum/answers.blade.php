<div class="card-with-mask position-relative">
    <div class="mask-8-white"></div>

    <div class="position-relative z-index-2 bg-white p-16 rounded-16">
        <div class="font-weight-bold text-dark">{{ $courseForum->title }}</div>

        <div class="d-flex align-items-center mt-12">
            <div class="size-40 rounded-circle bg-gray-100">
                <img src="{{ $courseForum->user->getAvatar(40) }}" class="img-cover rounded-circle" alt="{{ $courseForum->user->full_name }}">
            </div>
            <div class="ml-8 font-12 text-gray-500">
                <span class="d-block ">{{ trans('public.created_by') }}</span>
                <span class="d-block mt-2"><b>{{ $courseForum->user->full_name }}</b> {{ trans('update.on') }} {{ dateTimeFormat($courseForum->created_at, 'j M Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Question Details --}}
@include('design_1.web.courses.learning_page.includes.contents.forum.includes.answer_card')

@if(!empty($courseForum) and count($courseForum->answers))
    @foreach($courseForum->answers as $courseForumAnswer)
        @include('design_1.web.courses.learning_page.includes.contents.forum.includes.answer_card', ['answer' => $courseForumAnswer])
    @endforeach
@endif

<div class="card-with-mask position-relative my-24">
    <div class="mask-8-white"></div>
    <div class="position-relative z-index-2 bg-white py-16 rounded-24">
        <div class="px-16 card-before-line">
            <h4 class="font-14 text-dark">{{ trans('update.write_a_reply') }}</h4>
        </div>

        <form action="{{ $course->getForumPageUrl() }}/{{ $courseForum->id }}/answers" method="post">
            <div class="px-16 mt-16">
                <div class="form-group mb-0">
                    <textarea name="description" class="js-ajax-description form-control" rows="8"></textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-16 pt-16 border-top-gray-100">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 rounded-12 bg-gray-300">
                            <x-iconsax-bol-messages class="icons text-gray-500" width="20px" height="20px"/>
                        </div>
                        <div class="ml-8">
                            <h6 class="font-14 text-dark">{{ trans('update.have_an_idea') }}</h6>
                            <div class="mt-4 font-12 text-gray-500">{{ trans('update.share_it_with_others_and_get_involved') }}</div>
                        </div>
                    </div>

                    <button type="button" class="js-reply-forum-question btn btn-primary btn-lg">{{ trans('update.submit_reply') }}</button>
                </div>
            </div>
        </form>

    </div>
</div>

{{--  --}}
@if(!empty($relatedForums) and count($relatedForums))
    <div class="my-24">
        <h4 class="font-14 text-dark">{{ trans('update.involve_in_other_open_questions') }}</h4>

        <div class="d-grid grid-columns-4 mt-12 gap-16">
            @foreach($relatedForums as $relatedForum)
                <div class="learning-page__related-forum-card card-with-mask position-relative">
                    <div class="mask-8-white"></div>
                    <div class="position-relative z-index-2 d-flex align-items-center justify-content-between bg-white p-16 rounded-16">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-56 rounded-circle bg-gray-100">
                                <div class="size-40 rounded-circle">
                                    <img src="{{ $relatedForum->user->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
                                </div>
                            </div>
                            <div class="ml-8">
                                <a href="{{ $course->getForumPageUrl() }}/{{ $relatedForum->id }}/answers" class="">
                                    <h5 class="font-14 text-dark">{{ truncate($relatedForum->title, 21) }}</h5>
                                </a>

                                <div class="mt-4 font-12 text-gray-500"><b>{{ truncate($relatedForum->user->full_name, 9) }}</b> {{ trans('update.on') }} {{ dateTimeFormat($relatedForum->created_at, 'j M Y H:i') }}</div>
                            </div>
                        </div>

                        @if($relatedForum->answers_count > 0)
                            <div class="d-flex-center bg-gray-100 rounded-circle ml-16 p-4 font-12 text-gray-500">{{ $relatedForum->answers_count }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
