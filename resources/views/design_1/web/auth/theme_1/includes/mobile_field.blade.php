<div class="form-group js-auth-mobile-container">
    <div class="register-mobile-form-group position-relative @error('mobile') is-invalid @enderror">
        <label class="form-group-label">{{ trans('public.phone') }} {{ !empty($optional) ? "(". trans('public.optional') .")" : '' }}</label>

        <div class="row">
            <div class="col-4 h-100 pr-0">
                <select name="country_code" class="form-control country-code-select2" data-dropdown-parent=".js-auth-mobile-container">
                    @foreach(getCountriesMobileCode() as $country => $code)
                        <option value="{{ $code }}" @if($code == old('country_code')) selected @endif>{{ $country }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-8 h-100 pl-4">
                <input type="tel" name="mobile" class="register-mobile-form-group__input">
            </div>
        </div>
    </div>

    @error('mobile')
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror
</div>
