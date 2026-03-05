@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.themes') }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.themes')}}</div>
            </div>
        </div>

        <div class="section-body">

            {{-- Top Stats --}}


            <div class="row mt-40">
                @foreach($themes as $themeItem)
                    <div class="col-12 col-md-4 col-lg-3 mb-20">
                        <div class="bg-white p-12 rounded-16 w-100 h-100">
                            <div class="js-landing-item-preview-image landing-pages__landing-item-preview-image rounded-8 bg-gray-100" data-duration="10000">
                                <img src="{{ $themeItem->preview_image }}" alt="{{ $themeItem->title }}" class="img-cover rounded-8">

                                <div class="landing-pages__landing-item-status d-flex align-items-center gap-12">
                                    @if($themeItem->is_default)
                                        <div class="p-6 font-12 rounded-8 bg-primary text-white">{{ trans('update.default') }}</div>
                                    @endif

                                    @if($themeItem->enable)
                                        <div class="p-6 font-12 rounded-8 bg-success text-white">{{ trans('public.active') }}</div>
                                    @else
                                        <div class="p-6 font-12 rounded-8 bg-danger text-white">{{ trans('public.inactive') }}</div>
                                    @endif
                                </div>

                                <div class="landing-pages__landing-item-dropdown btn-group dropdown table-actions">
                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                        <x-iconsax-lin-more class="icons text-gray-500" width="24px" height="24px"/>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right">


                                        <a href="{{ getAdminPanelUrl("/themes/{$themeItem->id}/edit") }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                            <span class="text-gray-500 font-14">{{ trans('public.edit') }}</span>
                                        </a>

                                        @if(!$themeItem->enable)
                                            @include('admin.includes.delete_button',[
                                                'url' => getAdminPanelUrl("/themes/{$themeItem->id}/enable"),
                                                'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                'btnText' => trans('update.enable'),
                                                'btnIcon' => 'tick-square',
                                                'iconType' => 'lin',
                                                'iconClass' => 'text-success mr-2',
                                            ])
                                        @endif

                                        @if(!$themeItem->is_default)
                                            @include('admin.includes.delete_button',[
                                                'url' => getAdminPanelUrl("/themes/{$themeItem->id}/delete"),
                                                'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                'btnText' => trans('admin/main.delete'),
                                                'btnIcon' => 'trash',
                                                'iconType' => 'lin',
                                                'iconClass' => 'text-danger mr-2',
                                            ])
                                        @endif

                                    </div>
                                </div>


                            </div>

                            <div class="d-flex align-items-start flex-column p-4 pt-16 w-100">
                                <h5 class="font-16">{{ $themeItem->title }}</h5>

                                {{--<div class="d-inline-flex-center p-8 rounded-8 bg-gray-100 gap-4 font-12 mt-16">
                                    <x-iconsax-bul-category class="icons text-gray-500" width="20px" height="20px"/>
                                    <span class="font-weight-bold">{{ $themeItem->components_count }}</span>
                                    <span class="text-gray-500 font-14">{{ trans('update.components') }}</span>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-40 text-center">
                {{ $themes->appends(request()->input())->links() }}
            </div>

        </div>
    </section>

@endsection

@push('scripts_bottom')

    <script src="/assets/vendors/jquery-image-scroll/jquery-image-scroll.js"></script>
    <script src="/assets/admin/js/parts/landing_lists.min.js"></script>
@endpush
