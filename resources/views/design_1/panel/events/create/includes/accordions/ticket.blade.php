<li data-id="{{ !empty($ticket) ? $ticket->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="ticket_{{ !empty($ticket) ? $ticket->id :'record' }}">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}" data-parent="#ticketsAccordion" role="button" data-toggle="collapse">
            <span>{{ !empty($ticket) ? $ticket->title : trans('update.new_ticket') }}</span>
        </div>

        @if(!empty($ticket))
            <div class="d-flex align-items-center">
                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/events/{{ $event->id }}/tickets/{{ $ticket->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}" data-parent="#ticketsAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}" class="accordion__collapse {{ empty($ticket) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form js-ticket-form mt-20" data-action="/panel/events/{{ $event->id }}/tickets/{{ !empty($ticket) ? $ticket->id . '/update' : 'store' }}">


            @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($ticket) ? $ticket : null,
                'withoutReloadLocale' => true,
                'extraClass' => 'js-event-content-locale',
                'extraData' => "data-event-id='".(!empty($event) ? $event->id : '')."'  data-id='".(!empty($ticket) ? $ticket->id : '')."'  data-relation='tickets' data-fields='title,description,options'"
            ])

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.title') }}</label>

                <span class="has-translation bg-gray-100 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>

                <input type="text" name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($ticket) ? $ticket->title : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.description') }}</label>

                <span class="has-translation bg-gray-100 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>

                <input type="text" name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][description]" class="js-ajax-description form-control" value="{{ !empty($ticket) ? $ticket->description : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <x-landingBuilder-icons-select
                label="{{ trans('update.icon') }}"
                name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][icon]"
                value="{{ !empty($ticket) ? $ticket->icon : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-icons-select2"
                dropdownParent="#collapseTicket{{ !empty($ticket) ? $ticket->id :'record' }}"
                className=""
            />

            <h4 class="font-14">{{ trans('update.pricing_&_discount') }}</h4>

            <div class="form-group mt-24">
                <label class="form-group-label">{{ trans('public.price') }}</label>
                <span class="has-translation bg-gray-100 text-gray-500">{{ $currency }}</span>
                <input type="text" name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][price]" class="js-ajax-price form-control" value="{{ (!empty($ticket) and !empty($ticket->price)) ? convertPriceToUserCurrency($ticket->price) : '' }}" placeholder="{{ trans('public.0_for_free') }}" oninput="validatePrice(this)"/>
                <div class="invalid-feedback d-block"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.capacity') }}</label>
                <input type="number" name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][capacity]" value="{{ !empty($ticket) ? $ticket->capacity : '' }}" class="js-ajax-capacity form-control"/>
                <div class="invalid-feedback d-block"></div>
                <div class="text-gray-500 font-12 mt-8">{{ trans('update.leave_blank_for_unlimited_capacity') }}</div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.discount') }}</label>
                <span class="has-translation bg-gray-100 text-gray-500">%</span>
                <input type="number" name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][discount]" class="js-ajax-discount form-control" value="{{ (!empty($ticket) and !empty($ticket->discount)) ? $ticket->discount : '' }}"/>
                <div class="invalid-feedback d-block"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('update.discount_date_range') }}</label>
                <span class="has-translation bg-gray-100 text-gray-500"><x-iconsax-bul-calendar-2 class="text-gray-500" width="24px" height="24px"/></span>
                <input type="text" name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][discount_date_range]" class="js-ajax-discount_date_range form-control date-range-picker" aria-describedby="dateRangeLabel" autocomplete="off" data-format="YYYY/MM/DD HH:mm" data-timepicker="true" value="{{ (!empty($ticket) and !empty($ticket->discount_start_at) and !empty($ticket->discount_end_at)) ? (dateTimeFormat($ticket->discount_start_at, 'Y/m/j H:i', false). ' - '. dateTimeFormat($ticket->discount_end_at, 'Y/m/j H:i', false)) : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <h4 class="font-14">{{ trans('update.options') }}</h4>

            @php
                $ticketOptions = (!empty($ticket) and !empty($ticket->options)) ? explode(',', $ticket->options) : [];
            @endphp

            <div class="form-group mt-16">
                <input type="text" name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][options]" data-max-tag="5" value="{{ !empty($ticketOptions) ? implode(',', $ticketOptions) : '' }}" class="form-control inputtags" placeholder="{{ trans('public.type_tag_name_and_press_enter') }} ({{ trans('admin/main.max') }} : 5)"/>
            </div>

            <div class="form-group d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="enableTicketSwitch_{{ !empty($ticket) ? $ticket->id : 'new' }}" type="checkbox" name="ajax[{{ !empty($ticket) ? $ticket->id : 'new' }}][enable]" class="custom-control-input" {{ (!empty($ticket) and $ticket->enable) ? 'checked' : '' }}>
                    <label class="custom-control-label cursor-pointer" for="enableTicketSwitch_{{ !empty($ticket) ? $ticket->id : 'new' }}"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="enableTicketSwitch_{{ !empty($ticket) ? $ticket->id : 'new' }}">{{ trans('update.enable') }}</label>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-course-content btn btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($ticket))
                    <a href="/panel/events/{{ $event->id }}/tickets/{{ $ticket->id }}/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
