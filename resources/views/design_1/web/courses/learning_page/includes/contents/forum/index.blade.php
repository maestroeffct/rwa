<div class="bg-white p-16 rounded-24">
    {{-- Top Stats --}}
    @include('design_1.web.courses.learning_page.includes.contents.forum.includes.forum_top_stats')

    {{-- Have a Question? --}}
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-16 p-8 border-dashed border-gray-300 rounded-16">
        <div class="d-flex align-items-center p-4">
            <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                <x-iconsax-lin-message-question class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h4 class="font-14 text-dark">{{ trans('update.have_a_question?') }}</h4>
                <div class="mt-4 font-12 text-gray-500">{{ trans('update.ask_your_questions_in_course_forum_and_communicate_with_other_users') }}</div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-end gap-24 mt-16 mt-lg-0">
            <div class="cursor-pointer">
                <x-iconsax-lin-search-normal class="icons text-gray-500" width="24px" height="24px"/>
            </div>

            <button type="button" class="js-forum-question-action btn btn-primary btn-lg" data-title="{{ trans('update.new_question') }}" data-action="{{ $course->getForumPageUrl() }}/create">{{ trans('update.ask_question') }}</button>
        </div>
    </div>
</div>

{{-- Card --}}
@if($forums and count($forums))
    <div class="row mb-40">
        @foreach($forums as $forumRow)
            <div class="col-12 col-lg-6 mt-24">
                @include('design_1.web.courses.learning_page.includes.contents.forum.includes.forum_card', ['forum' => $forumRow])
            </div>
        @endforeach
    </div>
@else
    <div class="d-flex-center flex-column text-center bg-white mt-24 py-64 px-16 rounded-24">
        <div class="">
            <img src="/assets/design_1/img/courses/learning_page/empty_state.svg" alt="" class="img-fluid" width="285px" height="212px">
        </div>
        <h3 class="font-16 text-dark mt-12">{{ trans('update.learning_page_course_forum_empty_state_title') }}</h3>
        <div class="mt-8 font-12 text-gray-500">{{ trans('update.learning_page_course_forum_empty_state_msg') }}</div>
    </div>
@endif
