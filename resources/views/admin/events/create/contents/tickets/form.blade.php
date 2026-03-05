<form id="eventTicketForm" action="{{ getAdminPanelUrl("/events/{$event->id}/tickets/") }}{{ !empty($ticket) ? ($ticket->id.'/update') : 'store' }}" method="post">

    <div class="row">
        <div class="col-6">
            @if(!empty(getGeneralSettings('content_translate')))
                <div class="form-group">
                    <label class="input-label">{{ trans('auth.language') }}</label>
                    <select name="locale" class="form-control {{ !empty($ticket) ? 'js-event-content-locale' : '' }}" data-title="{{ trans('update.edit_ticket') }}" >
                        @foreach($userLanguages as $lang => $language)
                            <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                        @endforeach
                    </select>
                    @error('locale')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            @else
                <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
            @endif
        </div>

        <div class="col-6">
            <div class="form-group">
                <label class="input-label">{{ trans('public.title') }}</label>
                <input type="text" name="title" value="{{ (!empty($ticket) and !empty($ticket->translate($locale))) ? $ticket->translate($locale)->title : old('title') }}" class="js-ajax-title form-control " placeholder=""/>
                <div class="invalid-feedback d-block"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">

            <x-landingBuilder-icons-select
                label="{{ trans('update.icon') }}"
                name="icon"
                value="{{ !empty($ticket) ? $ticket->icon : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-make-icons-select2"
                dropdownParent="#eventTicketForm"
                className=""
            />
        </div>

        <div class="col-6">

            <div class="form-group">
                <label class="input-label">{{ trans('public.description') }}</label>
                <textarea name="description" rows="2" class="js-ajax-description form-control">{!! (!empty($ticket) and !empty($ticket->translate($locale))) ? $ticket->translate($locale)->description : old('description')  !!}</textarea>
                <div class="invalid-feedback d-block"></div>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="input-label">{{ trans('public.capacity') }}</label>
                <input type="number" name="capacity" value="{{ !empty($ticket) ? $ticket->capacity : old('capacity') }}" class="js-ajax-capacity form-control"/>
                <div class="invalid-feedback d-block"></div>
                <div class="text-gray-500 text-small mt-1">{{ trans('update.leave_blank_for_unlimited_capacity') }}</div>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <label class="input-label">{{ trans('public.point') }}</label>
                <input type="number" name="point" value="{{ !empty($ticket) ? $ticket->point : old('point') }}" class="js-ajax-point form-control"/>
                <div class="invalid-feedback d-block"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.price') }}</label>
                <span class="has-translation bg-gray-100 text-gray-500">{{ $currency }}</span>
                <input type="text" name="price" class="js-ajax-price form-control" value="{{ (!empty($ticket) and !empty($ticket->price)) ? convertPriceToUserCurrency($ticket->price) : old('price') }}" placeholder="{{ trans('public.0_for_free') }}" oninput="validatePrice(this)"/>
                <div class="invalid-feedback d-block"></div>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.discount') }}</label>
                <span class="has-translation bg-gray-100 text-gray-500">%</span>
                <input type="number" name="discount" class="js-ajax-discount form-control" value="{{ (!empty($ticket) and !empty($ticket->discount)) ? $ticket->discount : old('discount') }}"/>
                <div class="invalid-feedback d-block"></div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="input-label">{{ trans('update.discount_date_range') }}</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="dateRangeLabel">
                    <x-iconsax-bul-calendar-2 class="" width="24px" height="24px"/>
                </span>
            </div>
            <input type="text" name="discount_date_range" class="js-ajax-discount_date_range form-control date-range-picker" aria-describedby="dateRangeLabel" autocomplete="off" data-format="YYYY/MM/DD HH:mm" data-timepicker="true" value="{{ (!empty($ticket) and !empty($ticket->discount_start_at) and !empty($ticket->discount_end_at)) ? (dateTimeFormat($ticket->discount_start_at, 'Y/m/j H:i', false). ' - '. dateTimeFormat($ticket->discount_end_at, 'Y/m/j H:i', false)) : '' }}"/>
            <div class="invalid-feedback"></div>
        </div>
    </div>

    @php
        $ticketOptions = (!empty($ticket) and !empty($ticket->translate($locale))) ? explode(',', $ticket->translate($locale)->options) : [];
    @endphp

    <div class="form-group">
        <label class="input-label d-block">{{ trans('update.options') }}</label>
        <input type="text" name="options" data-max-tag="5" value="{{ !empty($ticketOptions) ? implode(',', $ticketOptions) : '' }}" class="form-control inputtags" placeholder="{{ trans('public.type_tag_name_and_press_enter') }} ({{ trans('admin/main.max') }} : 5)"/>
    </div>


    <div class="form-group mt-30 d-flex align-items-center ">
        <label class="cursor-pointer mr-16" for="enableTicketSwitch">{{ trans('update.enable') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="enable" class="custom-control-input" id="enableTicketSwitch" {{ (!empty($ticket) and $ticket->enable) ? 'checked' : ''  }}>
            <label class="custom-control-label" for="enableTicketSwitch"></label>
        </div>
    </div>

</form>

