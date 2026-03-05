@if($post->relatedPosts->isNotEmpty())
    @php
        $suggestedPosts = [];

        foreach ($post->relatedPosts as $relatedPost) {
            $suggestedPosts[] = $relatedPost->post;
        }
    @endphp

    <div class="position-relative mt-52">
        <h2 class="font-24">{{ trans('update.suggested_post') }}</h2>
        <p class="mt-4 text-gray-500">{{ trans('update.interested_in_this_topic_we_suggest_reading_the_following_posts') }}</p>

        <div class="position-relative mt-24">
            <div class="swiper-container js-make-swiper suggested-posts-slider pb-8 h-100"
                 data-item="suggested-posts-slider"
                 data-autoplay="true"
                 data-loop="true"
                 data-breakpoints="1440:4.2,769:2.2,320:1.2"
            >
                <div class="swiper-wrapper py-0 mx-16 mx-md-32 h-100">
                    @include('design_1.web.blog.components.cards.grids.index',['posts' => $suggestedPosts, 'gridCardClassName' => "swiper-slide"])
                </div>

            </div>
        </div>
    </div>
@endif
