@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body pb-0">

                            <ul class="nav nav-pills" id="myTab3" role="tablist">

                                @php
                                    $tabs = [
                                        'general',
                                        'homepage',
                                        'homepage_revolver',
                                        'images',
                                        'cta_section',
                                    ];
                                @endphp

                                @foreach($tabs as $tabName)
                                    @php
                                        $tabUrl = "/forums/settings";

                                        if ($tabName != "general") {
                                            $tabUrl .= "/" . $tabName;
                                        }
                                    @endphp
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($pageTab == $tabName) ? 'active' : '' }}" href="{{ getAdminPanelUrl($tabUrl) }}">{{ trans("update.{$tabName}") }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                <div class="tab-pane mt-3 fade show active">
                                    @include("admin.forums.settings.tabs.{$pageTab}")
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
    <script src="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>

@endpush
