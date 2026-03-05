<div class="form-group">
    <label class="form-group-label">{{ trans('site.captcha') }}</label>

    <div class="d-flex align-items-center flex-wrap gap-16">
        <div class="flex-1">
            <input type="text" name="captcha" class="js-ajax-captcha form-control @error('captcha')  is-invalid @enderror">
        </div>

        <div class="flex-1">
            <div class="d-flex align-items-center border-gray-300 p-4 rounded-12">
                <div class="captcha-image-card d-flex-center bg-gray-100 p-4 rounded-4 flex-1">
                    <img id="captchaImageComment" class="captcha-image rounded-4" src="">
                </div>

                <div id="refreshCaptcha" class="d-flex-center size-40 bg-hover-gray-100 ml-8 mr-4 rounded-8 cursor-pointer">
                    <x-iconsax-lin-refresh-2 class="icons text-gray-500" width="16px" height="16px"/>
                </div>
            </div>
        </div>
    </div>

    <div class="invalid-feedback mt-4 d-block">
        @error('captcha')
        {{ $message }}
        @enderror
    </div>
</div>
