<div class="bg-white pt-16 rounded-24">
    <div class="d-flex px-16 align-items-center">
        @if(!empty($assignment->icon))
            <div class="size-64">
                <img src="{{ $assignment->icon }}" alt="" class="img-fluid">
            </div>
        @else
            <div class="d-flex-center size-64 rounded-circle bg-gray-100">
                <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
            </div>
        @endif

        <div class="ml-12">
            <h6 class="font-14 text-dark">{{ $assignment->title }}</h6>
            @if(!empty($assignment->webinar))
                <div class="mt-4 font-12 text-gray-500">{{ $assignment->webinar->title }}</div>
            @endif
        </div>
    </div>

    <div class="mt-16 p-16 border-top-gray-100">
        <div class="row">
            {{-- Total Submission --}}
            <div class="col-6 col-md-4 col-lg-2 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                    <x-iconsax-bul-document-text class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-16 font-weight-bold">{{ $totalSubmissionsCount }}</span>
                        <span class="d-block mt-8 text-gray-500">{{ trans('update.total_submissions') }}</span>
                    </div>
                </div>
            </div>

            {{-- Passed Submissions --}}
            <div class="col-6 col-md-4 col-lg-2 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                    <x-iconsax-bul-clipboard-tick class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-16 font-weight-bold">{{ $totalPassedSubmissions }}</span>
                        <span class="d-block mt-8 text-gray-500">{{ trans('update.passed_submissions') }}</span>
                    </div>
                </div>
            </div>

            {{-- Failed Submissions --}}
            <div class="col-6 col-md-4 col-lg-2 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                    <x-iconsax-bul-clipboard-close class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-16 font-weight-bold">{{ $totalFailedSubmissions }}</span>
                        <span class="d-block mt-8 text-gray-500">{{ trans('update.failed_submissions') }}</span>
                    </div>
                </div>
            </div>

            {{-- Pending Review --}}
            <div class="col-6 col-md-4 col-lg-2 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                    <x-iconsax-bul-timer class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-16 font-weight-bold">{{ $totalPendingSubmissions }}</span>
                        <span class="d-block mt-8 text-gray-500">{{ trans('update.pending_review') }}</span>
                    </div>
                </div>
            </div>

            {{-- Not Submitted --}}
            <div class="col-6 col-md-4 col-lg-2 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-document-normal class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-16 font-weight-bold">{{ $totalNotSubmitted }}</span>
                        <span class="d-block mt-8 text-gray-500">{{ trans('update.not_submitted') }}</span>
                    </div>
                </div>
            </div>

            {{-- Success Rate --}}
            <div class="col-6 col-md-4 col-lg-2 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-100">
                        <x-iconsax-bul-cup class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-16 font-weight-bold">{{ $successRatePercent }}%</span>
                        <span class="d-block mt-8 text-gray-500">{{ trans('update.success_rate') }}</span>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
