<div class="row">
    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.total_submissions') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-clipboard-text class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $totalSubmissionsCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.passed_assignments') }}</span>
                <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-timer-start class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $passedAssignmentsCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.failed_assignments') }}</span>
                <div class="size-48 d-flex-center bg-danger-30 rounded-12">
                    <x-iconsax-bul-message class="icons text-danger" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $failedAssignmentsCount }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.pending_review_assignments') }}</span>
                <div class="size-48 d-flex-center bg-accent-30 rounded-12">
                    <x-iconsax-bul-trend-up class="icons text-accent" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $pendingReviewAssignmentsCount }}</h5>
        </div>
    </div>
</div>
