@php
    $getPanelSidebarSettings = getPanelSidebarSettings();
@endphp


<div id="panelSidebar" class="panel-sidebar bg-white">
    <div class="panel-sidebar__contents bg-white {{ (empty($getPanelSidebarSettings) or empty($getPanelSidebarSettings['background'])) ? 'without-bottom-image' : '' }}"
        data-simplebar @if (!empty($isRtl)) data-simplebar-direction="rtl" @endif>

        <div class="js-show-panel-sidebar cursor-pointer d-flex d-lg-none">
            <x-iconsax-lin-add class="icons text-dark close-icon" width="24px" height="24px" />
        </div>

        <div class="d-flex-center flex-column mt-20 mt-lg-36">
            <div class="panel-sidebar__user-avatar size-64 rounded-circle">
                <img src="{{ $authUser->getAvatar(56) }}" alt="{{ $authUser->full_name }}"
                    class="img-cover rounded-circle">
            </div>

            <h4 class="font-14 font-weight-bold text-dark mt-8">{{ $authUser->full_name }}</h4>

            @if (!$authUser->isUser())
                @include('design_1.web.components.rate', [
                    'rate' => $authUser->rates(),
                    'rateClassName' => 'mt-4',
                ])
            @endif

            <div class="d-flex align-items-center justify-content-around mt-12 rounded-10 bg-gray p-8">
                @if ($authUser->isUser())
                    <div class="d-flex flex-column align-items-center">
                        <span class="font-12 font-weight-bold">{{ count($authUser->getPurchasedCoursesIds()) }}</span>
                        <span class="font-12 text-gray-500">{{ trans('panel.classes') }}</span>
                    </div>

                    <div class="gray-card-divider mx-16"></div>

                    <div class="d-flex flex-column align-items-center">
                        <span class="font-12 font-weight-bold">{{ $authUser->following()->count() }}</span>
                        <span class="font-12 text-gray-500">{{ trans('panel.following') }}</span>
                    </div>
                @else
                    <div class="d-flex flex-column align-items-center">
                        <span class="font-12 font-weight-bold">{{ $authUser->webinars()->count() }}</span>
                        <span class="font-12 text-gray-500">{{ trans('panel.classes') }}</span>
                    </div>

                    <div class="gray-card-divider mx-16"></div>

                    <div class="d-flex flex-column align-items-center">
                        <span class="font-12 font-weight-bold">{{ $authUser->followers()->count() }}</span>
                        <span class="font-12 text-gray-500">{{ trans('panel.followers') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div id="sidebarAccordions" class="pb-24">
            {{-- Menu Items --}}
            @include('design_1.panel.includes.sidebar.items')
        </div>
    </div>

    {{--
    @if (!empty($getPanelSidebarSettings) and !empty($getPanelSidebarSettings['background']))
        <div class="panel-sidebar__bottom-banner bg-white d-none d-md-block mb-32">
            <a href="{{ !empty($getPanelSidebarSettings['link']) ? $getPanelSidebarSettings['link'] : '' }}" class="">
                <img src="{{ !empty($getPanelSidebarSettings['background']) ? $getPanelSidebarSettings['background'] : '' }}" alt="" class="img-fluid">
            </a>
        </div>
    @endif --}}

</div>
