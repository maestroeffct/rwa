@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.mobile_app_configuration') }} {{ trans('admin/main.settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}/settings">{{ trans('admin/main.settings') }}</a></div>
                <div class="breadcrumb-item ">{{ trans('update.mobile_app_configuration') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @php
                                $itemsByName = [
                                    \App\Models\Setting::$mobileAppGeneralSettingsName => 'general',
                                ];

                                $itemsUrl = [
                                    'general' => '',
                                ];
                            @endphp

                            <ul class="nav nav-pills border-bottom-gray-200" id="myTab3" role="tablist">
                                @foreach($itemsUrl as $itemName => $itemUrl)
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($itemsByName[$name] == $itemName) ? 'active' : '' }}" href="{{ getAdminPanelUrl("/settings/mobile-app{$itemUrl}") }}">{{ trans("update.{$itemName}") }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @if($name == \App\Models\Setting::$mobileAppGeneralSettingsName)
                                    @include("admin.settings.mobile_app.general")
                                @endif
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
