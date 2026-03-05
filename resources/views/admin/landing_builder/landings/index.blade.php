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

            {{-- Top Stats --}}
            @include('admin.landing_builder.landings.top_stats')

            <div class="row mt-40">
                @foreach($landingItems as $landingItem)
                    <div class="col-12 col-md-4 col-lg-3 mb-20">
                        <div class="bg-white p-12 rounded-16 w-100 h-100">
                            <div class="js-landing-item-preview-image landing-pages__landing-item-preview-image rounded-8 bg-gray-100" data-duration="10000">
                                <img src="{{ $landingItem->preview_img }}" alt="{{ $landingItem->title }}" class="img-cover rounded-8">

                                @if($landingItem->enable)
                                    <div class="landing-pages__landing-item-status p-6 font-12 rounded-8 bg-success-40 text-success">{{ trans('public.active') }}</div>
                                @else
                                    <div class="landing-pages__landing-item-status p-6 font-12 rounded-8 bg-danger-40 text-danger">{{ trans('public.inactive') }}</div>
                                @endif

                                <div class="landing-pages__landing-item-dropdown btn-group dropdown table-actions">
                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                        <x-iconsax-lin-more class="icons text-gray-500" width="24px" height="24px"/>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ $landingItem->getUrl() }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                            <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                            <span class="text-gray-500 font-14">{{ trans('update.preview') }}</span>
                                        </a>

                                        <a href="{{ getLandingBuilderUrl("/{$landingItem->id}/edit") }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                            <span class="text-gray-500 font-14">{{ trans('public.edit') }}</span>
                                        </a>

                                        @can('admin_landing_builder_duplicate')
                                            @include('admin.includes.delete_button',[
                                                'url' => getLandingBuilderUrl("/{$landingItem->id}/duplicate"),
                                                'btnClass' => 'dropdown-item text-gray-500 mb-3 py-3 px-0 font-14',
                                                'btnText' => trans('public.duplicate'),
                                                'btnIcon' => 'copy',
                                                'iconType' => 'lin',
                                                'iconClass' => 'text-gray-500 mr-2',
                                            ])
                                        @endcan

                                        @can('admin_landing_builder_delete')
                                            @include('admin.includes.delete_button',[
                                                'url' => getLandingBuilderUrl("/{$landingItem->id}/delete"),
                                                'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                'btnText' => trans('admin/main.delete'),
                                                'btnIcon' => 'trash',
                                                'iconType' => 'lin',
                                                'iconClass' => 'text-danger mr-2',
                                            ])
                                        @endcan

                                    </div>
                                </div>

                            </div>

                            <div class="d-flex align-items-start flex-column p-4 pt-16 w-100">
                                <h5 class="font-16">{{ $landingItem->title }}</h5>

                                <div class="d-inline-flex-center p-8 rounded-8 bg-gray-100 gap-4 font-12 mt-16">
                                    <x-iconsax-bul-category class="icons text-gray-500" width="20px" height="20px"/>
                                    <span class="font-weight-bold">{{ $landingItem->components_count }}</span>
                                    <span class="text-gray-500 font-14">{{ trans('update.components') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/jquery-image-scroll/jquery-image-scroll.js"></script>
    <script src="/assets/admin/js/parts/landing_lists.min.js"></script>
@endpush
