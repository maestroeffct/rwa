<div class="meeting-package-grid-card-1 position-relative">
    <div class="meeting-package-grid-card-1__mask"></div>

    <div class="meeting-package-grid-card-1__contents position-relative z-index-3 bg-white rounded-16 py-16">

        @if(!empty($meetingPackage->discount))
            <div class="meeting-package-grid-card-1__discount-badge d-inline-flex-center py-4 px-8 rounded-16 font-12 text-white bg-accent">
                {{ trans('update.percent_off', ['percent' => $meetingPackage->discount]) }}
            </div>
        @endif

        <div class="px-16">

            @php
                $meetingPackageIcon = !empty($meetingPackage->icon) ? $meetingPackage->icon : getMeetingPackagesSettings("default_icon");
            @endphp

            @if(!empty($meetingPackageIcon))
                <div class="d-flex-center size-48">
                    <img src="{{ $meetingPackageIcon }}" alt="{{ $meetingPackage->title }}" class="img-fluid">
                </div>
            @else
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <x-iconsax-bul-teacher class="icons text-gray-500" width="24px" height="24px"/>
                </div>
            @endif

            <h3 class="font-16 mt-12">{{ $meetingPackage->title }}</h3>

            <div class="bg-gray-100 mt-12 p-12 rounded-12">
                {{-- Sessions --}}
                <div class="d-flex align-items-center gap-4">
                    <x-tick-icon class="icons text-success" width="16px" height="16px"/>
                    <span class="font-weight-bold">{{ $meetingPackage->sessions }}</span>
                    <span class="text-gray-500">{{ trans('public.sessions') }}</span>
                </div>

                {{-- Meeting Hours --}}
                <div class="d-flex align-items-center gap-4 mt-12">
                    <x-tick-icon class="icons text-success" width="16px" height="16px"/>
                    <span class="font-weight-bold">{{ convertMinutesToHourAndMinute($meetingPackage->session_duration) }}</span>
                    <span class="text-gray-500">{{ trans('update.meeting_hours') }}</span>
                </div>

                {{-- Validity --}}
                <div class="d-flex align-items-center gap-4 mt-12">
                    <x-tick-icon class="icons text-success" width="16px" height="16px"/>
                    <span class="font-weight-bold">{{ $meetingPackage->duration }}</span>
                    <span class="text-gray-500">{{ trans('update.type_validity', ['type' => trans("update.{$meetingPackage->duration_type}")]) }}</span>
                </div>
            </div>

            {{-- Creator --}}
            <a href="{{ $meetingPackage->creator->getProfileUrl() }}" target="_blank" class="">
                <div class="d-flex align-items-center mt-16">
                    <div class="size-32 bg-gray-100 rounded-circle">
                        <img src="{{ $meetingPackage->creator->getAvatar(32) }}" alt="{{ $meetingPackage->creator->full_name }}" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-4">
                        <h5 class="font-12 text-dark">{{ $meetingPackage->creator->full_name }}</h5>

                        @php
                            $meetingPackageCreatorRate = $meetingPackage->creator->rates(true);
                        @endphp

                        @if($meetingPackageCreatorRate['count'] > 0)
                            @include("design_1.web.components.rate", [
                                'rate' => $meetingPackageCreatorRate['rate'],
                                'rateCount' => $meetingPackageCreatorRate['count'],
                                'rateClassName' => 'mt-2',
                            ])
                        @endif
                    </div>
                </div>
            </a>

        </div>

        {{-- Price --}}
        <div class="d-flex align-items-center justify-content-between mt-16 pt-16 px-16 border-top-gray-200">
            <div class="d-flex align-items-center gap-4 font-16 font-weight-bold text-primary">
                @if(!empty($meetingPackage->price) and $meetingPackage->price > 0)
                    @php
                        $meetingPackagePrices = $meetingPackage->getPrices();
                    @endphp

                    @if(!empty($meetingPackage->discount))
                        <span class="">{{ handlePrice($meetingPackagePrices['price']) }}</span>
                        <span class="font-14 font-weight-400 text-gray-500 text-decoration-line-through">{{ handlePrice($meetingPackagePrices['real_price']) }}</span>
                    @else
                        <span class="">{{ handlePrice($meetingPackagePrices['price']) }}</span>
                    @endif
                @else
                    <span class="">{{ trans('update.free') }}</span>
                @endif
            </div>


            @if(!empty($meetingPackage->price) and $meetingPackage->price > 0)
                <div class="js-meeting-package-add-to-cart d-flex cursor-pointer" data-package="{{ $meetingPackage->id }}">
                    <div class="d-flex align-items-center gap-8 ">
                        <span class="text-gray-500">{{ trans('update.buy_package') }}</span>
                        <x-iconsax-lin-arrow-right class="icons text-gray-500" width="24px" height="24px"/>
                    </div>
                </div>
            @else
                <a href="/meeting-packages/{{ $meetingPackage->id }}/free" class="d-flex align-items-center gap-8 ">
                    <span class="text-gray-500">{{ trans('update.buy_package') }}</span>
                    <x-iconsax-lin-arrow-right class="icons text-gray-500" width="24px" height="24px"/>
                </a>
            @endif

        </div>
    </div>
</div>
