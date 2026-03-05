@extends('design_1.panel.layouts.panel')

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.marketing.affiliates.top_stats')

    <div class="row">
        <div class="col-12 col-lg-4 mt-20">
            <div class="bg-white p-16 rounded-24">
                <h3 class="font-16 font-weight-bold pb-16 border-bottom-gray-100">{{ trans('panel.affiliate_summary') }}</h3>

                <div class="d-flex-center mt-32">
                    <div class="affiliate-summary-img">
                        <img src="/assets/design_1/img/panel/affiliate/affiliate_summary.svg" alt="affiliate_summary" class="img-cover">
                    </div>
                </div>

                @if(!empty($referralSettings))
                    @if(!empty($referralSettings['affiliate_user_amount']))
                        <div class="d-flex align-items-center mt-16">
                            <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                                <x-iconsax-lin-money-recive class="icons text-primary" width="24px" height="24px"/>
                            </div>
                            <div class="ml-8">
                                <h5 class="font-16 font-weight-bold">{{ handlePrice($referralSettings['affiliate_user_amount']) }}</h5>
                                <p class="mt-2 font-12 text-gray-500">{{ trans('update.your_referral_user_registration_amount') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(!empty($referralSettings['referred_user_amount']))
                        <div class="d-flex align-items-center mt-16">
                            <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                                <x-iconsax-lin-profile-add class="icons text-primary" width="24px" height="24px"/>
                            </div>
                            <div class="ml-8">
                                <h5 class="font-16 font-weight-bold">{{ handlePrice($referralSettings['referred_user_amount']) }}</h5>
                                <p class="mt-2 font-12 text-gray-500">{{ trans('update.referred_user_registration_amount') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(!empty($referralSettings['affiliate_user_commission']))
                        <div class="d-flex align-items-center mt-16">
                            <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                                <x-iconsax-lin-empty-wallet class="icons text-primary" width="24px" height="24px"/>
                            </div>
                            <div class="ml-8">
                                <h5 class="font-16 font-weight-bold">{{ $referralSettings['affiliate_user_commission'] }}%</h5>
                                <p class="mt-2 font-12 text-gray-500">{{ trans('update.purchase_commission') }}</p>
                            </div>
                        </div>
                    @endif

                @endif

            </div>
        </div>

        {{-- Your Affiliate Information --}}
        <div class="col-12 col-lg-4 mt-20">
            <div class="bg-white p-16 rounded-24">
                <h3 class="font-16 font-weight-bold pb-16 border-bottom-gray-100">{{ trans('update.your_affiliate_information') }}</h3>

                <div class="d-flex-center flex-column text-center mt-32">
                    <div class="affiliate-summary-img">
                        <img src="/assets/design_1/img/panel/affiliate/your_affiliate_information.svg" alt="your_affiliate_information" class="img-cover">
                    </div>

                    <div class="d-flex-center mt-16 p-8 rounded-32 bg-gray-100 form-group mb-0">
                        <span class="font-16 font-weight-bold mr-8">{{ $affiliateCode->code }}</span>

                        <button type="button" class="js-copy-input btn-transparent d-flex" data-tippy-content="{{ trans('public.copy') }}" data-copy-text="{{ trans('public.copy') }}" data-done-text="{{ trans('public.copied') }}">
                            <x-iconsax-lin-document-copy class="icons text-gray-400" width="20px" height="20px"/>
                        </button>
                        <input type="hidden" class="form-control" value="{{ $affiliateCode->code }}">
                    </div>

                    <h4 class="mt-12 font-14 font-weight-bold">{{ trans('panel.your_affiliate_code') }}</h4>

                    @if(!empty($referralSettings['referral_description']))
                        <p class="mt-8 font-12 text-gray-500 text-center">{{ $referralSettings['referral_description'] }}</p>
                    @endif
                </div>

                <div class="form-group mb-0 mt-28">
                    <label class="form-group-label">{{ trans('panel.affiliate_url') }}</label>

                    <button type="button" class="js-copy-input has-translation btn-transparent d-flex" data-tippy-content="{{ trans('public.copy') }}" data-copy-text="{{ trans('public.copy') }}" data-done-text="{{ trans('public.copied') }}">
                        <x-iconsax-lin-document-copy class="icons text-gray-400" width="24px" height="24px"/>
                    </button>

                    <input type="text" name="affiliate_url" readonly value="{{ $affiliateCode->getAffiliateUrl() }}" class="form-control"/>
                </div>
            </div>
        </div>


        {{-- How it works? --}}
        <div class="col-12 col-lg-4 mt-20">
            <div class="bg-white p-16 rounded-24">
                <h3 class="font-16 font-weight-bold pb-16 border-bottom-gray-100">{{ trans('update.how_it_works') }}</h3>

                @if(!empty($referralHowWorkSettings))
                    <div class="d-flex-center flex-column text-center mt-32">
                        @if(!empty($referralHowWorkSettings['image']))
                            <div class="affiliate-summary-img">
                                <img src="{{ $referralHowWorkSettings['image'] }}" alt="how_it_works" class="img-cover">
                            </div>
                        @endif
                    </div>

                    @if(!empty($referralHowWorkSettings['description']))
                        <p class="mt-16 font-14 text-gray-500 white-space-pre-wrap">{{ $referralHowWorkSettings['description'] }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>


    {{-- List Table --}}
    @if(!empty($affiliates) and !$affiliates->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-24">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('panel.referred_users') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_and_manage_referred_users_related_statistics') }}</p>
                </div>
            </div>

            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/marketing/affiliates">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('panel.user') }}</th>
                        <th class="text-center">{{ trans('panel.registration_bonus') }}</th>
                        <th class="text-center">{{ trans('panel.affiliate_bonus') }}</th>
                        <th class="text-center">{{ trans('panel.registration_date') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($affiliates as $affiliateRow)
                        @include('design_1.panel.marketing.affiliates.table_items', ['affiliate' => $affiliateRow])
                    @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'affiliate.svg',
            'title' => trans('update.your_affiliate_list_is_empty'),
            'hint' => nl2br(trans('update.when_a_user_Referred_by_you_it_will_be_appeared_here')),
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
