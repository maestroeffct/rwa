<div class="d-flex-center flex-column text-center mt-22">

    <div class="">
        <img src="/assets/design_1/img/courses/share.svg" alt="{{ trans('public.share') }}" class="img-fluid" width="215px" height="160px">
    </div>

    <h4 class="font-14 font-weight-bold mt-16 text-dark">{{ trans('update.share_this_content_with_your_friends') }}</h4>
    <p class="mt-4 font-12 text-gray-500">{{ trans('update.share_this_content_with_your_friends_hint_msg') }}</p>

    <div class="d-flex-center flex-wrap gap-16 mt-14">

        <a href="{{ $course->getShareLink('whatsapp') }}" target="_blank" class="d-flex-center size-48 rounded-12 bg-whatsapp-50">
            <x-whatsapp-bol-icon class="icons text-whatsapp" width="24px" height="24px"/>
        </a>

        <a href="{{ $course->getShareLink('twitter') }}" target="_blank" class="d-flex-center size-48 rounded-12 bg-twitter-50">
            <x-twitter-bol-icon class="icons text-twitter" width="24px" height="24px"/>
        </a>

        <a href="{{ $course->getShareLink('telegram') }}" target="_blank" class="d-flex-center size-48 rounded-12 bg-telegram-50">
            <x-telegram-bol-icon class="icons text-telegram" width="24px" height="24px"/>
        </a>

        <a href="{{ $course->getShareLink('facebook') }}" target="_blank" class="d-flex-center size-48 rounded-12 bg-facebook-50">
            <x-facebook-bol-icon class="icons text-facebook" width="24px" height="24px"/>
        </a>

        <a href="{{ $course->getShareLink('linkedin') }}" target="_blank" class="d-flex-center size-48 rounded-12 bg-linkedin-50">
            <x-linkedin-bol-icon class="icons text-linkedin" width="24px" height="24px"/>
        </a>
    </div>

    @php
        $url = $course->getUrl();
    @endphp

    <div class="form-group mb-8 d-flex-center mt-32 p-16 rounded-12 bg-white border-gray-200 w-100">
        <span class="font-14 text-gray-500 mr-8 text-ellipsis">{{ $url }}</span>

        <button type="button" class="js-copy-input btn-transparent d-flex" data-tippy-content="{{ trans('public.copy') }}" data-copy-text="{{ trans('public.copy') }}" data-done-text="{{ trans('public.copied') }}">
            <x-iconsax-lin-document-copy class="icons text-gray-400" width="24px" height="24px"/>
        </button>

        <input type="hidden" class="form-control" value="{{ $url }}">
    </div>

</div>
