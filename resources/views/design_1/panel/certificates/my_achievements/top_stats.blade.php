<div class="row">
    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.total_certificates') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-archive-book class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalCertificatesCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.quiz_certificates') }}</span>
                <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-task-square class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $quizCertificatesCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.completion_certificates') }}</span>
                <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                    <x-iconsax-bul-video-play class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $completionCertificatesCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.not_acheived_certificates') }}</span>
                <div class="size-48 d-flex-center bg-danger-30 rounded-12">
                    <x-iconsax-bul-clipboard-close class="icons text-danger" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ count($pendingCertificates) }}</h5>
        </div>
    </div>
</div>
