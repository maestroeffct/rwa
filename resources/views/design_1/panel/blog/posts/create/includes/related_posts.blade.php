{{-- Related Posts --}}
@if(!empty($post))

    <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <x-iconsax-bul-document-text class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('update.related_posts') }}</h5>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.assign_related_posts_to_the_this_article') }}</p>
            </div>
        </div>
    </div>


    <div class="">
        <div class="mt-20">
            @include('design_1.panel.blog.posts.create.includes.accordions.related_post')
        </div>

        <div class="mt-36">
            @if(!empty($post->relatedPosts) and count($post->relatedPosts))
                <ul class="draggable-content-lists related_courses-draggable-lists" data-path="" data-drag-class="related_courses-draggable-lists">
                    @foreach($post->relatedPosts as $relatedPostInfo)
                        @include('design_1.panel.blog.posts.create.includes.accordions.related_post',['relatedPost' => $relatedPostInfo])
                    @endforeach
                </ul>
            @endif

        </div>
    </div>
@else
    <div class="d-flex-center flex-column text-center mt-20 rounded-16 border-dashed border-gray-200 p-48">
        <div class="d-flex-center size-64 rounded-12 bg-warning-30">
            <x-iconsax-bul-document-text class="icons text-warning" width="24px" height="24px"/>
        </div>

        <h4 class="font-16 mt-12">{{ trans('update.related_posts') }}</h4>
        <p class="mt-4 text-12 text-gray-500">{{ trans('update.you_can_assign_related_blog_posts_after_saving_the_post') }}</p>
    </div>
@endif
