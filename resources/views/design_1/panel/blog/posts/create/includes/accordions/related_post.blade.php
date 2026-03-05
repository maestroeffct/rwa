<li data-id="{{ !empty($relatedPost) ? $relatedPost->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="relatedPost_{{ !empty($relatedPost) ? $relatedPost->id :'record' }}">
        <div class="d-flex align-items-center justify-content-between cursor-pointer" href="#collapseRelatedCourse{{ !empty($relatedPost) ? $relatedPost->id :'record' }}" data-parent="#relatedPostsAccordion" role="button" data-toggle="collapse">
            <div class="d-flex align-items-center">
                <div class="">
                    <x-iconsax-lin-document-text class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <h6 class="font-14">{{ (!empty($relatedPost) and !empty($relatedPost->post)) ? $relatedPost->post->title .' - '. $relatedPost->post->author->full_name : trans('update.add_new_related_post') }}</h6>
                </div>
            </div>
        </div>


        <div class="d-flex align-items-center">
            @if(!empty($relatedPost))
                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/blog/{{ $post->id }}/related-posts/{{ $relatedPost->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseRelatedCourse{{ !empty($relatedPost) ? $relatedPost->id :'record' }}" data-parent="#relatedPostsAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
            </span>
        </div>

    </div>

    <div id="collapseRelatedCourse{{ !empty($relatedPost) ? $relatedPost->id :'record' }}" class="accordion__collapse {{ empty($relatedPost) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form js-relatedPost-form" data-action="/panel/blog/{{ $post->id }}/related-posts/{{ !empty($relatedPost) ? $relatedPost->id . '/update' : 'store' }}">

            <div class="form-group mt-20">
                <label class="form-group-label">{{ trans('update.post') }}</label>

                <select name="ajax[{{ !empty($relatedPost) ? $relatedPost->id : 'new' }}][post_id]" class="js-ajax-post_id form-control select2" data-allow-clear="false"">
                    <option value="">{{ trans('update.select_related_post') }}</option>

                    @if(!empty($otherPosts))
                        @foreach($otherPosts as $otherPost)
                            <option value="{{ $otherPost->id }}">{{ $otherPost->title .' - '. $otherPost->author->full_name }}</option>
                        @endforeach
                    @endif
                </select>
                <div class="invalid-feedback"></div>
            </div>


            <div class="mt-28 d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-post-content btn btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($relatedPost))
                    <a href="/panel/blog/{{ $post->id }}/related-posts/{{ $relatedPost->id }}/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
