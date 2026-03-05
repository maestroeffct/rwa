<div class="bg-white p-16 rounded-16">
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-64 rounded-circle bg-gray-100">
            @php
                $meetingPackageIcon = !empty($meetingPackage->icon) ? $meetingPackage->icon : getMeetingPackagesSettings("default_icon");
            @endphp

            @if(!empty($meetingPackageIcon))
                <img src="{{ $meetingPackageIcon }}" alt="{{ $meetingPackage->title }}" class="img-cover rounded-circle">
            @endif
        </div>

        <div class="ml-12">
            <h3 class="font-14">{{ $meetingPackage->title }}</h3>
            <p class="font-12 text-gray-500 mt-4">{{ $meetingPackageSold->user->full_name }}</p>
        </div>
    </div>

    <div class="mt-16 pt-16 border-top-gray-100">
        <div class="row">
            {{-- Purchase Date --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-gray-100 rounded-circle">
                        <x-iconsax-lin-bag class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16">{{ dateTimeFormat($meetingPackageSold->paid_at, 'j M Y') }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.purchase_date') }}</p>
                    </div>
                </div>
            </div>

            {{-- Expiry Date --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-gray-100 rounded-circle">
                        <x-iconsax-lin-calendar-remove class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16">{{ dateTimeFormat($meetingPackageSold->expire_at, 'j M Y') }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.expiry_date') }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Sessions --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-gray-100 rounded-circle">
                        <x-iconsax-lin-monitor-recorder class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16">{{ $meetingPackageSold->sessions->count() }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.total_sessions') }}</p>
                    </div>
                </div>
            </div>

            {{-- Not Scheduled --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-gray-100 rounded-circle">
                        <x-iconsax-lin-calendar-2 class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16">{{ ($meetingPackageSold->notScheduled > 0) ? $meetingPackageSold->notScheduled : '-' }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.not_scheduled') }}</p>
                    </div>
                </div>
            </div>

            {{-- Scheduled --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-gray-100 rounded-circle">
                        <x-iconsax-lin-calendar-tick class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16">{{ ($meetingPackageSold->scheduled > 0) ? $meetingPackageSold->scheduled : '-' }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('update.scheduled') }}</p>
                    </div>
                </div>
            </div>

            {{-- Finished --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-gray-100 rounded-circle">
                        <x-iconsax-lin-tick-circle class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-16">{{ ($meetingPackageSold->ended > 0) ? $meetingPackageSold->ended : '-' }}</h5>
                        <p class="text-gray-500 mt-8">{{ trans('public.finished') }}</p>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
