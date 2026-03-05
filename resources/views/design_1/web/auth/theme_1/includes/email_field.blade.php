<div class="form-group">
    <label class="form-group-label" for="email">{{ trans('public.email') }}: {{ !empty($optional) ? "(". trans('public.optional') .")" : '' }}</label>
    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ !empty($defaultEmailValue) ? $defaultEmailValue : old('email') }}" aria-describedby="emailHelp">
    @error('email')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>
