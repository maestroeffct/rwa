@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.landing_page_builder') }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.landing_builder')}}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex-center flex-column text-center py-80">
                        <div class="">
                            <img src="/assets/design_1/img/landing_builder/init.svg" alt="" class="img-fluid" height="300px">
                        </div>

                        <h4 class="font-16 mt-3 mb-0">{{ trans('update.loading_landing_builder') }}</h4>
                        <p class="mt-2 text-gray-500">{{ trans('update.you_will_be_redirected_to_the_landing_builder_please_wait_for_a_moment') }}</p>

                        <div class="js-progress-card progress w-50 " data-seconds="15" data-url="{{ getLandingBuilderUrl("/") }}">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>

                        <a href="{{ getLandingBuilderUrl("/create") }}" class="btn btn-primary btn-lg mt-40">{{ trans('update.access_now...') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/landing_builder_welcome.min.js"></script>
@endpush
