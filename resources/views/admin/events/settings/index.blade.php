@extends('admin.layouts.app')


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.events_settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.events_settings') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <form action="{{ getAdminPanelUrl("/events/settings") }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="page" value="general">
                                        <input type="hidden" name="name" value="{{ \App\Models\Setting::$eventsSettingsName }}">

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[status]" value="0">
                                                <input type="checkbox" name="value[status]" id="eventsStatusSwitch" value="1" {{ (!empty($values) and !empty($values['status'])) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="eventsStatusSwitch">{{ trans('admin/main.active') }}</label>
                                            </label>
                                            <div class="text-gray-500 text-small">{{ trans('update.events_setting_active_hint') }}</div>
                                        </div>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[qr_status]" value="0">
                                                <input type="checkbox" name="value[qr_status]" id="eventsQrStatusSwitch" value="1" {{ (!empty($values) and !empty($values['qr_status'])) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="eventsQrStatusSwitch">{{ trans('update.enable_qr') }}</label>
                                            </label>
                                            <div class="text-gray-500 text-small">{{ trans('update.events_setting_qr_switch_hint') }}</div>
                                        </div>

                                        @php
                                            $formats = ['numerical', 'textual', 'both'];
                                        @endphp

                                        <div class="form-group">
                                            <label class="">{{ trans('update.ticket_code_format') }}</label>
                                            <select name="value[ticket_code_format]" class="form-control">
                                                @foreach($formats as $format)
                                                    <option value="{{ $format }}" {{ (!empty($values) and !empty($values['ticket_code_format']) and $values['ticket_code_format'] == $format) ? 'selected' : '' }}>{{ trans("update.{$format}") }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.number_of_characters_ticket_code') }}</label>
                                            <input type="number" name="value[number_of_characters_ticket_code]" value="{{ (!empty($values) and !empty($values['number_of_characters_ticket_code'])) ? $values['number_of_characters_ticket_code'] : '' }}" class="form-control"/>
                                            <div class="mt-8 font-12 text-gray-500">{{ trans('update.number_of_characters_ticket_code_input_hint') }}</div>
                                        </div>

                                        @php
                                            $ticketCards = ['lists', 'grid'];
                                        @endphp

                                        <div class="form-group">
                                            <label class="">{{ trans('update.ticket_card_style') }}</label>
                                            <select name="value[ticket_card_style]" class="form-control">
                                                @foreach($ticketCards as $card)
                                                    <option value="{{ $card }}" {{ (!empty($values) and !empty($values['ticket_card_style']) and $values['ticket_card_style'] == $card) ? 'selected' : '' }}>{{ trans("update.{$card}") }}</option>
                                                @endforeach
                                            </select>

                                            <div class="mt-8 font-12 text-gray-500">{{ trans('update.events_setting_ticket_card_style_input_hint') }}</div>
                                        </div>

                                        <x-landingBuilder-icons-select
                                            label="{{ trans('update.tickets_default_icon') }}"
                                            name="value[tickets_default_icon]"
                                            value="{{ (!empty($values) and !empty($values['tickets_default_icon'])) ? $values['tickets_default_icon'] : '' }}"
                                            placeholder="{{ trans('update.search_icons') }}"
                                            hint=""
                                            selectClassName="js-icons-select2"
                                            className=""
                                        />

                                        <div class="form-group mt-3 custom-switches-stacked">
                                            <label class="custom-switch pl-0">
                                                <input type="hidden" name="value[event_recent_reviews_status]" value="0">
                                                <input type="checkbox" name="value[event_recent_reviews_status]" id="event_recent_reviews_statusSwitch" value="1"
                                                       {{ (!empty($values) and !empty($values['event_recent_reviews_status']) and $values['event_recent_reviews_status']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="event_recent_reviews_statusSwitch">{{ trans('update.event_recent_reviews') }}</label>
                                            </label>
                                            <p class="font-12 text-gray-500 mb-0">{{ trans('update.event_recent_reviews_status_hint') }}</p>
                                        </div>

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
                                        </div>

                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
