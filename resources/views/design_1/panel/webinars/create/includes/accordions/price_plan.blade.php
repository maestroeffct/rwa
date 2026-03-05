<li data-id="{{ !empty($plan) ? $plan->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="price_plan_{{ !empty($plan) ? $plan->id :'record' }}">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapsePricePlan{{ !empty($plan) ? $plan->id :'record' }}" data-parent="#price_plansAccordion" role="button" data-toggle="collapse">
            <span>{{ !empty($plan) ? $plan->title : trans('update.new_pricing_plan') }}</span>
        </div>

        @if(!empty($plan))
            <div class="d-flex align-items-center">

                {{--<span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>--}}

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/tickets/{{ $plan->id }}/delete" class="delete-action d-flex align-items-center w-100 px-16 py-8 bg-transparent text-danger">{{ trans('delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapsePricePlan{{ !empty($plan) ? $plan->id :'record' }}" data-parent="#price_plansAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapsePricePlan{{ !empty($plan) ? $plan->id :'record' }}" class="accordion__collapse {{ empty($plan) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form js-price_plan-form mt-20" data-action="/panel/tickets/{{ !empty($plan) ? $plan->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($plan) ? $plan->id : 'new' }}][webinar_id]" value="{{ !empty($webinar) ? $webinar->id :'' }}">

            <div class="row">
                <div class="col-12 col-lg-12">

                    @include('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($plan) ? $plan : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-webinar-content-locale',
                        'className' => 'mt-24',
                        'extraData' => "data-webinar-id='".(!empty($webinar) ? $webinar->id : '')."'  data-id='".(!empty($plan) ? $plan->id : '')."'  data-relation='tickets' data-fields='title'"
                    ])

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('title') }}</label>

                        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>

                        <input type="text" name="ajax[{{ !empty($plan) ? $plan->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($plan) ? $plan->title : '' }}"/>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('public.discount') }}</label>

                        <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14">%</span>

                        <input type="text" name="ajax[{{ !empty($plan) ? $plan->id : 'new' }}][discount]" class="js-ajax-discount form-control" value="{{ !empty($plan) ? $plan->discount : '' }}"/>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('public.capacity') }}</label>

                        <input type="text" name="ajax[{{ !empty($plan) ? $plan->id : 'new' }}][capacity]" class="js-ajax-capacity form-control" value="{{ !empty($plan) ? $plan->capacity : '' }}" placeholder="{{ trans('update.leave_it_blank_for_using_course_capacity') }}"/>
                        <div class="invalid-feedback"></div>

                        @if(empty($plan) and !empty($webinar->capacity) and !empty($sumTicketsCapacities))
                            <div class="test-gray-500 font-12 mt-8">{{ trans('panel.remaining') }}: <span class="js-ticket-remaining-capacity">{{ $webinar->capacity - $sumTicketsCapacities }}</span></div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-group-label">{{ trans('public.start_date') }}</label>

                                <span class="has-translation bg-transparent rounded-8 p-8">
                                    <x-iconsax-lin-calendar class="icons text-gray-500" width="24" height="24"/>
                                </span>

                                <input type="text" name="ajax[{{ !empty($plan) ? $plan->id : 'new' }}][start_date]" class="js-ajax-start_date form-control datetimepicker js-default-init-date-picker bg-white" value="{{ !empty($plan) ? dateTimeFormat($plan->start_date, 'Y-m-d  H:i', false) :'' }}" aria-describedby="dateRangeLabel" data-drops="up"/>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-20 mt-md-0">
                            <div class="form-group mb-0">
                                <label class="form-group-label">{{ trans('webinars.end_date') }}</label>

                                <span class="has-translation bg-transparent rounded-8 p-8">
                                    <x-iconsax-lin-calendar class="icons text-gray-500" width="24" height="24"/>
                                </span>

                                <input type="text" name="ajax[{{ !empty($plan) ? $plan->id : 'new' }}][end_date]" class="js-ajax-end_date form-control datetimepicker js-default-init-date-picker bg-white" value="{{ !empty($plan) ? dateTimeFormat($plan->end_date, 'Y-m-d  H:i', false) :'' }}" aria-describedby="dateRangeLabel" data-drops="up"/>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-28 d-flex align-items-center">
                <button type="button" class="js-save-price_plan btn btn-sm btn-primary">{{ trans('save') }}</button>

                @if(!empty($plan))
                    <a href="/panel/tickets/{{ $plan->id }}/delete" class="delete-action btn btn-sm btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
