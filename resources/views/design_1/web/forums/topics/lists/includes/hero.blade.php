
<div class="card-with-mask position-relative">
    <div class="mask-8-white"></div>

    <div class="position-relative bg-white rounded-24 p-12 z-index-3">
        <div class="forum-topic-cover position-relative rounded-16">
            <img src="{{ $forum->cover }}" alt="{{ trans('update.topics') }}" class="img-cover rounded-16">

            <div class="forum-topic-cover__icon d-inline-flex-center size-64 rounded-circle bg-white border-gray-200">
                <img src="{{ $forum->icon }}" alt="{{ trans('update.icon') }}" class="img-fluid" width="40px">
            </div>
        </div>

        <div class="pl-12 pr-4 pb-12">
            <div class="d-flex align-items-center mt-40">
                <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                <x-iconsax-lin-arrow-right-1 class="icons text-gray-400" width="16px" height="16px"/>
                <a href="/forums" class="text-gray-500">{{ trans('update.forum') }}</a>
            </div>

            <h1 class="font-16 mt-8 ">{{ $forum->title }}</h1>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-8">
                <div class="text-gray-500">{{ trans('update.discussions_about_information_technology_and_related_topics_and_subjects') }}</div>

                <div class="d-flex align-items-center gap-24 mt-16 mt-lg-0">
                    <div class="d-flex align-items-center gap-4 font-12 text-gray-500">
                        <x-iconsax-lin-message-text class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-weight-bold">{{ $topicsCount }}</span>
                        <span class="">{{ trans('update.topics') }}</span>
                    </div>

                    <div class="d-flex align-items-center gap-4 font-12 text-gray-500">
                        <x-iconsax-lin-messages class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-weight-bold">{{ $postsCount }}</span>
                        <span class="">{{ trans('update.posts') }}</span>
                    </div>

                    <div class="d-flex align-items-center gap-4 font-12 text-gray-500">
                        <x-iconsax-lin-profile-2user class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-weight-bold">{{ $membersCount }}</span>
                        <span class="">{{ trans('update.members') }}</span>
                    </div>
                </div>
            </div>


            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-12 pt-16 border-top-gray-100">
                <div class="d-flex align-items-center gap-24">
                    @php
                        $sorts = ['newest', 'popular_topics', 'not_answered'];
                    @endphp

                    @foreach($sorts as $sort)
                        <a href="{{ request()->url() }}?sort={{ $sort }}" class="font-14 {{ (($loop->first and empty(request()->get('sort'))) or request()->get('sort') == $sort) ? 'text-primary' : 'text-gray-500' }}">{{ trans("update.{$sort}") }}</a>
                    @endforeach
                </div>

                <div class="d-flex align-items-center gap-24 mt-16 mt-lg-0">
                    <div class="js-show-search-drawer cursor-pointer">
                        <x-iconsax-lin-search-normal class="icons text-gray-500" width="20px" height="20px"/>
                    </div>

                    <a href="/forums/create-topic{{ $forum->checkUserCanCreateTopic() ? '?forum_id='.$forum->id : '' }}" class="btn btn-primary btn-lg">{{ trans('update.new_topic') }}</a>
                </div>
            </div>

        </div>

    </div>
</div>
