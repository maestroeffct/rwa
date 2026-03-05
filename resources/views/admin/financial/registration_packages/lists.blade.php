@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.registration_packages') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.registration_packages') }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('admin/main.total') }}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-people class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalPackages }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('update.active_by_instructors') }}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-teacher class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalActiveByInstructors }}</h5>
                    </div>
                </div> 
            </div>

            <div class="col-lg-4 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('update.active_by_organization') }}</span>
                            <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                <x-iconsax-bul-courthouse class="icons text-accent" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalActiveByOrganization }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card mt-32">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('admin/main.role') }}</th>
                                        <th class="text-center">{{ trans('admin/main.price') }}</th>
                                        <th class="text-center">{{ trans('public.days') }}</th>
                                        <th class="text-center">{{ trans('admin/main.sale_count') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th class="text-center">{{ trans('admin/main.created_at') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($packages as $package)
                                        <tr>
                                            <td>
                                                <img class="rounded-circle" src="{{ $package->icon }}" width="50" height="50" alt="">
                                            </td>
                                            <td class="text-left">{{ $package->title }}</td>
                                            <td class="text-center">{{ $package->role }}</td>
                                            <td class="text-center">{{ handlePrice($package->price) }}</td>
                                            <td class="text-center">{{ $package->days }}</td>
                                            <td class="text-center">{{ $package->sales->count() }}</td>
                                            <td class="text-center">
                                            <span class="badge-status {{ ($package->status == 'active') ? 'text-success bg-success-30' : 'text-danger bg-danger-30' }}">{{ trans('admin/main.'.$package->status) }}</span>
                                            </td>
                                            <td class="text-center">{{ dateTimeFormat($package->created_at, 'Y M j | H:i') }}</td>
                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_registration_packages_edit')
                <a href="{{ getAdminPanelUrl() }}/financial/registration-packages/{{ $package->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_registration_packages_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/financial/registration-packages/'.$package->id.'/delete',
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans("admin/main.delete"),
                    'btnIcon' => 'trash',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2',
                ])
            @endcan
        </div>
    </div>
</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $packages->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.registration_packages_list_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.registration_packages_list_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.registration_packages_list_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.registration_packages_list_hint_description_2')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

