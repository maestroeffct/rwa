<div class="bg-white p-16 rounded-24">
    <div class="row">
        <div class="col-12 col-lg-6">
            <h5 class="font-16 font-weight-bold">{{ trans('update.ready_to_payout') }}</h5>
            <h3 class="font-44 font-weight-bold mt-12">{{ handlePrice($readyPayout ?? 0) }}</h3>

            <ul class="mt-8">
                <li class="text-gray-500 mb-6">{{ trans('update.payout_condition_1') }}</li>
                <li class="text-gray-500 mb-6">{{ trans('update.payout_condition_2') }}</li>
                <li class="text-gray-500 mb-6">{{ trans('update.payout_condition_3') }}</li>
            </ul>

            <div class="d-flex align-items-center mt-12">
                <button type="button" class="js-request-payout-modal btn btn-primary flex-1" {{ (!$authUser->financial_approval) ? 'disabled' : '' }}>
                    {{ trans('financial.request_payout') }}
                </button>

                <a href="/panel/setting/step/financial" class="btn bg-gray-100 text-gray-500 ml-8 flex-1">{{ trans('update.payout_information') }}</a>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-16 mt-lg-0">
            @if(!empty($selectedBank) and $authUser->financial_approval)
                <div class="position-relative">
                    <div class="payout-bank-card-mask"></div>
                    <div class="payout-bank-card d-flex flex-column w-100 z-index-2">
                        <img src="/assets/design_1/img/panel/payout/circle-left-top.svg" alt="" class="circle-left-top">
                        <img src="/assets/design_1/img/panel/payout/circle-bottom-right.svg" alt="" class="circle-bottom-right mr-24">
                        <img src="/assets/design_1/img/panel/payout/logo_mask.svg" alt="" class="logo-mask-right">

                        <div class="first-rectangle"></div>
                        <div class="second-rectangle"></div>

                        <div class="d-flex align-items-center justify-content-between mb-16">
                            <div class="">
                                <span class="d-block font-16 font-weight-bold text-white">{{ $selectedBank->bank->title }}</span>
                                <span class="d-block font-12 text-white">{{ $selectedBank->payouts->where('status', 'done')->count() }} {{ trans('admin/main.payouts') }}</span>
                            </div>

                            <div class="position-relative z-index-3 d-flex-center size-48 rounded-12 bg-white">
                                <img src="{{ $selectedBank->bank->logo }}" alt="" class="img-fluid">
                            </div>
                        </div>

                        <div class="mt-auto w-100">
                            @if(!empty($selectedBank) and !empty($selectedBank->bank))
                                @foreach($selectedBank->bank->specifications as $specification)
                                    @php
                                        $selectedBankSpecification = $selectedBank->specifications->where('user_selected_bank_id', $selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                                    @endphp

                                    @if(!empty($selectedBankSpecification) and !empty($selectedBankSpecification->value))
                                        <div class="d-flex align-items-center justify-content-between font-12 text-white w-100 mt-12">
                                            <span class="">{{ $specification->name }}</span>
                                            <span class="">{{ $selectedBankSpecification->value }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                    </div>
                </div>
            @else
                <div class="d-flex-center flex-column text-center p-32 w-100 h-100 rounded-16 bg-gray-100 soft-shadow-2 border-dashed border-{{ empty($selectedBank) ? 'primary' : 'warning' }}">
                    <div class="d-flex-center size-44 bg-{{ empty($selectedBank) ? 'primary' : 'warning' }}-20 rounded-circle">
                        <x-iconsax-lin-wallet-minus class="icons text-{{ empty($selectedBank) ? 'primary' : 'warning' }}" width="24px" height="24px"/>
                    </div>
                    <h4 class="font-14 mt-8 text-{{ empty($selectedBank) ? 'primary' : 'warning' }}">{{ trans('update.payout_account') }}</h4>

                    @if(empty($selectedBank))
                        <p class="font-12 mt-4 text-primary">{{ trans('update.define_your_payout_information_to_get_payout') }}</p>
                    @else
                        <p class="font-12 mt-4 text-warning">{{ trans('update.your_payout_information_is_awaiting_admin_approval') }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
