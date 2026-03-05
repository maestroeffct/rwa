@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@php
    $values = !empty($setting) ? $setting->value : null;

    if (!empty($values)) {
        $values = json_decode($values, true);
    }

@endphp


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item">{{ trans('update.settings') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form action="{{ getAdminPanelUrl('/settings/main') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="page" value="general">
                                <input type="hidden" name="name" value="{{ \App\Models\Setting::$becomeInstructorSettingsName }}">
                                <input type="hidden" name="locale" value="{{ \App\Models\Setting::$defaultSettingsLocale }}">


                                <div class="section-title after-line mb-3">{{ trans('public.images') }}</div>


                                <div class="mb-4">
                                    <div class="font-16 text-dark mb-16">{{ trans('update.images') }}</div>

                                    @foreach(['main_image', 'overlay_image'] as $type)
                                        <div class="form-group mb-16">
                                            <label class="input-label">{{ trans("update.{$type}") }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager" data-input="{{ $type }}" data-preview="holder">
                                                        <i class="fa fa-chevron-up"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="value[{{ $type }}]" id="{{ $type }}" value="{{ (!empty($values) and !empty($values[$type])) ? $values[$type] : '' }}" class="form-control"/>
                                                <div class="input-group-append">
                                                    <button type="button" class="input-group-text admin-file-view" data-input="{{ $type }}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


