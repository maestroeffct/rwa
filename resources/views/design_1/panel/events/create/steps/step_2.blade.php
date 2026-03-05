@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">
    <h3 class="font-14 font-weight-bold">{{ trans('update.taxonomy') }}</h3>

    <div class="form-group  mt-24">
        <label class="form-group-label is-required">{{ trans('public.category') }}</label>

        <select name="category_id" id="categories" class="select2 @error('category_id')  is-invalid @enderror">
            <option {{ (!empty($event) and !empty($event->category_id)) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>
            @foreach($categories as $category)
                @if(!empty($category->subCategories) and $category->subCategories->count() > 0)
                    <optgroup label="{{  $category->title }}">
                        @foreach($category->subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ ((!empty($event) and $event->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $category->id }}" {{ ((!empty($event) and $event->category_id == $category->id) or old('category_id') == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                @endif
            @endforeach
        </select>

        @error('category_id')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mt-24 {{ (!empty($eventCategoryFilters) and count($eventCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
        <h3 class="font-14 font-weight-bold">{{ trans('public.category_filters') }}</h3>

        <div id="categoriesFiltersCard" class="row">
            @if(!empty($eventCategoryFilters) and count($eventCategoryFilters))
                @foreach($eventCategoryFilters as $filter)
                    <div class="col-12 col-md-3 mt-16">
                        <div class="create-course-filter-card bg-white p-16 rounded-12 border-gray-200">
                            <h5 class="font-14 font-weight-bold mb-16">{{ $filter->title }}</h5>

                            @php
                                $eventFilterOptions = $event->filterOptions->pluck('filter_option_id')->toArray();

                                if (!empty(old('filters'))) {
                                    $eventFilterOptions = array_merge($eventFilterOptions, old('filters'));
                                }
                            @endphp

                            @foreach($filter->options as $option)
                                <div class="custom-control custom-checkbox {{ $loop->first ? '' : 'mt-12' }}">
                                    <input type="checkbox" name="filters[]" value="{{ $option->id }}" id="filterOptions{{ $option->id }}" class="custom-control-input" {{ ((!empty($eventFilterOptions) && in_array($option->id, $eventFilterOptions)) ? 'checked' : '') }}>
                                    <label class="custom-control__label cursor-pointer" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Event Options --}}
    <h3 class="font-14 font-weight-bold my-24">{{ trans('update.event_options') }}</h3>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.capacity') }}</label>
        <input type="number" name="capacity" value="{{ (!empty($event) and !empty($event->capacity)) ? $event->capacity : old('capacity') }}" class="form-control @error('capacity')  is-invalid @enderror" placeholder="{{ trans('forms.capacity_placeholder') }}"/>
        @error('capacity')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <p class="text-gray-500 mt-8 font-12">{{  trans('update.leave_blank_for_unlimited_capacity')  }}</p>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.ticket_purchase_limit') }} ({{ trans('update.per_user') }})</label>
        <input type="number" name="purchase_limit_count" value="{{ !empty($event) ? $event->purchase_limit_count : old('purchase_limit_count') }}" class="form-control @error('purchase_limit_count')  is-invalid @enderror"/>
        @error('purchase_limit_count')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <div class="text-gray-500 mt-8 font-12">{{ trans('update.ticket_purchase_limit_input_hint') }}</div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.duration') }}</label>
        <input type="number" name="duration" value="{{ !empty($event) ? $event->duration : old('duration') }}" class="form-control @error('duration')  is-invalid @enderror"/>
        @error('duration')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
        <div class="text-gray-500 mt-8 font-12">{{ trans('update.event_duration_input_hint') }}</div>
    </div>

    <div class="row">

        <div class="col-12 col-md-6">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.start_date') }}</label>
                <span class="has-translation bg-transparent text-gray-500"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <input type="text" name="start_date" value="{{ (!empty($event) and $event->start_date) ? dateTimeFormat($event->start_date, 'Y-m-d H:i', false, false, $event->timezone) : old('start_date') }}" class="form-control @error('start_date')  is-invalid @enderror datetimepicker js-default-init-date-picker" aria-describedby="dateInputGroupPrepend" autocomplete="off"/>

                @error('start_date')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="form-group">
                <label class="form-group-label">{{ trans('webinars.end_date') }}</label>
                <span class="has-translation bg-transparent text-gray-500"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <input type="text" name="end_date" value="{{ (!empty($event) and $event->end_date) ? dateTimeFormat($event->end_date, 'Y-m-d H:i', false, false, $event->timezone) : old('end_date') }}" class="form-control @error('end_date')  is-invalid @enderror datetimepicker js-default-init-date-picker" aria-describedby="dateInputGroupPrepend" autocomplete="off"/>

                @error('end_date')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.sales_end_date') }}</label>
        <span class="has-translation bg-transparent text-gray-500"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
        <input type="text" name="sales_end_date" value="{{ (!empty($event) and $event->sales_end_date) ? dateTimeFormat($event->sales_end_date, 'Y-m-d H:i', false, false, $event->timezone) : old('sales_end_date') }}" class="form-control @error('sales_end_date')  is-invalid @enderror datetimepicker js-default-init-date-picker" aria-describedby="dateInputGroupPrepend" autocomplete="off"/>

        @error('sales_end_date')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    @php
        $selectedTimezone = getGeneralSettings('default_time_zone');

        if (!empty($event->timezone)) {
            $selectedTimezone = $event->timezone;
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


    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="enableCountdownSwitch" type="checkbox" name="enable_countdown" class="custom-control-input" {{ (!empty($event) and $event->enable_countdown) ? 'checked' : '' }}>
            <label class="custom-control-label cursor-pointer" for="enableCountdownSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="enableCountdownSwitch">{{ trans('update.enable_countdown_on_the_event_page') }}</label>
        </div>
    </div>

    <div class="js-countdown-time-reference-field form-group {{ (!empty($event) and $event->enable_countdown) ? '' : 'd-none' }}">
        <label class="form-group-label">{{ trans('update.countdown_time_reference') }}</label>
        <select name="countdown_time_reference" class="form-control">
            <option value="start_date" {{ (!empty($event) and $event->countdown_time_reference == "start_date") ? "selected" : '' }}>{{ trans('public.start_date') }}</option>
            <option value="sales_end_date" {{ (!empty($event) and $event->countdown_time_reference == "sales_end_date") ? "selected" : '' }}>{{ trans('update.sales_end_date') }}</option>
        </select>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="supportSwitch" type="checkbox" name="support" class="custom-control-input" {{ (!empty($event) and $event->support) ? 'checked' : '' }}>
            <label class="custom-control-label cursor-pointer" for="supportSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="supportSwitch">{{ trans('panel.support') }}</label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="includeCertificateSwitch" type="checkbox" name="certificate" class="custom-control-input" {{ (!empty($event) and $event->certificate) ? 'checked' : '' }}>
            <label class="custom-control-label cursor-pointer" for="includeCertificateSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="includeCertificateSwitch">{{ trans('update.include_certificate') }}</label>
        </div>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="privateSwitch" type="checkbox" name="private" class="custom-control-input" {{ (!empty($event) and $event->private) ? 'checked' : '' }}>
            <label class="custom-control-label cursor-pointer" for="privateSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="privateSwitch">{{ trans('webinars.private') }}</label>
        </div>
    </div>

    @php
        $eventTags = !empty($event) ? $event->tags->pluck('title')->toArray() : [];
    @endphp

    <div class="form-group tagsinput-bg-white mt-15">
        <label class="form-group-label d-block">{{ trans('public.tags') }}</label>
        <input type="text" name="tags" data-max-tag="5" value="{{ !empty($eventTags) ? implode(',', $eventTags) : '' }}" class="form-control inputtags" placeholder="{{ trans('public.type_tag_name_and_press_enter') }} ({{ trans('forms.max') }} : 5)"/>
    </div>

</div>


@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
@endpush
