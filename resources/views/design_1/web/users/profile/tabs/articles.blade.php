@if(!empty($posts) and $posts->isNotEmpty())
    <div id="profilePostsRow" class="row">
        @include('design_1.web.blog.components.cards.grids.index',['posts' => $posts, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-16"])
    </div>

    @if(!empty($hasMorePosts))
        <div class="d-flex-center mt-16">
            <div class="js-profile-tab-load-more-btn btn border-dashed border-gray-300 rounded-12 bg-white bg-hover-gray-100 cursor-pointer" data-path="/users/{{ $user->getUsername() }}/get-posts" data-el="profilePostsRow">
                <x-iconsax-lin-rotate-left class="icons text-gray-500" width="16px" height="16px"/>
                <span class="ml-4 text-gray-500">{{ trans('update.load_more') }}</span>
            </div>
        </div>
    @endif
@else
    @include('design_1.panel.includes.no-result',[
        'file_name' => 'profile_blog.svg',
        'title' => trans('update.user_profile_not_have_posts'),
        'hint' => trans('update.user_profile_not_have_posts_hint'),
        'extraClass' => 'mt-0',
    ])
@endif

