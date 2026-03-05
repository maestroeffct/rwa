@extends('admin.layouts.app')

@push('styles_top')

    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl("/themes") }}">{{ trans('update.themes') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.new_theme') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ getAdminPanelUrl('/themes/').(!empty($themeItem) ? $themeItem->id.'/update' : 'store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @php
                                    $tabs = ['general_information', 'card_styles', 'home_sections', 'images', 'authentication_pages', 'custom_css_js']
                                @endphp

                                <ul class="nav nav-pills">
                                    @foreach($tabs as $tab)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $tab }}-tab" data-toggle="tab" href="#{{ $tab }}" role="tab" aria-controls="{{ $tab }}" aria-selected="true">{{ trans("update.{$tab}") }}</a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content">
                                    @foreach($tabs as $tab)
                                        <div class="tab-pane mt-3 fade {{ $loop->first ? 'show active' : '' }}" id="{{ $tab }}" role="tabpanel" aria-labelledby="{{ $tab }}-tab">
                                            @include("admin.theme.create.tabs.{$tab}")
                                        </div>
                                    @endforeach
                                </div>


                                <div class="">
                                    <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <div id="authThemeSliderMainRow" class="form-group p-3 border rounded-lg d-none position-relative">
        <button type="button" class="btn remove-btn position-absolute" style="right: 10px; top: 10px;">
            <x-iconsax-lin-close-square class="icons text-danger" width="24px" height="24px"/>
        </button>

        <div class="form-group mt-3">
            <label class="input-label">{{ trans('admin/main.title') }}</label>
            <input type="text" name="contents[authentication_pages][slider_contents][record][title]" required
                   class="form-control w-100"
                   placeholder="{{ trans('admin/main.choose_title') }}"/>
        </div>

        <div class="form-group mb-0 mt-2">
            <label class="input-label mb-0">{{ trans('admin/main.subtitle') }}</label>
            <input type="text" name="contents[authentication_pages][slider_contents][record][subtitle]" required
                   class="form-control w-100"
                   placeholder="{{ trans('admin/main.subtitle') }}"/>
        </div>

        <div class="form-group mb-0 mt-2">
            <label class="input-label mb-0">{{ trans('admin/main.image') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text admin-file-manager" data-input="auth_theme_slider_image_record" data-preview="holder">
                        <x-iconsax-lin-export class="icons text-gray-500" width="18px" height="18px"/>
                    </button>
                </div>
                <input type="text" name="contents[authentication_pages][slider_contents][record][image]" required id="auth_theme_slider_image_record" class="br-0 form-control" placeholder="{{ trans('update.auth_theme_slider_image_placeholder') }}"/>
                <div class="input-group-append">
                    <button type="button" class="input-group-text admin-file-view" data-input="auth_theme_slider_image_record">
                        <x-iconsax-lin-eye class="icons text-gray-500" width="18px" height="18px"/>
                    </button>
                </div>
            </div>
        </div>
    </div>




@endsection

@push('scripts_bottom')
    <script>
        @foreach ($errors->all() as $error)
        @if(!empty($error))
        showToast('error', 'Error', '{{ $error }}')
        @endif
        @endforeach
    </script>


    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script src="/assets/admin/js/parts/settings/cookie_settings.min.js"></script>
    <script src="/assets/admin/js/parts/create_theme.min.js"></script>
@endpush
