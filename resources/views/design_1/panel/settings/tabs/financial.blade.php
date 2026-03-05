<div class="custom-tabs-content active">
    @if($user->financial_approval)
        <div class="d-flex align-items-center bg-primary-20 text-primary p-12 rounded-12">
            <div class="d-flex-center size-48 bg-primary rounded-12">
                <x-iconsax-bol-info-circle class="icons text-white" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <span class="d-block font-14 font-weight-bold">{{ trans('update.your_identity_verified') }}</span>
                <span class="d-block font-12 mt-4">{{ trans('site.identity_and_financial_verified') }}</span>
            </div>
        </div>
    @else
        <div class="d-flex align-items-center bg-warning-20 text-warning p-12 rounded-12">
            <div class="d-flex-center size-48 bg-warning rounded-12">
                <x-iconsax-bol-more-circle class="icons text-white" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <span class="d-block font-14 font-weight-bold">{{ trans('update.identity_approval') }}</span>
                <span class="d-block font-12 mt-4">{{ trans('site.identity_and_financial_not_verified') }}</span>
            </div>
        </div>
    @endif


    <div class="row">
        <div class="col-12 col-lg-4 mt-20">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.payout_account') }}</h3>


                <div class="form-group ">
                    <label class="form-group-label">{{ trans('financial.select_account_type') }}</label>
                    <select name="bank_id" class="js-user-bank-input form-control select2 @error('bank_id')  is-invalid @enderror" {{ ($user->financial_approval) ? 'disabled' : '' }}>
                        <option selected disabled>{{ trans('financial.select_account_type') }}</option>

                        @foreach($userBanks as $userBank)
                            <option value="{{ $userBank->id }}" @if(!empty($user->selectedBank) and $user->selectedBank->user_bank_id == $userBank->id) selected="selected" @endif data-specifications="{{ json_encode($userBank->specifications->pluck('name','id')->toArray()) }}">{{ $userBank->title }}</option>
                        @endforeach
                    </select>

                    @error('bank_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="js-bank-specifications-card">
                    @if(!empty($user) and !empty($user->selectedBank) and !empty($user->selectedBank->bank))
                        @foreach($user->selectedBank->bank->specifications as $specification)
                            @php
                                $selectedBankSpecification = $user->selectedBank->specifications->where('user_selected_bank_id', $user->selectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                            @endphp

                            <div class="form-group">
                                <label class="form-group-label">{{ $specification->name }}</label>
                                <input type="text" name="bank_specifications[{{ $specification->id }}]" value="{{ (!empty($selectedBankSpecification)) ? $selectedBankSpecification->value : '' }}" class="form-control" {{ ($user->financial_approval) ? 'disabled' : '' }}/>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>

        <div class="col-12 col-lg-8 mt-20">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.identity_documents') }}</h3>


                <div class="position-relative custom-input-file">
                    <label for="identity_scan" class="w-100 h-100 rounded-15 d-flex-center flex-column cursor-pointer p-28 border-gray-400 border-dashed">
                        <div class="d-flex-center size-44 rounded-circle {{ ($user->financial_approval) ? 'bg-gray-400-20' : 'bg-primary-20' }}">
                            <x-iconsax-lin-direct-send class="icons {{ ($user->financial_approval) ? 'text-gray-400' : 'text-primary' }}" width="24" height="24"/>
                        </div>

                        <div class="js-file-name-span mt-8 font-12 {{ ($user->financial_approval) ? 'text-gray-400' : 'text-primary' }}">{{ trans('financial.identity_scan') }}</div>
                    </label>

                    <input type="file" name="identity_scan" id="identity_scan" class="custom-file-input" {{ ($user->financial_approval) ? 'disabled' : '' }}>
                </div>


                <div class="position-relative custom-input-file mt-16">
                    <label for="certificate" class="w-100 h-100 rounded-15 d-flex-center flex-column cursor-pointer p-28 border-gray-400 border-dashed">
                        <div class="d-flex-center size-44 rounded-circle {{ ($user->financial_approval) ? 'bg-gray-400-20' : 'bg-primary-20' }}">
                            <x-iconsax-lin-direct-send class="icons {{ ($user->financial_approval) ? 'text-gray-400' : 'text-primary' }}" width="24" height="24"/>
                        </div>

                        <div class="js-file-name-span mt-8 font-12 {{ ($user->financial_approval) ? 'text-gray-400' : 'text-primary' }}">{{ trans('public.certificate_and_documents') }}</div>
                    </label>

                    <input type="file" name="certificate" id="certificate" class="custom-file-input" {{ ($user->financial_approval) ? 'disabled' : '' }}>
                </div>


            </div>
        </div>
    </div>
</div>


