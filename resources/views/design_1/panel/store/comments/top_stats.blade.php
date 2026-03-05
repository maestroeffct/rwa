<div class="row">
    <div class="col-12 col-lg-4">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('panel.comments') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-message class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $allCommentsCount }}</h5>
        </div>
    </div>

    <div class="col-12 col-lg-4 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('panel.replied') }}</span>
                <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-message-tick class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $repliedCommentsCount }}</h5>
        </div>
    </div>

    <div class="col-12 col-lg-4 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('panel.not_replied') }}</span>
                <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                    <x-iconsax-bul-message-time class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ ($allCommentsCount - $repliedCommentsCount) }}</h5>
        </div>
    </div>

</div>
