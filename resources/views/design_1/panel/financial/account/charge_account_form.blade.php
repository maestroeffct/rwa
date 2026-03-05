@php
    $showOfflineFields = false;
    if ($errors->has('date') or $errors->has('referral_code') or $errors->has('account') or !empty($editOfflinePayment)) {
        $showOfflineFields = true;
    }

    $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));
    $userCurrency = currency();
    $invalidChannels = [];
@endphp

<form action="/panel/financial/{{ !empty($editOfflinePayment) ? 'offline-payments/'. $editOfflinePayment->id .'/update' : 'charge' }}" method="post" enctype="multipart/form-data" class="mt-25">
    {{csrf_field()}}

    <div class="bg-white pt-16 rounded-24 mt-20">
        <div class="px-16 pb-16 border-bottom-gray-200">
            <h3 class="font-16 font-weight-bold">{{ trans('update.charge_your_account') }}</h3>
            <p class="text-gray-500">{{ trans('update.deposit_amount_to_your_account_and_use_it_for_purchases') }}</p>
        </div>

        <h4 class="font-16 px-16 font-weight-bold mt-16">{{ trans('update.select_a_payment_method') }}</h4>


        @if($errors->has('gateway'))
            <div class="text-danger mb-16">{{ $errors->first('gateway') }}</div>
        @endif

        <div class="row px-16">
            @foreach($paymentChannels as $paymentChannel)
                @if(!$isMultiCurrency or (!empty($paymentChannel->currencies) and in_array($userCurrency, $paymentChannel->currencies)))
                    <div class="col-6 col-md-3 mt-16">
                        <div class="custom-input-button position-relative">
                            <div class="payment-channel-mask"></div>
                            <input type="radio" class="online-gateway" name="gateway" id="gateway_{{ $paymentChannel->class_name }}" @if(old('gateway') == $paymentChannel->class_name) checked @endif value="{{ $paymentChannel->class_name }}">
                            <label for="gateway_{{ $paymentChannel->class_name }}" class="position-relative d-flex-center flex-column p-16 rounded-16 text-center z-index-2 bg-white">
                                <div class="d-flex-center size-48 bg-gray-100 rounded-4">
                                    <img src="{{ $paymentChannel->image }}" alt="" class="img-fluid">
                                </div>

                                <div class="mt-12 font-14 font-weight-bold">{{ $paymentChannel->title }}</div>
                            </label>
                        </div>
                    </div>
                @else
                    @php
                        $invalidChannels[] = $paymentChannel;
                    @endphp
                @endif
            @endforeach

            @if(!empty(getOfflineBankSettings('offline_banks_status')))
                <div class="col-6 col-md-3 mt-16">
                    <div class="custom-input-button position-relative">
                        <div class="payment-channel-mask"></div>
                        <input type="radio" class="" name="gateway" id="gateway_offline" value="offline" @if(old('gateway') == 'offline' or !empty($editOfflinePayment)) checked @endif>
                        <label for="gateway_offline" class="position-relative d-flex-center flex-column p-16 rounded-16 text-center z-index-2 bg-white">
                            <div class="d-flex-center size-48 bg-gray-100 rounded-4">
                                <x-iconsax-bul-convert-card class="icons" width="36px" height="36px"/>
                            </div>

                            <div class="mt-12 font-14 font-weight-bold">{{ trans('financial.offline') }}</div>
                        </label>
                    </div>
                </div>
            @endif
        </div>

        @if(!empty($invalidChannels))
            <div class="d-flex align-items-center p-12 rounded-12 bg-gray-500-20 mt-24 mx-16">
                <div class="d-flex-center size-48 rounded-12 bg-gray-500">
                    <x-iconsax-bol-info-circle class="icons text-white" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h4 class="font-14 font-weight-bold text-gray-500">{{ trans('update.disabled_payment_gateways') }}</h4>
                    <p class="font-12 text-gray-500">{{ trans('update.disabled_payment_gateways_hint') }}</p>
                </div>
            </div>

            <div class="row mx-12">
                @foreach($invalidChannels as $invalidChannel)
                    <div class="col-6 col-lg-3 mt-16">
                        <div class="d-flex align-items-center p-16 rounded-16 border-gray-200">
                            <div class="d-flex-center size-48 bg-gray-100 rounded-4">
                                <img src="{{ $invalidChannel->image }}" alt="" class="img-fluid">
                            </div>

                            <div class="ml-16 font-14 font-weight-bold text-gray-500">{{ $invalidChannel->title }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-24 js-offline-payment-input {{ (!$showOfflineFields) ? 'd-none' : '' }}">
            <h4 class="font-16 px-16 pt-16 pb-16 font-weight-bold">{{ trans('update.offline_payment_accounts') }}</h4>

            <div class="row px-16">
                @foreach($offlineBanks as $offlineBank)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="rounded-16 border-gray-200 p-16 h-100 w-100">
                            <div class="d-flex-center flex-column text-center">
                                <div class="d-flex-center size-48 bg-gray-100 rounded-8">
                                    <img src="{{ $offlineBank->logo }}" class="img-fluid">
                                </div>
                                <h5 class="mt-12 font-14 font-weight-bold">{{ $offlineBank->title }}</h5>
                            </div>

                            @foreach($offlineBank->specifications as $specification)
                                <div class="d-flex align-items-center justify-content-between {{ $loop->first ? 'mt-8' : 'mt-12' }}">
                                    <span class="font-12 text-gray-500">{{ $specification->name }}:</span>
                                    <span class="font-12 text-dark">{{ $specification->value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="px-16 mt-24">
            <h4 class="font-16 font-weight-bold">{{ trans('update.charge_amount') }}</h4>

            <div class="row mt-24">
                <div class="col-12 col-md-4 ">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('panel.amount') }}</label>
                        <span class="has-translation bg-gray-100 text-gray-500">{{ $currency }}</span>
                        <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ !empty($editOfflinePayment) ? $editOfflinePayment->amount : old('amount') }}" oninput="validatePrice(this)">
                        <div class="invalid-feedback d-block">@error('amount') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="col-12 col-md-4 js-offline-payment-input {{ (!$showOfflineFields) ? 'd-none' : '' }}">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('financial.account') }}</label>
                        <select name="account" class="form-control @error('account') is-invalid @enderror">
                            <option selected disabled>{{ trans('financial.select_the_account') }}</option>

                            @foreach($offlineBanks as $offlineBank)
                                <option value="{{ $offlineBank->id }}" @if(!empty($editOfflinePayment) and $editOfflinePayment->offline_bank_id == $offlineBank->id) selected @endif>{{ $offlineBank->title }}</option>
                            @endforeach
                        </select>

                        @error('account')
                        <div class="invalid-feedback"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-4 js-offline-payment-input {{ (!$showOfflineFields) ? 'd-none' : '' }}">
                    <div class="form-group">
                        <label for="referralCode" class="form-group-label">{{ trans('admin/main.referral_code') }}</label>
                        <input type="text" name="referral_code" id="referralCode" class="form-control @error('referral_code') is-invalid @enderror" value="{{ !empty($editOfflinePayment) ? $editOfflinePayment->reference_number : old('referral_code') }}"/>
                        @error('referral_code')
                        <div class="invalid-feedback"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-4 js-offline-payment-input {{ (!$showOfflineFields) ? 'd-none' : '' }}">
                    <div class="form-group">
                        <label for="dateInput" class="form-group-label">{{ trans('public.date_time') }}</label>
                        <input type="text" name="date" id="dateInput" class="form-control datetimepicker js-default-init-date-picker @error('date') is-invalid @enderror" value="{{ !empty($editOfflinePayment) ? dateTimeFormat($editOfflinePayment->pay_date, 'Y-m-d H:i', false) : old('date') }}" data-format="YYYY/MM/DD HH:mm" autocomplete="off"/>
                        @error('date')
                        <div class="invalid-feedback"> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-4 js-offline-payment-input {{ (!$showOfflineFields) ? 'd-none' : '' }}">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.attach_the_payment_photo') }}</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="attachment" class="js-ajax-upload-file-input js-ajax-attachment custom-file-input" data-upload-name="attachment" id="attachmentInput" accept="image/*">
                            <span class="custom-file-text"></span>
                            <label class="custom-file-label" for="attachmentInput">{{ trans('update.browse') }}</label>
                        </div>

                        @error('attachment')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <button type="button" id="submitChargeAccountForm" class="btn btn-primary btn-lg btn-block">{{ trans('public.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
