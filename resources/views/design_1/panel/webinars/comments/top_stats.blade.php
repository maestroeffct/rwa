<div class="row">
    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('panel.comments') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                <x-iconsax-bul-message-2 class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalCommentsCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('panel.replied') }}</span>
                <div class="size-48 d-flex-center bg-success-30 rounded-12">
                <x-iconsax-bul-message-2 class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $repliedCommentsCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('panel.not_replied') }}</span>
                <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                <x-iconsax-bul-message-2 class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ ($totalCommentsCount - $repliedCommentsCount) }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('admin/main.reports') }}</span>
                <div class="size-48 d-flex-center bg-danger-30 rounded-12">
                <x-iconsax-bul-message-2 class="icons text-danger" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalReportsCommentsCount }}</h5>
        </div>
    </div>


</div>
