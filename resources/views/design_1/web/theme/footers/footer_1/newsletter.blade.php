<div class="theme-footer-1__newsletter">
    <div class="container position-relative">
        <div class="theme-footer-1__newsletter-mask"></div>

        <div class="position-relative z-index-2 bg-white p-16 rounded-24">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6">
                    <div class="">
                        <div class="d-flex align-items-center gap-4">
                            @if(!empty($newsletterData['title']))
                                <h4 class="font-20">{{ $newsletterData['title'] }}</h4>
                            @endif

                            @if(!empty($newsletterData['emoji']))
                                <div class="theme-footer-1__newsletter-emoji">
                                    <img src="{{ $newsletterData['emoji'] }}" alt="emoji" class="img-fluid" width="20px" height="20px">
                                </div>
                            @endif
                        </div>

                        @if(!empty($newsletterData['subtitle']))
                            <div class="mt-8 font-14 text-gray-500">{{ $newsletterData['subtitle'] }}</div>
                        @endif

                    </div>
                </div>

                <div class="col-12 col-lg-6 mt-16 mt-lg-0 d-flex justify-content-end">
                    <div class="js-newsletter-form newsletter-form d-flex align-items-center justify-content-between p-12 rounded-12 border-gray-200">
                        <div class="form-group mb-0 flex-1">
                            <div class="d-flex align-items-center gap-8 px-12 flex-1">
                                <x-iconsax-lin-sms class="icons text-gray-500" width="24px" height="24px"/>
                                <input type="email" name="newsletter_email" class="js-ajax-newsletter_email flex-1" placeholder="{{ trans('footer.enter_email_here') }}">
                            </div>

                            <div class="invalid-feedback d-block position-absolute position-bottom-0"></div>
                        </div>

                        <button type="button" class="js-submit-newsletter-btn btn btn-primary btn-lg text-white">{{ trans('footer.join') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
