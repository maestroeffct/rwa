@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("forum") }}">
@endpush


@section('content')
    <div class="container mt-40 mb-64">
        {{-- Hero & Cover --}}
        @include('design_1.web.forums.search.includes.hero')

        <div class="position-relative pl-8 mt-28">
            <div class="alert alert-success d-flex align-items-center p-12 rounded-12">
                <div class="alert-left-20 d-flex-center size-48 bg-success rounded-12">
                <x-iconsax-bul-search-normal style="fill: white;" class="icons" width="24px" height="24px"/>
                </div>

                <div class="ml-8">
                    <h6 class="font-14 text-success">{{ trans('update.n_search_results', ['count' => $totalTopicsCount]) }}</h6>
                    <p class="font-12 text-success opacity-75">{{ trans('update.explore_search_results_from_the_following_list') }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Lists --}}
            <div class="col-12 col-md-8 col-lg-9">

                @if(!empty($forumTopics) and !$forumTopics->isEmpty())
                    <div id="forumTopicsListsRow" class="">
                        @include('design_1.web.forums.components.cards.topic.index',['forumTopics' => $forumTopics, 'cardClassName' => "mt-24"])
                    </div>

                    @if(!empty($hasMoreForumTopics))
                        <div class="d-flex-center mt-16">
                            <div class="js-topics-load-more-btn btn border-dashed border-gray-300 rounded-12  bg-hover-gray-100 px-24 py-16 cursor-pointer" data-path="/forums/{{ $forum->slug }}/topics" data-el="forumTopicsListsRow">
                                <x-iconsax-lin-rotate-left class="icons text-gray-500" width="16px" height="16px"/>
                                <span class="ml-4 text-gray-500">{{ trans('update.load_more') }}</span>
                            </div>
                        </div>
                    @endif
                @else
                    @include('design_1.panel.includes.no-result',[
                        'file_name' => 'webinar.png',
                        'title' => trans('update.instructor_not_have_topics'),
                        'hint' => '',
                    ])
                @endif

            </div>

            {{-- Right --}}
            <div class="col-12 col-md-4 col-lg-3 mt-20">

                @if(!empty($popularTopics) and count($popularTopics))
                    @include('design_1.web.forums.topics.lists.includes.popular_posts')
                @endif

                @if(!empty($topUsers) and count($topUsers))
                    @include('design_1.web.forums.topics.lists.includes.top_users')
                @endif


            </div>
        </div>
    </div>


    {{-- Search Drawer --}}
    @include('design_1.web.forums.search.includes.search_drawer')

@endsection


@push('scripts_bottom')

    <script src="{{ getDesign1ScriptPath("forum_topics_lists") }}"></script>
@endpush

