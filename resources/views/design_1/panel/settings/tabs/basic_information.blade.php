<div class="custom-tabs-content active">
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.account_&_security') }}</h3>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('auth.name') }}</label>
                    <input type="text" name="full_name" value="{{ (!empty($user) and empty($new_user)) ? $user->full_name : old('full_name') }}" class="form-control @error('full_name')  is-invalid @enderror" placeholder=""/>
                    @error('full_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('public.email') }}</label>
                    <input type="text" name="email" value="{{ (!empty($user) and empty($new_user)) ? $user->email : old('email') }}" class="form-control @error('email')  is-invalid @enderror" placeholder=""/>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="register-mobile-form-group position-relative bg-white @error('mobile') is-invalid @enderror">
                        <label class="form-group-label">{{ trans('public.phone') }}</label>

                        <div class="row">
                            <div class="col-4 h-100 pr-0">
                                <select name="country_code" class="form-control country-code-select2">
                                    @foreach(getCountriesMobileCode() as $country => $code)
                                        <option value="{{ $code }}" @if($code == old('country_code')) selected @endif>{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-8 h-100 pl-4">
                                <input type="tel" name="mobile" class="register-mobile-form-group__input bg-white" value="{{ (!empty($user) and empty($new_user)) ? $user->mobile : old('mobile') }}">
                            </div>
                        </div>
                    </div>

                    @error('mobile')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('auth.password') }}</label>
                    <input type="password" name="password" value="{{ old('password') }}" class="form-control @error('password')  is-invalid @enderror" placeholder=""/>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-group-label">{{ trans('auth.password_repeat') }}</label>
                    <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation')  is-invalid @enderror" placeholder=""/>
                    @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

            </div>

            @if(empty($new_user) and empty($edit_new_user))
                <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.delete_account') }}</h3>

                    @if(!empty($user->deleteAccountRequest))
                        <div class="d-flex-center flex-column mt-16 text-center">
                            <div class="d-flex-center size-48 bg-warning rounded-12">
                                <x-iconsax-bol-info-circle class="icons text-white" width="24px" height="24px"/>
                            </div>

                            <h5 class="font-14 font-weight-bold text-dark mt-12">{{ trans('update.pending_review_request') }}</h5>
                            <p class="mt-8 font-14 text-gray-500">{{ trans('update.delete_account_request_pending_review_hint') }}</p>
                        </div>
                    @else
                        <p class="mt-16 font-14 text-gray-500">{{ trans('update.delete_account_hint') }}</p>

                        <a href="/panel/setting/deleteAccount" class="delete-action btn btn-outline-danger btn-block mt-16" data-confirm="{{ trans('update.delete_account_modal_confirm_btn_text') }}" data-msg="{{ trans('update.delete_account_modal_hint') }}">{{ trans('update.delete_account') }}</a>
                    @endif
                </div>

            @endif

        </div>

        <div class="col-12 col-lg-4 mt-20 mt-lg-0">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.localization') }}</h3>

                @if(!empty($userLanguages))
                    <div class="form-group mb-0">
                        <label class="form-group-label">{{ trans('auth.language') }}</label>
                        <select name="language" class="form-control select2" data-minimum-results-for-search="Infinity">
                            <option value="">{{ trans('auth.language') }}</option>
                            @foreach($userLanguages as $lang => $language)
                                <option value="{{ $lang }}" @if(!empty($user) and mb_strtolower($user->language) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                            @endforeach
                        </select>
                        @error('language')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                @endif

                <div class="form-group  mb-0 mt-20">
                    <label class="form-group-label">{{ trans('update.timezone') }}</label>
                    <select name="timezone" class="form-control select2" data-allow-clear="false">
                        <option value="" {{ empty($user->timezone) ? 'selected' : '' }} disabled>{{ trans('public.select') }}</option>
                        @foreach(getListOfTimezones() as $timezone)
                            <option value="{{ $timezone }}" @if(!empty($user) and $user->timezone == $timezone) selected @endif>{{ $timezone }}</option>
                        @endforeach
                    </select>
                    @error('timezone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                @if(!empty($currencies) and count($currencies))
                    @php
                        $userCurrency = currency();
                    @endphp

                    <div class="form-group  mb-0 mt-20">
                        <label class="form-group-label">{{ trans('update.currency') }}</label>
                        <select name="currency" class="form-control select2" data-allow-clear="false">
                            @foreach($currencies as $currencyItem)
                                <option value="{{ $currencyItem->currency }}" {{ ($userCurrency == $currencyItem->currency) ? 'selected' : '' }}>{{ currenciesLists($currencyItem->currency) }} ({{ currencySign($currencyItem->currency) }})</option>
                            @endforeach
                        </select>
                        @error('currency')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                @endif
            </div>

            <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.vacation_mode') }}</h3>

                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="vacationModeSwitch" type="checkbox" name="offline" class="custom-control-input" {{ $user->offline ? 'checked' : '' }}>
                        <label class="custom-control-label cursor-pointer" for="vacationModeSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="vacationModeSwitch">{{ trans('update.enable_vacation_mode') }}</label>
                    </div>
                </div>

                <div class="form-group mt-20">
                    <label class="form-group-label">{{ trans('panel.offline_message') }}</label>
                    <textarea name="offline_message" class="form-control" rows="6">{{ $user->offline_message }}</textarea>
                </div>

                <p class="font-14 text-gray-500">{{ trans('update.vacation_mode_message_hint') }}</p>
            </div>

        </div>

        <div class="col-12 col-lg-4 mt-20 mt-lg-0">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold mb-24">{{ trans('update.account_options') }}</h3>

                <div class="d-flex-center mt-36 mb-20">
                    <img src="/assets/design_1/img/panel/settings/account_options.svg" alt="" class="img-fluid" width="231px" height="200px">
                </div>

                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="newsletterSwitch" type="checkbox" name="join_newsletter" class="custom-control-input" {{ (!empty($user) and $user->newsletter) ? 'checked' : '' }}>
                        <label class="custom-control-label cursor-pointer" for="newsletterSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="newsletterSwitch">{{ trans('auth.join_newsletter') }}</label>
                    </div>
                </div>


                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="publicMessagesSwitch" type="checkbox" name="public_message" class="custom-control-input" {{ (!empty($user) and $user->public_message) ? 'checked' : '' }}>
                        <label class="custom-control-label cursor-pointer" for="publicMessagesSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="publicMessagesSwitch">{{ trans('update.enable_profile_messages') }}</label>
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="enableProfileStatisticsSwitch" type="checkbox" name="enable_profile_statistics" class="custom-control-input" {{ (!empty($user) and $user->enable_profile_statistics) ? 'checked' : '' }}>
                        <label class="custom-control-label cursor-pointer" for="enableProfileStatisticsSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="enableProfileStatisticsSwitch">{{ trans('update.enable_profile_statistics') }}</label>
                    </div>
                </div>

                <div class="form-group d-flex align-items-center mb-0">
                    <div class="custom-switch mr-8">
                        <input id="autoRenewSubscriptionSwitch" type="checkbox" name="auto_renew_subscription" class="custom-control-input" {{ (!empty($user) and $user->auto_renew_subscription) ? 'checked' : '' }}>
                        <label class="custom-control-label cursor-pointer" for="autoRenewSubscriptionSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="autoRenewSubscriptionSwitch">{{ trans('update.auto_renew_subscription') }}</label>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
