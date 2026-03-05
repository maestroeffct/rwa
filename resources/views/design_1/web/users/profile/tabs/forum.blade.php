@if(!empty($forumTopics) and !$forumTopics->isEmpty())
    <div id="profileForumTopicsRow" class="">
        @include('design_1.web.forums.components.cards.topic.index',['forumTopics' => $forumTopics, 'cardClassName' => "mt-24 profile-forum-topic"])
    </div>

    @if(!empty($hasMoreForumTopics))
        <div class="d-flex-center mt-16">
            <div class="js-profile-tab-load-more-btn btn border-dashed border-gray-300 rounded-12 bg-white bg-hover-gray-100 cursor-pointer" data-path="/users/{{ $user->getUsername() }}/get-topics" data-el="profileForumTopicsRow">
                <x-iconsax-lin-rotate-left class="icons text-gray-500" width="16px" height="16px"/>
                <span class="ml-4 text-gray-500">{{ trans('update.load_more') }}</span>
            </div>
        </div>
    @endif
@else
    @include('design_1.panel.includes.no-result',[
        'file_name' => 'profile_forum.svg',
        'title' => trans('update.user_profile_not_have_topics'),
        'hint' => trans('update.user_profile_not_have_topics_hint'),
        'extraClass' => 'mt-0',
    ])
@endif
