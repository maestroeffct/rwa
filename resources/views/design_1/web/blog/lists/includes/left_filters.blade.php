<div class="position-relative blog-lists-filters">
    <div class="blog-lists-filters__mask"></div>

    <div class="position-relative card-before-line card-before-line__4-12 bg-white py-16 rounded-24 z-index-2">
        <div class="font-14 font-weight-bold text-dark px-16">
            {{ trans('categories.categories') }}
        </div>

        @foreach($blogCategories as $blogCategory)
            <a href="{{ $blogCategory->getUrl() }}" class="d-flex align-items-center justify-content-between mt-16 px-16 cursor-pointer {{ (!empty($selectedCategory) and $selectedCategory->id == $blogCategory->id) ? 'text-primary' : 'text-dark' }}">
                <span class="">{{ $blogCategory->title }}</span>

                <span class="font-12 text-gray-400">{{ $blogCategory->blog_count }}</span>
            </a>
        @endforeach
    </div>
</div>


<div class="position-relative blog-lists-filters mt-28">
    <div class="blog-lists-filters__mask"></div>

    <div id="leftFiltersAccordion" class="position-relative bg-white py-16 rounded-24 z-index-2">


        {{-- More Options --}}
        <div class="accordion card-before-line card-before-line__4-12 pb-16 px-16 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersOptions" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    {{ trans('update.options') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersOptions" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            @php
                $moreOptions = [
                    'supported_courses',
                    'quiz_included',
                    'certificate_included',
                    'assignment_included',
                    'course_forum_included',
                    'point_courses',
                ];
            @endphp

            <div id="leftFiltersOptions" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                <div class="form-group  mb-0 mt-28">
                    <label class="form-group-label font-12">{{ trans('update.sort_posts_by') }}</label>
                    <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity" data-placholder="{{ trans('update.sort_posts_by') }}">
                        <option value="">{{ trans('update.all') }}</option>
                        <option value="newest">{{ trans('public.newest') }}</option>
                        <option value="oldest">{{ trans('update.oldest') }}</option>
                        <option value="most_views">{{ trans('update.most_views') }}</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Prices Filters --}}
        <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersStudyTime" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    {{ trans('public.study_time') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersStudyTime" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            <div id="leftFiltersStudyTime" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">

                <div class="d-flex align-items-center mt-16">
                    <div class="form-group mb-0">
                        <input type="tel" readonly value="" class="js-filters-min-study_time form-control input-xs bg-white text-center text-gray-500" placeholder="{{ trans('update.min_study_time_input_placeholder') }}">
                    </div>
                    <div class="mx-4"></div>
                    <div class="form-group mb-0">
                        <input type="tel" readonly value="" class="js-filters-max-study_time form-control input-xs bg-white text-center text-gray-500" placeholder="{{ trans('update.max_study_time_input_placeholder') }}">
                    </div>
                </div>

                <div
                    class="range wrunner-value-bottom no-bottom-value-note mt-8"
                    id="studyTimeRange"
                    data-minLimit="0"
                    data-maxLimit="{{ $filterMaxStudyTime }}"
                    data-step="1"
                >
                    <input type="hidden" name="min_study_time" class="js-range-input-view-data" value="">
                    <input type="hidden" name="max_study_time" class="js-range-input-view-data" value="">
                </div>

            </div>
        </div>

        {{-- Instructor --}}
        <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersAuthor" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    {{ trans('update.author') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersAuthor" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            <div id="leftFiltersAuthor" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                <div class="form-group mb-0  mt-24">
                    <label class="form-group-label">{{ trans('update.article_instructor') }}</label>
                    <select name="author_id" class="form-control searchable-select bg-white" data-allow-clear="true" data-placeholder="{{ trans('update.search_and_select_author') }}"
                            data-api-path="/users/search"
                            data-item-column-name="full_name"
                            data-option="just_post_authors"
                    >

                    </select>
                </div>
            </div>
        </div>

    </div>
</div>

@if(!empty($popularPosts) and $popularPosts->isNotEmpty())
    <div class="position-relative blog-lists-filters mt-28">
        <div class="blog-lists-filters__mask"></div>

        <div class="position-relative card-before-line card-before-line__4-12 bg-white py-16 rounded-24 z-index-2">
            <div class="font-14 font-weight-bold text-dark px-16">
                {{ trans('update.popular_posts') }}
            </div>

            @foreach($popularPosts as $popularPost)
                <div class="d-flex align-items-center mt-16 px-16">
                    <div class="blog-lists-filters__post-img rounded-8">
                        <img src="{{ $popularPost->image }}" class="img-cover rounded-8" alt="{{ $popularPost->title }}">
                    </div>
                    <div class="ml-8">
                        <a href="{{ $popularPost->getUrl() }}">
                            <h3 class="font-14 text-dark">{{ truncate($popularPost->title, 27) }}</h3>
                        </a>
                        <span class="mt-8 font-12 text-gray-500">{{ dateTimeFormat($popularPost->created_at, 'j M Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(!empty($recentPosts) and $recentPosts->isNotEmpty())
    <div class="position-relative blog-lists-filters mt-28">
        <div class="blog-lists-filters__mask"></div>

        <div class="position-relative card-before-line card-before-line__4-12 bg-white py-16 rounded-24 z-index-2">
            <div class="font-14 font-weight-bold text-dark px-16">
                {{ trans('site.recent_posts') }}
            </div>

            @foreach($recentPosts as $recentPost)
                <div class="d-flex align-items-center mt-16 px-16">
                    <div class="blog-lists-filters__post-img rounded-8">
                        <img src="{{ $recentPost->image }}" class="img-cover rounded-8" alt="{{ $recentPost->title }}">
                    </div>
                    <div class="ml-8">
                        <a href="{{ $recentPost->getUrl() }}">
                            <h3 class="font-14 text-dark">{{ truncate($recentPost->title, 27) }}</h3>
                        </a>
                        <span class="mt-8 font-12 text-gray-500">{{ dateTimeFormat($recentPost->created_at, 'j M Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
