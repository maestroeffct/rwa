@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{trans('admin/main.referral_history')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('admin/main.referral_history')}}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.total')}} {{ trans('admin/main.referred_users') }}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-hierarchy-square-2 class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $affiliatesCount }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.total')}} {{ trans('admin/main.affiliate_users') }}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-people class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $affiliateUsersCount }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.total')}} {{ trans('admin/main.registeration_amount') }}</span>
                                <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                    <x-iconsax-bul-dollar-square class="icons text-accent" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ handlePrice($allAffiliateAmounts) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.total_commission_amount')}}</span>
                                <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                    <x-iconsax-bul-percentage-square class="icons text-secondary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ handlePrice($allAffiliateCommissionAmounts) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card mt-32">

                        <div class="card-header justify-content-between">
                             <div>
                                <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                            </div>
                            
                             <div class="d-flex align-items-center gap-12">

                             @can('admin_referrals_export')
                                <div class="d-flex align-items-center gap-12">
                                    <a href="{{ getAdminPanelUrl() }}/referrals/excel?type=history" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                    </a>
                                </div>
                            @endcan

                             </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th>{{ trans('admin/main.affiliate_user') }}</th>
                                        <th>{{ trans('admin/main.referred_user') }}</th>
                                        <th>{{ trans('admin/main.affiliate_registration_amount') }}</th>
                                        <th>{{ trans('admin/main.affiliate_user_commission') }}</th>
                                        <th>{{ trans('admin/main.referred_user_amount') }}</th>
                                        <th>{{ trans('admin/main.date') }}</th>
                                    </tr>

                                    <tbody>
                                    @foreach($affiliates as $affiliate)
                                        <tr>
                                            <td>
                                                @if(!empty($affiliate->affiliateUser))
                                                    {{ $affiliate->affiliateUser->full_name }}
                                                @else
                                                    {{ trans('update.deleted_user') }}
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($affiliate->referredUser))
                                                    {{ $affiliate->referredUser->full_name }}
                                                @else
                                                    {{ trans('update.deleted_user') }}
                                                @endif
                                            </td>

                                            <td>
                                                {{ handlePrice($affiliate->getAffiliateRegistrationAmountsOfEachReferral()) }}
                                            </td>

                                            <td>
                                                {{ handlePrice($affiliate->getTotalAffiliateCommissionOfEachReferral()) }}
                                            </td>

                                            <td>
                                                {{ handlePrice($affiliate->getReferredAmount()) }}
                                            </td>

                                            <td>
                                                {{ dateTimeFormat($affiliate->created_at, 'Y M j | H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $affiliates->appends(request()->input())->links() }}
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
                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.total_user_hint')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.total_user_desc')}}</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.total_affiliate_hint')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.total_affiliate_desc')}}</div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.total_aff_amount_hint')}}</div>
                        <div class="text-small font-600-bold">{{trans('admin/main.total_aff_amount_desc')}}</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.total_aff_commission_hint')}}</div>
                        <div class="text-small font-600-bold">{{trans('admin/main.total_aff_commission_desc')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')

@endpush
