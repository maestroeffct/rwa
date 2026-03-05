@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.personalization') }} {{ trans('admin/main.settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}/settings">{{ trans('admin/main.settings') }}</a></div>
                <div class="breadcrumb-item ">{{ trans('admin/main.personalization') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @php
                                $items = ['panel_sidebar',
                                            'cookie_settings', 'mobile_app', 'maintenance_settings',
                                            'restriction_settings', 'guaranty_text', 'user_dashboard_data', 'content_review_information', 'others_personalization'
                                         ];
                            @endphp

                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                @foreach($items as $item)
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($item == $name) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/settings/personalization/{{ $item }}">{{ trans('admin/main.'.$item) }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @include('admin.settings.personalization.'.$name,['itemValue' => (!empty($values)) ? $values : ''])
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
