@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{trans('admin/main.affiliate_users')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('admin/main.affiliate_users')}}</div>
            </div>
        </div>

        <div class="section-body">


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">


                        <div class="card-header justify-content-between">
                             <div>
                                <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                            </div>
                            
                             <div class="d-flex align-items-center gap-12">

                             @can('admin_referrals_export')
                                <div class="d-flex align-items-center gap-12">
                                    <a href="{{ getAdminPanelUrl() }}/referrals/excel?type=users" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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
                                        <th>{{ trans('admin/main.user') }}</th>
                                        <th>{{ trans('admin/main.role') }}</th>
                                        <th>{{ trans('admin/main.user_group') }}</th>
                                        <th>{{ trans('admin/main.referral_code') }}</th>
                                        <th>{{ trans('admin/main.registration_income') }}</th>
                                        <th>{{ trans('admin/main.aff_sales_commission') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
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
                                                @if(!empty($affiliate->affiliateUser))
                                                    @if($affiliate->affiliateUser->isUser())
                                                        {{ trans('quiz.student') }}
                                                    @elseif($affiliate->affiliateUser->isTeacher())
                                                        {{ trans('panel.teacher') }}
                                                    @elseif($affiliate->affiliateUser->isOrganization())
                                                        {{ trans('home.organization') }}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($affiliate->affiliateUser))
                                                    {{  !empty($affiliate->affiliateUser->getUserGroup()) ? $affiliate->affiliateUser->getUserGroup()->name : '-'  }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($affiliate->affiliateUser))
                                                    {{ !empty($affiliate->affiliateUser->affiliateCode) ? $affiliate->affiliateUser->affiliateCode->code : '' }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                {{ handlePrice($affiliate->getTotalAffiliateRegistrationAmounts()) }}
                                            </td>

                                            <td>
                                                {{ handlePrice($affiliate->getTotalAffiliateCommissions()) }}
                                            </td>

                                            <td>
                                                @if(!empty($affiliate->affiliateUser))
                                                    {{ $affiliate->affiliateUser->affiliate ? trans('admin/main.yes') : trans('admin/main.no') }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
    @if(!empty($affiliate->affiliateUser))
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ getAdminPanelUrl() }}/users/{{ $affiliate->affiliateUser->id }}/edit" class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4">
                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
            </a>
        </div>
    </div>
    @endif
</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{--{{ $affiliates->appends(request()->input())->links() }}--}}
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
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.registration_income_hint')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.registration_income_desc')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.aff_sales_commission_hint')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.aff_sales_commission_desc')}}</div>
                    </div>
                </div>


            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')

@endpush
