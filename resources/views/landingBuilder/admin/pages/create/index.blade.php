@extends('landingBuilder.admin.layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

@section('content')
    <div class="bg-white p-16 rounded-24 mt-20">
        <div class="d-flex align-items-center justify-content-between">
            @if(!empty($landingItem))
                <div class="">
                    <h2 class="font-16 text-dark">{{ trans('update.edit_landing_page') }}</h2>
                    <p class="text-gray-500 mt-2">{{ trans('update.edit_and_manage_your_landing_page_information') }}</p>
                </div>

                <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                    <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                        <ul class="my-8">

                            @can('admin_landing_builder_preview')
                                <li class="actions-dropdown__dropdown-menu-item">
                                    <a href="{{ $landingItem->getUrl() }}" target="_blank" class="">{{ trans('update.preview') }}</a>
                                </li>
                            @endcan

                            @can('admin_landing_builder_duplicate')
                                <li class="actions-dropdown__dropdown-menu-item">
                                    <a href="{{ getLandingBuilderUrl("/{$landingItem->id}/duplicate") }}" class="delete-action" data-title="" data-confirm="{{ trans('public.duplicate') }}">{{ trans('public.duplicate') }}</a>
                                </li>
                            @endcan

                            @can('admin_landing_builder_delete')
                                <li class="actions-dropdown__dropdown-menu-item">
                                    <a href="{{ getLandingBuilderUrl("/{$landingItem->id}/delete") }}" class="delete-action text-danger ">{{ trans('public.delete') }}</a>
                                </li>
                            @endcan

                        </ul>
                    </div>
                </div>
            @else
                <div class="">
                    <h2 class="font-16 text-dark">{{ trans('update.new_landing_page') }}</h2>
                    <p class="text-gray-500 mt-2">{{ trans('update.manage_your_landing_page_information') }}</p>
                </div>
            @endif
        </div>

        <div class="custom-tabs mt-16">
            <div class="d-flex align-items-center flex-wrap border-bottom-gray-200 border-top-gray-200 px-16">
                <div class="navbar-item navbar-item-h-52 d-flex-center mr-24 mr-md-40 cursor-pointer active" data-tab-toggle data-tab-href="#basicInformationTab">
                    <x-iconsax-lin-setting-2 class="icons" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('public.basic_information') }}</span>
                </div>

                <div class="navbar-item navbar-item-h-52 d-flex-center cursor-pointer" data-tab-toggle data-tab-href="#componentsTab">
                    <x-iconsax-lin-category-2 class="icons" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.components') }}</span>
                </div>
            </div>

            <div class="custom-tabs-body mt-20">

                <div class="custom-tabs-content active" id="basicInformationTab">
                    @include('landingBuilder.admin.pages.create.tabs.basic_information')
                </div>


                <div class="custom-tabs-content" id="componentsTab">
                    @include('landingBuilder.admin.pages.create.tabs.components')
                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script src="/assets/design_1/landing_builder/js/components.min.js"></script>
@endpush
