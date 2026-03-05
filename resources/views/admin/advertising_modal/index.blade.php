@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.advertising_modal') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.advertising_modal') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ getAdminPanelUrl() }}/advertising_modal" method="post">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-12 col-md-6">

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.image') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager" data-input="image" data-preview="holder">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="value[image]" id="image" value="{{ (!empty($value) and !empty($value['image'])) ? $value['image'] : old('image') }}" class="form-control"/>
                                            </div>
                                            <div class="mt-1 fs-11 text-gray-500">{{ trans('update.advertising_modal_image_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('admin/main.icon') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager" data-input="icon" data-preview="holder">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="value[icon]" id="icon" value="{{ (!empty($value) and !empty($value['icon'])) ? $value['icon'] : old('icon') }}" class="form-control"/>
                                            </div>
                                            <div class="mt-1 fs-11 text-gray-500">{{ trans('update.advertising_modal_icon_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.title') }}</label>
                                            <input type="text" name="value[title]" value="{{ (!empty($value) and !empty($value['title'])) ? $value['title'] : old('title') }}" class="form-control "/>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('public.description') }}</label>
                                            <textarea type="text" name="value[description]" rows="5" class="form-control ">{{ (!empty($value) and !empty($value['description'])) ? $value['description'] : old('description') }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.countdown') }}</label>
                                            <input type="text" name="value[countdown]" class="form-control datetimepicker"
                                                   aria-describedby="dateRangeLabel" autocomplete="off" data-drops="down"
                                                   value="{{ (!empty($value) and !empty($value['countdown'])) ? dateTimeFormat($value['countdown'], 'Y-m-d H:i', false) : old('countdown') }}"/>
                                            <div class="mt-1 fs-11 text-gray-500">{{ trans('update.advertising_modal_countdown_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.opening_delay') }} ({{ trans('update.seconds') }})</label>
                                            <input type="number" name="value[opening_delay]" class="form-control"
                                                   value="{{ (!empty($value) and !empty($value['opening_delay'])) ? $value['opening_delay'] : old('opening_delay') }}"/>
                                            <div class="mt-1 fs-11 text-gray-500">{{ trans('update.advertising_modal_opening_delay_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.autoclose') }} ({{ trans('update.seconds') }})</label>
                                            <input type="number" name="value[autoclose]" class="form-control"
                                                   value="{{ (!empty($value) and !empty($value['autoclose'])) ? $value['autoclose'] : old('autoclose') }}"/>
                                            <div class="mt-1 fs-11 text-gray-500">{{ trans('update.advertising_modal_autoclose_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.button') }}</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <label>{{ trans('admin/main.title') }}</label>
                                                    <input type="text" name="value[button1][title]" value="{{ (!empty($value) and !empty($value['button1']) and !empty($value['button1']['title'])) ? $value['button1']['title'] : '' }}" class="form-control "/>
                                                </div>
                                                <div class="col-6">
                                                    <label>{{ trans('admin/main.subtitle') }}</label>
                                                    <input type="text" name="value[button1][subtitle]" value="{{ (!empty($value) and !empty($value['button1']) and !empty($value['button1']['subtitle'])) ? $value['button1']['subtitle'] : '' }}" class="form-control "/>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <label>{{ trans('admin/main.link') }}</label>
                                                    <input type="text" name="value[button1][link]" value="{{ (!empty($value) and !empty($value['button1']) and !empty($value['button1']['link'])) ? $value['button1']['link'] : '' }}" class="form-control "/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[status]" value="0">
                                                <input type="checkbox" name="value[status]" id="advertiseModalStatusSwitch" value="1" {{ (!empty($value) and !empty($value['status']) and $value['status']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="advertiseModalStatusSwitch">{{ trans('admin/main.active') }}</label>
                                            </label>
                                            <div class="text-gray-500 text-small mt-1">{{ trans('update.advertising_modal_status_hint') }}</div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-6 text-right">
                                    <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                                    <button type="button" class="js-preview-modal btn btn-warning ml-2" data-path="{{ getAdminPanelUrl("/advertising_modal/preview") }}">{{ trans('update.preview') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

    <script src="/assets/design_1/js/parts/time-counter-down.min.js"></script>

    <script src="/assets/admin/js/parts/advertising_modal.min.js"></script>
@endpush
