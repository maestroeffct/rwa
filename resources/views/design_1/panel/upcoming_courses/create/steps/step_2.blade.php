
@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">
    <h3 class="font-14 font-weight-bold">{{ trans('update.taxonomy') }}</h3>

    <div class="form-group  mt-24">
        <label class="form-group-label is-required">{{ trans('public.category') }}</label>

        <select name="category_id" id="categories" class="select2 @error('category_id')  is-invalid @enderror">
            <option {{ (!empty($upcomingCourse) and !empty($upcomingCourse->category_id)) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>
            @foreach($categories as $category)
                @if(!empty($category->subCategories) and $category->subCategories->count() > 0)
                    <optgroup label="{{  $category->title }}">
                        @foreach($category->subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ ((!empty($upcomingCourse) and $upcomingCourse->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $category->id }}" {{ ((!empty($upcomingCourse) and $upcomingCourse->category_id == $category->id) or old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                @endif
            @endforeach
        </select>

        @error('category_id')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mt-24 {{ (!empty($upcomingCourseCategoryFilters) and count($upcomingCourseCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
        <h3 class="font-14 font-weight-bold">{{ trans('public.category_filters') }}</h3>

        <div id="categoriesFiltersCard" class="row">
            @if(!empty($upcomingCourseCategoryFilters) and count($upcomingCourseCategoryFilters))
                @foreach($upcomingCourseCategoryFilters as $filter)
                    <div class="col-12 col-md-3 mt-16">
                        <div class="create-course-filter-card bg-white p-16 rounded-12 border-gray-200">
                            <h5 class="font-14 font-weight-bold mb-16">{{ $filter->title }}</h5>

                            @php
                                $upcomingCourseFilterOptions = $upcomingCourse->filterOptions->pluck('filter_option_id')->toArray();

                                if (!empty(old('filters'))) {
                                    $upcomingCourseFilterOptions = array_merge($upcomingCourseFilterOptions, old('filters'));
                                }
                            @endphp

                            @foreach($filter->options as $option)
                                <div class="custom-control custom-checkbox {{ $loop->first ? '' : 'mt-12' }}">
                                    <input type="checkbox" name="filters[]" value="{{ $option->id }}" id="filterOptions{{ $option->id }}" class="custom-control-input" {{ ((!empty($upcomingCourseFilterOptions) && in_array($option->id, $upcomingCourseFilterOptions)) ? 'checked' : '') }}>
                                    <label class="custom-control__label cursor-pointer" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    {{-- Course Options --}}

    <h3 class="font-14 font-weight-bold my-24">{{ trans('update.course_options') }}</h3>

    <div class="form-group">
        <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
        <label class="form-group-label is-required">{{ trans('update.estimated_publish_date') }}</label>
        <input type="text" name="publish_date" class="form-control datetimepicker js-default-init-date-picker @error('publish_date')  is-invalid @enderror" value="{{ (!empty($upcomingCourse) and $upcomingCourse->publish_date) ? dateTimeFormat($upcomingCourse->publish_date, 'Y-m-d H:i', false, false, $upcomingCourse->timezone) : old('publish_date') }}" autocomplete="off">

        @error('publish_date')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>


    <div class="form-group">
        <label class="form-group-label">{{ trans('public.capacity') }}</label>
        <input type="number" name="capacity" value="{{ (!empty($upcomingCourse) and !empty($upcomingCourse->capacity)) ? $upcomingCourse->capacity : old('capacity') }}" class="form-control @error('capacity')  is-invalid @enderror" placeholder="{{ trans('forms.capacity_placeholder') }}"/>
        @error('capacity')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <p class="text-gray mt-5 font-12">{{  trans('forms.empty_means_unlimited')  }}</p>
    </div>

    @php
        $selectedTimezone = getGeneralSettings('default_time_zone');

        if (!empty($upcomingCourse->timezone)) {
            $selectedTimezone = $upcomingCourse->timezone;
        } elseif (!empty($authUser) and !empty($authUser->timezone)) {
            $selectedTimezone = $authUser->timezone;
        }
    @endphp

    <div class="form-group ">
        <label class="form-group-label">{{ trans('update.timezone') }}</label>
        <select name="timezone" class="form-control select2" data-allow-clear="false">
            @foreach(getListOfTimezones() as $timezone)
                <option value="{{ $timezone }}" @if($selectedTimezone == $timezone) selected @endif>{{ $timezone }}</option>
            @endforeach
        </select>
        @error('timezone')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.price') }}</label>
        <span class="has-translation bg-gray-100 text-gray-500">{{ $currency }}</span>
        <input type="text" name="price" class="form-control @error('price')  is-invalid @enderror" value="{{ (!empty($upcomingCourse) and !empty($upcomingCourse->price)) ? convertPriceToUserCurrency($upcomingCourse->price) : old('price') }}" placeholder="{{ trans('public.0_for_free') }}" oninput="validatePrice(this)"/>
        <div class="invalid-feedback d-block">@error('price') {{ $message }} @enderror</div>
    </div>

    <div class="form-group">
        <span class="has-translation px-8 bg-gray-100 w-auto text-gray-500">{{ trans('public.minutes') }}</span>
        <label class="form-group-label">{{ trans('public.duration') }}</label>
        <input type="text" name="duration" class="form-control @error('duration')  is-invalid @enderror" value="{{ (!empty($upcomingCourse) and !empty($upcomingCourse->duration)) ? $upcomingCourse->duration : old('duration') }}">

        @error('duration')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.sections') }}</label>
        <input type="number" min="0" name="sections" value="{{ (!empty($upcomingCourse) and !empty($upcomingCourse->sections)) ? $upcomingCourse->sections : old('sections') }}" class="form-control @error('sections')  is-invalid @enderror"/>
        @error('sections')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <p class="text-gray mt-5 font-12">{{  trans('update.upcoming_sections_hint')  }}</p>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.parts') }}</label>
        <input type="number" min="0" name="parts" value="{{ (!empty($upcomingCourse) and !empty($upcomingCourse->parts)) ? $upcomingCourse->parts : old('parts') }}" class="form-control @error('parts')  is-invalid @enderror"/>
        @error('parts')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <p class="text-gray mt-5 font-12">{{  trans('update.upcoming_parts_hint')  }}</p>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.course_progress') }}</label>
        <input type="number" min="0" max="100" name="course_progress" value="{{ (!empty($upcomingCourse) and !empty($upcomingCourse->course_progress)) ? $upcomingCourse->course_progress : old('course_progress') }}" class="form-control @error('course_progress')  is-invalid @enderror" placeholder="{{ trans('update.progress_placeholder') }}"/>
        @error('course_progress')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <p class="text-gray mt-5 font-12">{{  trans('update.upcoming_progress_hint')  }}</p>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="supportSwitch" type="checkbox" name="support" class="custom-control-input" {{ (!empty($upcomingCourse) and $upcomingCourse->support) ? 'checked' :  '' }}>
            <label class="custom-control-label cursor-pointer" for="supportSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="supportSwitch">{{ trans('webinars.support') }}</label>
        </div>
    </div>

    @if(!empty(getCertificateMainSettings("status")))
        <div class="form-group">
            <div class="d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="certificateSwitch" type="checkbox" name="certificate" class="custom-control-input" {{ (!empty($upcomingCourse) and $upcomingCourse->certificate) ? 'checked' :  '' }}>
                    <label class="custom-control-label cursor-pointer" for="certificateSwitch"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="certificateSwitch">{{ trans('update.include_certificate') }}</label>
                </div>
            </div>

            <p class="font-12 text-gray-500 mt-8">- {{ trans('update.certificate_completion_hint') }}</p>
        </div>
    @endif

    <div class="form-group">
        <div class="d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="include_quizzesSwitch" type="checkbox" name="include_quizzes" class="custom-control-input" {{ (!empty($upcomingCourse) and $upcomingCourse->include_quizzes) ? 'checked' :  '' }}>
                <label class="custom-control-label cursor-pointer" for="include_quizzesSwitch"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="include_quizzesSwitch">{{ trans('update.include_quizzes') }}</label>
            </div>
        </div>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="downloadableSwitch" type="checkbox" name="downloadable" class="custom-control-input" {{ (!empty($upcomingCourse) and $upcomingCourse->downloadable) ? 'checked' :  '' }}>
            <label class="custom-control-label cursor-pointer" for="downloadableSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="downloadableSwitch">{{ trans('home.downloadable') }}</label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="forumSwitch" type="checkbox" name="forum" class="custom-control-input" {{ (!empty($upcomingCourse) and $upcomingCourse->forum) ? 'checked' :  '' }}>
            <label class="custom-control-label cursor-pointer" for="forumSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="forumSwitch">{{ trans('update.course_forum') }}</label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="assignmentsSwitch" type="checkbox" name="assignments" class="custom-control-input" {{ (!empty($upcomingCourse) and $upcomingCourse->assignments) ? 'checked' :  '' }}>
            <label class="custom-control-label cursor-pointer" for="assignmentsSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="assignmentsSwitch">{{ trans('update.assignments') }}</label>
        </div>
    </div>


    <div class="form-group tagsinput-bg-white mt-15">
        <label class="form-group-label d-block">{{ trans('public.tags') }}</label>
        <input type="text" name="tags" data-max-tag="5" value="{{ !empty($upcomingCourse) ? implode(',', $upcomingCourseTags) : '' }}" class="form-control inputtags" placeholder="{{ trans('public.type_tag_name_and_press_enter') }} ({{ trans('forms.max') }} : 5)"/>
    </div>

</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
@endpush
