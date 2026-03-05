<a href="{{ $organization->getProfileUrl() }}" class="text-decoration-none d-block">
    <div class="organization-card position-relative {{ !empty($organizationCardClassName) ? $organizationCardClassName : '' }}">
        <div class="organization-card__mask"></div>

        <div class="position-relative z-index-2 bg-white p-16 rounded-24">
            <div class="d-flex align-items-center p-12">
                <div class="position-relative size-64 rounded-circle bg-gray-100">
                    <img src="{{ $organization->getAvatar(64) }}" alt="{{ $organization->full_name }}" class="img-cover rounded-circle">

                    @if($organization->verified)
                        <div class="organization-card__verified-badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="{{ trans('public.verified') }}">
                            <x-tick-icon class="icons text-white" />
                        </div>
                    @endif
                </div>
                <div class="ml-16">
                    <h6 class="font-16 text-dark">{{ truncate($organization->full_name, 25) }}</h6>
                    @include('design_1.web.components.rate', ['rate' => $organization->rates()])
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between gap-64 mt-16 p-16 rounded-12 bg-gray-100">
                <div class="d-flex align-items-center">
                    <x-iconsax-bul-video-play class="icons text-gray-500" width="24px" height="24px"/>
                    <div class="ml-12">
                        <span class="d-block font-weight-bold text-dark">{{ $organization->getActiveWebinars(true) }}</span>
                        <span class="d-block font-14 text-gray-500 mt-4">{{ trans('product.courses') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <x-iconsax-bul-briefcase class="icons text-gray-500" width="24px" height="24px"/>
                    <div class="ml-12">
                        <span class="d-block font-weight-bold text-dark">{{ $organization->getOrganizationTeachers()->count() }}</span>
                        <span class="d-block font-14 text-gray-500 mt-4">{{ trans('home.instructors') }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-24">
                <div class="">
                    <span class="d-block font-weight-bold text-dark">{{ trans('public.view_profile') }}</span>
                    <span class="d-block font-14 text-gray-500 mt-2">{{ trans('update.more_information') }}</span>
                </div>

                <div class="d-flex-center size-40 rounded-circle border-gray-200 bg-hover-gray-100">
                    <x-iconsax-lin-arrow-right class="icons text-gray-400" width="16px" height="16px"/>
                </div>
            </div>
        </div>
    </div>
</a>
