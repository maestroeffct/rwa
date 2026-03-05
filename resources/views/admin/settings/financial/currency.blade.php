@php
    if (!empty($itemValue) and !is_array($itemValue)) {
        $itemValue = json_decode($itemValue, true);
    }
@endphp


<div class="tab-pane mt-3 fade  @if(request()->get('tab') == "currency") active show @endif" id="currency" role="tabpanel" aria-labelledby="currency-tab">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="{{ getAdminPanelUrl() }}/settings/main" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="page" value="financial">
                <input type="hidden" name="name" value="{{ \App\Models\Setting::$currencySettingsName }}">


                <div class="form-group">
                    <label class="input-label d-block">{{ trans('update.default_currency') }}</label>
                    <select name="value[currency]" class="form-control select2" data-placeholder="{{ trans('admin/main.currency') }}">
                        <option value=""></option>
                        @foreach(currenciesLists() as $key => $currencyListItem)
                            <option value="{{ $key }}" @if((!empty($itemValue) and !empty($itemValue['currency'])) and $itemValue['currency'] == $key) selected @endif >{{ $currencyListItem }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label class="input-label d-block">{{ trans('update.currency_position') }}</label>
                    <select name="value[currency_position]" class="form-control">
                        @foreach(\App\Models\Currency::$currencyPositions as $position)
                            <option value="{{ $position }}" @if((!empty($itemValue) and !empty($itemValue['currency_position'])) and $itemValue['currency_position'] == $position) selected @endif >{{ trans('update.currency_position_'.$position) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="input-label d-block">{{ trans('update.currency_separator') }}</label>
                    <select name="value[currency_separator]" class="form-control">
                        <option value="dot" @if((!empty($itemValue) and !empty($itemValue['currency_separator'])) and $itemValue['currency_separator'] == 'dot') selected @endif >{{ trans('update.currency_separator_dot') }}</option>
                        <option value="comma" @if((!empty($itemValue) and !empty($itemValue['currency_separator'])) and $itemValue['currency_separator'] == 'comma') selected @endif >{{ trans('update.currency_separator_comma') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="input-label d-block">{{ trans('update.currency_decimal') }}</label>
                    <input type="number" name="value[currency_decimal]" class="form-control" min="0" max="3" value="{{ (!empty($itemValue) and !empty($itemValue['currency_decimal'])) ? $itemValue['currency_decimal'] : 0 }}">
                </div>


                <div class="form-group custom-switches-stacked">
                    <label class="custom-switch pl-0 d-flex align-items-center">
                        <input type="hidden" name="value[multi_currency]" value="0">
                        <input type="checkbox" name="value[multi_currency]" id="multiCurrencySwitch" value="1" {{ (!empty($itemValue) and !empty($itemValue['multi_currency']) and $itemValue['multi_currency']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description mb-0 cursor-pointer" for="multiCurrencySwitch">{{ trans('update.enable_multi_currency') }}</label>
                    </label>
                </div>

                <div class="form-group js-guests-default-currency-section {{ (!empty($itemValue) and !empty($itemValue['multi_currency'])) ? : "d-none" }}">
                    <label class="input-label d-block">{{ trans('admin/main.guest_default_currency') }}</label>
                    <select name="value[visitors_default_currency]" class="form-control">
                        <option value="default" @if(empty($itemValue) or empty($itemValue['visitors_default_currency']) or $itemValue['visitors_default_currency'] == 'default') selected @endif>{{ trans('admin/main.use_default_currency') }}</option>
                        <option value="detect_ip" @if(!empty($itemValue) and !empty($itemValue['visitors_default_currency']) and $itemValue['visitors_default_currency'] == 'detect_ip') selected @endif>{{ trans('admin/main.use_ip_currency') }}</option>
                    </select>
                    <div class="text-gray-500 text-small mt-1">{{ trans('admin/main.guest_default_currency_hint') }}</div>
                </div>

                <section class="js-multi-currency-section mt-3 {{ (!empty($itemValue) and !empty($itemValue['multi_currency'])) ? : "d-none" }}">
                    <div class="d-flex justify-content-between align-items-center pb-2">
                        <h2 class="section-title after-line">{{ trans('update.currencies') }}</h2>

                        <button id="add_multi_currency" type="button" class="btn btn-primary btn-sm ml-2">{{ trans('update.add_currency') }}</button>
                    </div>

                    <ul class="draggable-currency-lists mb-5" data-order-table="currencies">

                        @foreach($currencies as $currencyItem)
                            <li data-id="{{ $currencyItem->id }}" class="quiz-question-card d-flex align-items-center mt-4">
                                <div class="flex-grow-1">
                                    <h4 class="font-16 text-black font-weight-500 text-dark mb-0">{{ currenciesLists($currencyItem->currency) }}</h4>
                                    <div class="font-12 mt-1 question-infos">{{ trans('update.exchange_rate') }}: {{ $currencyItem->exchange_rate }}</div>
                                </div>

                                <div class="move-icon mr-10 cursor-pointer">
                                    <i class="fa fa-arrows-alt" height="25"></i>
                                </div>

                                <div class="btn-group dropdown table-actions">
                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>


                                    <div class="dropdown-menu dropdown-menu-right">
                                        <button type="button"
                                                data-path="{{ getAdminPanelUrl("/settings/financial/currency/{$currencyItem->id}/edit") }}"
                                                class="js-edit-currency dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                            <span class="text-gray-500">{{ trans('public.edit') }}</span>
                                        </button>

                                        @include('admin.includes.delete_button',[
                                            'url' => getAdminPanelUrl("/settings/financial/currency/{$currencyItem->id}/delete"),
                                            'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                            'btnText' => trans('public.delete'),
                                            'btnIcon' => 'trash',
                                            'iconType' => 'lin',
                                            'iconClass' => 'text-danger mr-2',
                                        ])
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <button type="submit" class="btn btn-success">{{ trans('admin/main.save_change') }}</button>
            </form>
        </div>
    </div>
</div>


@include('admin.settings.financial.currency_modal')


@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    </script>
    <script src="/assets/admin/js/parts/settings/currencies.min.js"></script>
@endpush
