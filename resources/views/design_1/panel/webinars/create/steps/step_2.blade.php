@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">
    <h3 class="font-14 font-weight-bold">{{ trans('update.taxonomy') }}</h3>

    <div class="form-group  mt-24">
        <label class="form-group-label is-required">{{ trans('public.category') }}</label>

        <select name="category_id" id="categories" class="select2 @error('category_id')  is-invalid @enderror">
            <option {{ (!empty($webinar) and !empty($webinar->category_id)) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>
            @foreach($categories as $category)
                @if(!empty($category->subCategories) and $category->subCategories->count() > 0)
                    <optgroup label="{{  $category->title }}">
                        @foreach($category->subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ ((!empty($webinar) and $webinar->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $category->id }}" {{ ((!empty($webinar) and $webinar->category_id == $category->id) or old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                @endif
            @endforeach
        </select>

        @error('category_id')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mt-24 {{ (!empty($webinarCategoryFilters) and count($webinarCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
        <h3 class="font-14 font-weight-bold">{{ trans('public.category_filters') }}</h3>

        <div id="categoriesFiltersCard" class="row">
            @if(!empty($webinarCategoryFilters) and count($webinarCategoryFilters))
                @foreach($webinarCategoryFilters as $filter)
                    <div class="col-12 col-md-3 mt-16">
                        <div class="create-course-filter-card bg-white p-16 rounded-12 border-gray-200">
                            <h5 class="font-14 font-weight-bold mb-16">{{ $filter->title }}</h5>

                            @php
                                $webinarFilterOptions = $webinar->filterOptions->pluck('filter_option_id')->toArray();

                                if (!empty(old('filters'))) {
                                    $webinarFilterOptions = array_merge($webinarFilterOptions, old('filters'));
                                }
                            @endphp

                            @foreach($filter->options as $option)
                                <div class="custom-control custom-checkbox {{ $loop->first ? '' : 'mt-12' }}">
                                    <input type="checkbox" name="filters[]" value="{{ $option->id }}" id="filterOptions{{ $option->id }}" class="custom-control-input" {{ ((!empty($webinarFilterOptions) && in_array($option->id, $webinarFilterOptions)) ? 'checked' : '') }}>
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
        <label class="form-group-label">{{ trans('public.capacity') }}</label>
        <input type="number" name="capacity" value="{{ (!empty($webinar) and !empty($webinar->capacity)) ? $webinar->capacity : old('capacity') }}" class="form-control @error('capacity')  is-invalid @enderror" placeholder="{{ trans('forms.capacity_placeholder') }}"/>
        @error('capacity')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <p class="text-gray-500 mt-5 font-12">{{  trans('forms.empty_means_unlimited')  }}</p>
    </div>


    <div class="row">

        @if($webinar->isWebinar())
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                    <label class="form-group-label is-required">{{ trans('public.start_date') }}</label>
                    <input type="text" name="start_date" class="form-control datetimepicker js-default-init-date-picker @error('start_date')  is-invalid @enderror" value="{{ (!empty($webinar) and $webinar->start_date) ? dateTimeFormat($webinar->start_date, 'Y-m-d H:i', false, false, $webinar->timezone) : old('start_date') }}" autocomplete="off">

                    @error('start_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        @endif

        <div class="col-12 @if($webinar->isWebinar()) col-md-6 @endif">
            <div class="form-group">
                <span class="has-translation px-8 bg-gray-100 w-auto text-gray-500">{{ trans('public.minutes') }}</span>
                <label class="form-group-label is-required">{{ trans('public.duration') }}</label>
                <input type="text" name="duration" class="form-control @error('duration')  is-invalid @enderror" value="{{ (!empty($webinar) and !empty($webinar->duration)) ? $webinar->duration : old('duration') }}">

                @error('duration')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>

    @if($webinar->isWebinar() and getFeaturesSettings('timezone_in_create_webinar'))
        @php
            $selectedTimezone = getGeneralSettings('default_time_zone');

            if (!empty($webinar->timezone)) {
                $selectedTimezone = $webinar->timezone;
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
    @endif

    @if(!empty(getFeaturesSettings("course_forum_status")))
        <div class="form-group">
            <div class="d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="forumSwitch" type="checkbox" name="forum" class="custom-control-input" {{ (!empty($webinar) and $webinar->forum) ? 'checked' :  '' }}>
                    <label class="custom-control-label cursor-pointer" for="forumSwitch"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="forumSwitch">{{ trans('update.course_forum') }}</label>
                </div>
            </div>

            <p class="font-12 text-gray-500 mt-8">- {{ trans('update.panel_course_forum_hint') }}</p>
        </div>
    @endif

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="supportSwitch" type="checkbox" name="support" class="custom-control-input" {{ (!empty($webinar) and $webinar->support) ? 'checked' :  '' }}>
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
                    <input id="certificateSwitch" type="checkbox" name="certificate" class="custom-control-input" {{ (!empty($webinar) and $webinar->certificate) ? 'checked' :  '' }}>
                    <label class="custom-control-label cursor-pointer" for="certificateSwitch"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="certificateSwitch">{{ trans('update.include_certificate') }}</label>
                </div>
            </div>

            <p class="font-12 text-gray-500 mt-8">- {{ trans('update.certificate_completion_hint') }}</p>
        </div>
    @endif

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="downloadableSwitch" type="checkbox" name="downloadable" class="custom-control-input" {{ (!empty($webinar) and $webinar->downloadable) ? 'checked' :  '' }}>
            <label class="custom-control-label cursor-pointer" for="downloadableSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="downloadableSwitch">{{ trans('home.downloadable') }}</label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="partnerInstructorSwitch" type="checkbox" name="partner_instructor" class="custom-control-input" {{ (!empty($webinar) and $webinar->partner_instructor) ? 'checked' :  '' }}>
            <label class="custom-control-label cursor-pointer" for="partnerInstructorSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="partnerInstructorSwitch">{{ trans('public.partner_instructor') }}</label>
        </div>
    </div>


    <div id="partnerInstructorInput" class="form-group  {{ (!empty($webinar) and $webinar->partner_instructor) ? '' : 'd-none' }}">
        <label class="form-group-label d-block">{{ trans('public.select_a_partner_teacher') }}</label>

        <select name="partners[]" class="form-control searchable-select bg-white" multiple data-allow-clear="false" data-placeholder="{{ trans('public.search_instructor') }}"
                data-api-path="/users/search"
                data-item-column-name="full_name"
                data-option=""
                {{--data-option="just_teachers"--}}
        >
            @if(!empty($webinar->webinarPartnerTeacher))
                @foreach($webinar->webinarPartnerTeacher as $partnerTeacher)
                    <option selected value="{{ $partnerTeacher->teacher->id }}">{{ $partnerTeacher->teacher->full_name }}</option>
                @endforeach
            @endif
        </select>

        <div class="text-gray-500 font-12 mt-8">{{ trans('admin/main.invited_instructor_hint') }}</div>

        @error('partners')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group tagsinput-bg-white mt-15">
        <label class="form-group-label d-block">{{ trans('public.tags') }}</label>
        <input type="text" name="tags" data-max-tag="5" value="{{ !empty($webinar) ? implode(',',$webinarTags) : '' }}" class="form-control inputtags" placeholder="{{ trans('public.type_tag_name_and_press_enter') }} ({{ trans('forms.max') }} : 5)"/>
    </div>


</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
@endpush
