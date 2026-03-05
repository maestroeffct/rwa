@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
@endpush


@section('content')
    @php
        $registrationBonusTermsSettings = getRegistrationBonusTermsSettings();
        $registrationBonusSettings = getRegistrationBonusSettings();
        $checkReferralUserCount = (!empty($registrationBonusSettings['unlock_registration_bonus_with_referral']) and !empty($registrationBonusSettings['number_of_referred_users']));
        $purchaseAmountCount = (!empty($registrationBonusSettings['enable_referred_users_purchase']));
    @endphp

    {{-- Top Stats --}}
    @include('design_1.panel.marketing.registration_bonus.top_stats')

    <div class="row">

        @if($checkReferralUserCount or $purchaseAmountCount)
            <div class=" mt-20 {{ (!empty($registrationBonusTermsSettings) and !empty($registrationBonusTermsSettings['items'])) ? 'col-12 col-lg-6' : 'col-12' }}">
                <div class="bg-white p-16 rounded-24">
                    <h3 class="font-16 font-weight-bold">{{ trans('update.bonus_status') }}</h3>

                    <div class="row">
                        <div class="col-12 col-md-5 mt-16 d-flex-center">
                            <img src="/assets/design_1/img/panel/registration_bonus/bonus_status.svg" alt="bonus_status" class="img-fluid" height="207px">
                        </div>

                        <div class="col-12 col-md-7 mt-16">

                            @if(!empty($registrationBonusSettings['number_of_referred_users']))
                                <div class="position-relative d-flex align-items-center bg-gray-100 p-20 rounded-16">
                                    <div class="d-flex-center size-84 rounded-circle bg-white p-8">
                                        <canvas id="bonusStatusReferredUsersChart" height="68px" width="68px"></canvas>
                                    </div>

                                    <div class="ml-20">
                                        <span class="d-block font-14 font-weight-bold">{{ trans('update.referred_users') }}</span>
                                        <span class="d-block font-12 text-gray-500 mt-8">{{ $bonusStatusReferredUsersChart['complete'] == 0 ? trans('update.you_havent_referred_any_users') : trans('update.you_referred_count_users_to_the_platform',['count' => "{$bonusStatusReferredUsersChart['referred_users']}/{$registrationBonusSettings['number_of_referred_users']}"]) }}</span>
                                    </div>

                                    @if($bonusStatusReferredUsersChart['complete'] == 100)
                                        <div class="bonus-status-complete-check">
                                            <x-tick-icon class="icons text-white" width="12px" height="12px"/>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($purchaseAmountCount)
                                <div class="position-relative d-flex align-items-center bg-gray-100 p-20 rounded-16 mt-16">
                                    <div class="d-flex-center size-84 rounded-circle bg-white p-8">
                                        <canvas id="bonusStatusUsersPurchasesChart" height="68px" width="68px"></canvas>
                                    </div>

                                    <div class="ml-20">
                                        <span class="d-block font-14 font-weight-bold">{{ trans('update.users_purchases') }}</span>
                                        <span class="d-block font-12 text-gray-500 mt-8">{{ $bonusStatusUsersPurchasesChart['complete'] == 0 ? trans('update.you_havent_referred_any_users_to_purchase') : trans('update.count_users_achieved_purchase_target',['count' => "{$bonusStatusUsersPurchasesChart['reached_user_purchased']}/{$bonusStatusUsersPurchasesChart['total_user_purchased']}"]) }}</span>
                                    </div>

                                    @if($bonusStatusUsersPurchasesChart['complete'] == 100)
                                        <div class="bonus-status-complete-check">
                                            <x-tick-icon class="icons text-white" width="12px" height="12px"/>
                                        </div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($registrationBonusTermsSettings) and !empty($registrationBonusTermsSettings['items']))
            <div class=" mt-20 {{ (!empty($registrationBonusSettings['number_of_referred_users']) or !empty($registrationBonusSettings['purchase_amount_for_unlocking_bonus'])) ? 'col-12 col-lg-6' : 'col-12' }}">
                <div class="bg-white p-16 rounded-24">
                    <h3 class="font-16 font-weight-bold">{{ trans('update.how_to_get_bonus') }}</h3>

                    <div class="row">
                        <div class="col-12 col-md-7 mt-16">

                            @foreach($registrationBonusTermsSettings['items'] as $termItem)
                                @if(!empty($termItem['icon']) and !empty($termItem['title']) and !empty($termItem['description']))
                                    <div class="how-to-get-bonus-items d-flex align-items-start">
                                        <div class="size-40 d-flex-center">
                                            <img src="{{ $termItem['icon'] }}" alt="{{ $termItem['title'] }}" class="img-fluid">
                                        </div>

                                        <div class="ml-12">
                                            <span class="d-block font-14 font-weight-bold">{{ $termItem['title'] }}</span>
                                            <span class="d-block font-12 font-weight-500 text-gray-500 mt-4">{{ $termItem['description'] }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>

                        @if(!empty($registrationBonusTermsSettings['term_image']))
                            <div class="col-12 col-md-5 mt-16 d-flex-center">
                                <img src="{{ $registrationBonusTermsSettings['term_image'] }}" alt="bonus_status" class="img-fluid" height="207px">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>


    {{-- List Table --}}
    <div class="bg-white pt-16 rounded-24 mt-24">
        <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
            <div class="">
                <h3 class="font-16">{{ trans('update.referral_history') }}</h3>
                <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_and_manage_referred_users_related_statistics') }}</p>
            </div>
        </div>

        @if(!empty($referredUsers) and !$referredUsers->isEmpty())
            <div class="table-responsive-lg">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('panel.user') }}</th>

                        @if($purchaseAmountCount)
                            <th class="text-center">{{ trans('update.purchase_status') }}</th>
                        @endif

                        <th class="text-center">{{ trans('panel.registration_date') }}</th>
                    </tr>
                    </thead>
                    <tbody class="">

                    @foreach($referredUsers as $user)
                        <tr>
                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <div class="size-40 rounded-circle bg-gray-200">
                                        <img src="{{ $user->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="{{ $user->full_name }}">
                                    </div>

                                    <div class=" ml-8">
                                        <span class="d-block">{{ $user->full_name }}</span>
                                    </div>
                                </div>
                            </td>

                            @if($purchaseAmountCount)
                                <td class="text-center">
                                    @if((!empty($registrationBonusSettings['purchase_amount_for_unlocking_bonus']) and $user->totalPurchase >= $registrationBonusSettings['purchase_amount_for_unlocking_bonus']) or (empty($registrationBonusSettings['purchase_amount_for_unlocking_bonus']) and $user->totalPurchase > 0))
                                        <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-success-20 text-success">{{ trans('update.reached') }}</span>
                                    @else
                                        <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-danger-20 text-danger">{{ trans('update.not_reached') }}</span>
                                    @endif
                                </td>
                            @endif

                            <td class="text-center">{{ dateTimeFormat($user->created_at, 'Y M j | H:i') }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                {{-- Pagination --}}

            </div>

        @else
            <div class="d-flex-center mt-80 mb-40">
                @include('design_1.panel.includes.no-result',[
                    'file_name' => 'upcoming_no_followers.svg',
                    'title' => trans('update.no_referred_users'),
                    'hint' => nl2br(trans('update.you_havent_referred_any_users_yet')),
                ])
            </div>
        @endif
    </div>
@endsection


@push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/design_1/js/panel/registration_bonus.min.js"></script>

    <script>
        (function ($) {
            "use strict";

            @if(!empty($bonusStatusReferredUsersChart))
            makePieChart('bonusStatusReferredUsersChart', @json($bonusStatusReferredUsersChart['labels']), Number({{ $bonusStatusReferredUsersChart['complete'] }}));
            @endif

            @if(!empty($bonusStatusUsersPurchasesChart))
            makePieChart('bonusStatusUsersPurchasesChart', @json($bonusStatusUsersPurchasesChart['labels']), Number({{ $bonusStatusUsersPurchasesChart['complete'] }}));
            @endif
        })()
    </script>
@endpush
