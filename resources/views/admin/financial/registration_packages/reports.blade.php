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
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('update.total_buy_instructors_packages')}}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalBuyInstructorsPackages }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('update.total_buy_organization_packages')}}</span>
                            <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                <x-iconsax-bul-courthouse class="icons text-accent" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalBuyOrganizationPackages }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('financial.total_amount')}}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-dollar-square class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ handlePrice($sales->sum('total_amount')) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.total_sales')}}</span>
                            <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                <x-iconsax-bul-bag class="icons text-secondary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $sales->count() }}</h5>
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
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        <th class="text-center">{{ trans('public.user_role') }}</th>
                                        <th class="text-center">{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('public.days') }}</th>
                                        <th class="text-center">{{ trans('admin/main.price') }}</th>
                                        <th class="text-center">{{ trans('update.activation_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.ext_date') }}</th>
                                    </tr>

                                    @foreach($sales as $sale)
                                        <tr>
                                            <td class="text-left">{{ !empty($sale->buyer) ? $sale->buyer->full_name : '' }}</td>
                                            <td class="text-center">{{ !empty($sale->buyer) ? $sale->buyer->role_name : '' }}</td>
                                            <td class="text-center">{{ !empty($sale->registrationPackage) ? $sale->registrationPackage->title : '' }}</td>
                                            <td class="text-center">{{ !empty($sale->registrationPackage) ? $sale->registrationPackage->days : '' }}</td>
                                            <td class="text-center">{{ !empty($sale->registrationPackage) ? handlePrice($sale->registrationPackage->price) : '' }}</td>
                                            <td class="text-center">{{ dateTimeFormat($sale->created_at, 'Y M j | H:i') }}</td>
                                            <td class="text-center">{{ !empty($sale->registrationPackage) ? dateTimeFormat(($sale->created_at + ($sale->registrationPackage->days * 24 * 60 *60)) , 'Y M j | H:i') : '' }}</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $sales->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

