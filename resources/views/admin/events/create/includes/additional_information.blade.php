<h2 class="section-title after-line mt-24">{{ trans('public.additional_information') }}</h2>

<div class="row">
    <div class="col-12 col-md-6">


        <div class="form-group mt-15">
            <label class="input-label">{{ trans('update.sales_count_number') }}</label>
            <input type="number" name="sales_count_number" value="{{ !empty($event) ? $event->sales_count_number : old('sales_count_number') }}" class="form-control @error('sales_count_number')  is-invalid @enderror"/>
            @error('sales_count_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <div class="text-gray-500 text-small mt-1">{{ trans('update.product_sales_count_number_hint') }}</div>
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.capacity') }}</label>
            <input type="number" name="capacity" value="{{ !empty($event) ? $event->capacity : old('capacity') }}" class="form-control @error('capacity')  is-invalid @enderror"/>
            @error('capacity')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <div class="text-gray-500 text-small mt-1">{{ trans('update.leave_blank_for_unlimited_capacity') }}</div>
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('update.ticket_purchase_limit') }} ({{ trans('update.per_user') }})</label>
            <input type="number" name="purchase_limit_count" value="{{ !empty($event) ? $event->purchase_limit_count : old('purchase_limit_count') }}" class="form-control @error('purchase_limit_count')  is-invalid @enderror"/>
            @error('purchase_limit_count')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <div class="text-gray-500 text-small mt-1">{{ trans('update.ticket_purchase_limit_input_hint') }}</div>
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.duration') }}</label>
            <input type="number" name="duration" value="{{ !empty($event) ? $event->duration : old('duration') }}" class="form-control @error('duration')  is-invalid @enderror"/>
            @error('duration')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <div class="text-gray-500 text-small mt-1">{{ trans('update.event_duration_input_hint') }}</div>
        </div>


        <div class="row mt-15">

            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label class="input-label">{{ trans('public.start_date') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="dateInputGroupPrepend">
                            <x-iconsax-bul-calendar-2 class="text-gray-500" width="20px" height="20px"/>
                        </span>
                        </div>
                        <input type="text" name="start_date" value="{{ (!empty($event) and $event->start_date) ? dateTimeFormat($event->start_date, 'Y-m-d H:i', false, false, $event->timezone) : old('start_date') }}" class="form-control @error('start_date')  is-invalid @enderror datetimepicker" aria-describedby="dateInputGroupPrepend" autocomplete="off"/>
                        @error('start_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="dateInputGroupPrepend">
                            <x-iconsax-bul-calendar-2 class="text-gray-500" width="20px" height="20px"/>
                        </span>
                        </div>
                        <input type="text" name="end_date" value="{{ (!empty($event) and $event->end_date) ? dateTimeFormat($event->end_date, 'Y-m-d H:i', false, false, $event->timezone) : old('end_date') }}" class="form-control @error('end_date')  is-invalid @enderror datetimepicker" aria-describedby="dateInputGroupPrepend" autocomplete="off"/>
                        @error('end_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        <div class="form-group">
            <label class="input-label">{{ trans('update.sales_end_date') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="dateInputGroupPrepend">
                        <x-iconsax-bul-calendar-2 class="text-gray-500" width="20px" height="20px"/>
                    </span>
                </div>
                <input type="text" name="sales_end_date" value="{{ (!empty($event) and $event->sales_end_date) ? dateTimeFormat($event->sales_end_date, 'Y-m-d H:i', false, false, $event->timezone) : old('sales_end_date') }}" class="form-control @error('sales_end_date')  is-invalid @enderror datetimepicker" aria-describedby="dateInputGroupPrepend" autocomplete="off"/>
                @error('sales_end_date')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group mt-30 d-flex align-items-center justify-content-between">
            <label class="cursor-pointer" for="enableCountdownSwitch">{{ trans('update.enable_countdown_on_the_event_page') }}</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="enable_countdown" class="custom-control-input" id="enableCountdownSwitch" {{ (!empty($event) and $event->enable_countdown) ? 'checked' : '' }}>
                <label class="custom-control-label" for="enableCountdownSwitch"></label>
            </div>
        </div>

        <div class="js-countdown-time-reference-field form-group {{ (!empty($event) and $event->enable_countdown) ? '' : 'd-none' }}">
            <label class="input-label">{{ trans('update.countdown_time_reference') }}</label>
            <select name="countdown_time_reference" class="form-control">
                <option value="start_date" {{ (!empty($event) and $event->countdown_time_reference == "start_date") ? "selected" : '' }}>{{ trans('public.start_date') }}</option>
                <option value="sales_end_date" {{ (!empty($event) and $event->countdown_time_reference == "sales_end_date") ? "selected" : '' }}>{{ trans('update.sales_end_date') }}</option>
            </select>
        </div>


        @php
            $selectedTimezone = getGeneralSettings('default_time_zone');

            if (!empty($event) and !empty($event->timezone)) {
                $selectedTimezone = $event->timezone;
            }
        @endphp

        <div class="form-group">
            <label class="input-label">{{ trans('update.timezone') }}</label>
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

        <div class="form-group mt-30 d-flex align-items-center justify-content-between">
            <label class="cursor-pointer" for="supportSwitch">{{ trans('panel.support') }}</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="support" class="custom-control-input" id="supportSwitch" {{ (!empty($event) and $event->support) ? 'checked' : '' }}>
                <label class="custom-control-label" for="supportSwitch"></label>
            </div>
        </div>

        <div class="form-group mt-30 d-flex align-items-center justify-content-between">
            <label class="cursor-pointer" for="includeCertificateSwitch">{{ trans('update.include_certificate') }}</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="certificate" class="custom-control-input" id="includeCertificateSwitch" {{ (!empty($event) and $event->certificate) ? 'checked' : '' }}>
                <label class="custom-control-label" for="includeCertificateSwitch"></label>
            </div>
        </div>

        <div class="form-group mt-30 d-flex align-items-center justify-content-between">
            <label class="cursor-pointer" for="privateSwitch">{{ trans('webinars.private') }}</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" name="private" class="custom-control-input" id="privateSwitch" {{ (!empty($event) and $event->private) ? 'checked' : ''  }}>
                <label class="custom-control-label" for="privateSwitch"></label>
            </div>
        </div>

        {{-- Product Badges --}}
        @if(!empty($event))
            @include('admin.product_badges.content_include', ['itemTarget' => $event])
        @endif

        @php
            $eventTags = !empty($event) ? $event->tags->pluck('title')->toArray() : [];
        @endphp

        <div class="form-group mt-15">
            <label class="input-label d-block">{{ trans('public.tags') }}</label>
            <input type="text" name="tags" data-max-tag="5" value="{{ !empty($eventTags) ? implode(',', $eventTags) : '' }}" class="form-control inputtags" placeholder="{{ trans('public.type_tag_name_and_press_enter') }} ({{ trans('admin/main.max') }} : 5)"/>
        </div>

    </div>
</div>
