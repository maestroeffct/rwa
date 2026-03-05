@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
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
                        <div class="card-body">

                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">{{ trans('admin/main.basic') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="terms-tab" data-toggle="tab" href="#terms" role="tab" aria-controls="terms" aria-selected="true">{{ trans('update.terms_&_policies') }}</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                <div class="tab-pane mt-3 fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                    @include('admin.financial.installments.settings.basic_tab')
                                </div>

                                <div class="tab-pane mt-3 fade" id="terms" role="tabpanel" aria-labelledby="terms-tab">
                                    @include('admin.financial.installments.settings.terms_tab')
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
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
@endpush
