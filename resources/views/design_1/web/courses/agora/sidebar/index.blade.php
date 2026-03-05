<div id="agoraPageSidebar" class="agora-page__sidebar py-12">

    <div class="d-flex d-lg-none align-items-center justify-content-end px-24 py-12">
        <div class="js-toggle-show-learning-page-sidebar-drawer cursor-pointer">
            <x-iconsax-lin-add class="close-icon text-gray-500" width="26px" height="26px"/>
        </div>
    </div>

    <div class="px-12">
        {{-- User & Progress --}}
        <div class="card-with-mask position-relative mb-24">
            <div class="mask-8-white bg-primary-20"></div>
            <div class="position-relative z-index-2 bg-primary p-8 rounded-24">
                <div class="d-flex align-items-center justify-content-between bg-white rounded-16">
                    <div class="d-flex align-items-center p-12">
                        <div class="size-40 rounded-circle">
                            <img src="{{ $hostUser->getAvatar(40) }}" alt="{{ $hostUser->full_name }}" class="img-cover rounded-circle">
                        </div>
                        <div class="ml-8">
                            <h4 class="font-14 text-dark">{{ $hostUser->full_name }}</h4>
                            <p class="mt-2 font-12 text-gray-500">{{ trans('update.host') }}</p>
                        </div>
                    </div>

                    <div class="d-flex-center size-40 p-6">
                        <x-iconsax-bul-teacher class="icons sidebar-teacher-icon" width="40px" height="40px"/>
                    </div>
                </div>

                <div class="js-all-live-users-count-card d-none align-items-center gap-4 mt-16 font-12 text-white">
                    <x-iconsax-lin-profile-2user class="icons text-white" width="16px" height="16px"/>

                    <div class="d-flex align-items-center gap-4">
                        <span class="js-all-live-users-count"></span>
                        <span class="">{{ trans('update.live_users') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mt-16 pt-12 border-top-gray-100">

        <div class="custom-tabs">
            <div class="px-12">
                <div class="position-relative sidebar-tabs d-flex align-items-center p-4 bg-gray-100 rounded-30 gap-8">
                    <div class="navbar-item d-flex-center cursor-pointer py-8 px-32 rounded-20 flex-1 active" data-tab-toggle data-tab-href="#chatTab">
                        <span class="">{{ trans('update.chat') }}</span>
                    </div>

                    <div class="navbar-item d-flex-center gap-4 cursor-pointer py-8 px-32 rounded-20 flex-1" data-tab-toggle data-tab-href="#usersTab">
                        <span class="">{{ trans('panel.users') }}</span>
                        <span class="js-joined-users-count"><!--(12)--></span>
                    </div>
                </div>
            </div>

            <div class="custom-tabs-body">
                <div class="custom-tabs-content active" id="chatTab">
                    @include('design_1.web.courses.agora.sidebar.chats')
                </div>

                <div class="custom-tabs-content" id="usersTab">
                    @include('design_1.web.courses.agora.sidebar.users')
                </div>
            </div>
        </div>

    </div>
</div>
