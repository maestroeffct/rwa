<div class="card-with-mask mt-40">
    <div class="mask-8-white"></div>

    <div class="position-relative bg-white rounded-24 p-16 z-index-2">
        <div class="d-flex-center flex-column text-center border-gray-200 border-dashed rounded-12 p-32 pb-40">
            <div class="d-flex-center size-56 rounded-12 bg-primary-20">
                <x-iconsax-bul-messages class="icons text-primary" width="32px" height="32px"/>
            </div>

            <h4 class="font-14 mt-12">{{ trans('update.login_to_reply') }}</h4>
            <p class="font-12 text-gray-500 mt-4">{{ trans('update.login_to_reply_hint') }}</p>

            <a href="/login" class="d-flex align-items-center mt-8 text-primary">
                <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                <span class="font-weight-bold ml-2">{{ trans('auth.login') }}</span>
            </a>
        </div>
    </div>
</div>
