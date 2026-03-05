@extends('admin.layouts.app')


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('admin/main.settings') }}</div>
            </div>
        </div>

        <div class="section-body">


            <div class="row">
                @can('admin_settings_general')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary-20 text-white">
                                <x-iconsax-bul-setting-5 class="icons text-primary" width="48px" height="48px"/>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.general_card_title') }}</h4>
                                <p>{{ trans('admin/main.general_card_hint') }}</p>
                                <a href="{{ getAdminPanelUrl() }}/settings/general" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_financial')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary-20 text-white">
                                <x-iconsax-bul-calculator class="icons text-primary" width="48px" height="48px"/>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.financial_card_title') }}</h4>
                                <p>{{ trans('admin/main.financial_card_hint') }}</p>
                                <a href="{{ getAdminPanelUrl() }}/settings/financial" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_personalization')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary-20 text-white">
                                <x-iconsax-bul-bucket class="icons text-primary" width="48px" height="48px"/>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.personalization_card_title') }}</h4>
                                <p>{{ trans('admin/main.personalization_card_hint') }}</p>
                                <a href="{{ getAdminPanelUrl() }}/settings/personalization/panel_sidebar" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_notifications')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary-20 text-white">
                                <x-iconsax-bul-notification class="icons text-primary" width="48px" height="48px"/>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.notifications_card_title') }}</h4>
                                <p>{{ trans('admin/main.notifications_card_hint') }}</p>
                                <a href="{{ getAdminPanelUrl() }}/settings/notifications" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_seo')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary-20 text-white">
                                <x-iconsax-bul-global-search class="icons text-primary" width="48px" height="48px"/>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.seo_card_title') }}</h4>
                                <p>{{ trans('admin/main.seo_card_hint') }}</p>
                                <a href="{{ getAdminPanelUrl() }}/settings/seo" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_mobile_app')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary-20 text-white">
                                <x-iconsax-bul-mobile class="icons text-primary" width="48px" height="48px"/>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('update.mobile_app_configuration') }}</h4>
                                <p>{{ trans('update.mobile_app_configuration_hint') }}</p>
                                <a href="{{ getAdminPanelUrl("/settings/mobile-app") }}" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_update_app')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary-20 text-white">
                                <x-iconsax-bul-refresh-2 class="icons text-primary" width="48px" height="48px"/>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('update.update_app_card_title') }}</h4>
                                <p>{{ trans('update.update_app_card_hint') }}</p>
                                <a href="{{ getAdminPanelUrl("/settings/update-app") }}" class="card-cta text-primary">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </section>
@endsection
