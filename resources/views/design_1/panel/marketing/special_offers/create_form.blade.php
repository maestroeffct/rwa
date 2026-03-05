<form action="/panel/marketing/special_offers/store" method="post">
    {{ csrf_field() }}

    <div class="bg-white p-16 rounded-24">

        <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100 mb-28">
            <div class="">
                <h3 class="font-16">{{ !empty($discount) ? trans('update.edit_discount') : trans('update.new_discount') }}</h3>
                <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_and_manage_course_discounts_create_hint') }}</p>
            </div>
        </div>


        <div class="form-group">
            <label class="form-group-label">{{ trans('public.title') }}</label>
            <input type="text" name="title" class="js-ajax-title form-control @error('title') is-invalid @enderror" value="{{ !empty($discount) ? $discount->title : old('title') }}"/>

            <div class="invalid-feedback">@error('title') {{ $message }} @enderror</div>
        </div>

        <div class="form-group ">
            <label class="form-group-label">{{ trans('admin/main.courses') }}</label>
            <select name="webinar_id" class="js-ajax-webinar_id form-control select2" data-placeholder="{{ trans('update.select_a_course') }}">
                <option value="">{{ trans('update.select_a_course') }}</option>

                @if(!empty($webinars))
                    @foreach($webinars as $webinar)
                        <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
                    @endforeach
                @endif
            </select>

            <div class="invalid-feedback">@error('webinar_id') {{ $message }} @enderror</div>
        </div>

        <div class="form-group">
            <label class="form-group-label">{{ trans('admin/main.discount_percentage') }}</label>
            <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14">%</span>
            <input type="number" name="percent"
                   class="js-ajax-percent form-control  @error('percent') is-invalid @enderror"
                   value="{{ !empty($discount) ? $discount->percent : old('percent') }}"
                   min="0"/>

            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group ">
            <label class="form-group-label">{{ trans('update.discount_date_range') }}</label>
            <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14"><x-iconsax-lin-calendar-2 class="icons text-gray-500" width="24px" height="24px"/></span>
            <input type="text" name="date_range" class="js-ajax-date_range form-control date-range-picker js-default-init-date-picker"
                   aria-describedby="dateRangeLabel" autocomplete="off" data-format="YYYY/MM/DD HH:mm"
                   data-timepicker="true"
                   value=""/>

            <div class="invalid-feedback d-block"></div>
        </div>

        <div class="d-flex align-items-center justify-content-between border-top-gray-100 pt-16 mt-16">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                    <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h5 class="font-14">{{ trans('update.notice') }}</h5>
                    <p class="mt-2 font-12 text-gray-500">{{ trans('update.the_discount_will_be_applied_on_the_start_date_automatically') }}</p>
                </div>
            </div>

            <button type="button" class="js-submit-special-offer-btn btn btn-lg btn-primary">{{ trans('public.create') }}</button>
        </div>

    </div>
</form>
