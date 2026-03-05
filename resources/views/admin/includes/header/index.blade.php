<div class="admin-header d-flex align-items-start justify-content-between bg-primary pt-16 px-24 px-lg-28">
    <div class="d-flex cursor-pointer" data-toggle="sidebar">
        <x-iconsax-lin-menu class="icons text-dark" width="24px" height="24px"/>
    </div>

    <div class="d-flex align-items-center justify-content-end flex-1 gap-16">

      {{-- About System --}}
        <div class="about-system-select position-relative">
            <div class="d-flex-center size-32 rounded-8 cursor-pointer position-relative" style="background-color: rgba(255, 255, 255, 0.2);">
                <x-iconsax-lin-info-circle class="icons text-white" width="20px" height="20px"/>
            </div>

            <div class="about-system-dropdown py-12" style="position: absolute; top: 100%; right: 0; min-width: 340px; max-width: 380px; background: #fff; border-radius: 24px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15); border: 1px solid #e9ecef; opacity: 0; visibility: hidden; transform: translateY(15px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1050; top: 40px;">
                <div class="px-16 text-left">
                    {{-- Header Section --}}
                    <div class="d-flex align-items-center p-16 rounded-16" style="background: var(--primary);">
                        <div class="d-flex-center size-56 bg-white rounded-circle shadow-sm">
                            <img src="{{ asset('assets/design_1/img/logo.jpg') }}" alt="Rocket LMS" class="size-48 rounded-circle" style="object-fit: cover;">
                        </div>
                        <div class="ml-12">
                            <h4 class="font-16 font-weight-bold text-white mb-0">Rocket LMS V2.1</h4>
                            <span class="font-12 text-white" style="opacity: 0.75;">Learning Management System</span>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-16 pt-12 border-top text-center">
                        <span class="font-12 text-gray-500">
                        All rights reserved for Rocket Soft on Codecanyon
                        </span>
                        <div class="d-flex align-items-center justify-content-center mt-8" style="gap: 8px;">
                            <a href="https://crm.rocket-soft.org/index.php/tickets" target="_blank" class="badge badge-soft-primary font-10 px-8 py-4 text-decoration-none">Support Center</a>
                            <a href="https://codecanyon.net/user/rocketsoft/portfolio" target="_blank" class="badge badge-soft-primary font-10 px-8 py-4 text-decoration-none">Envato Profile</a>
                            <a href="{{ getAdminPanelUrl('/licenses') }}"  class="badge badge-soft-primary font-10 px-8 py-4 text-decoration-none">License Info</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


                    <style>
            .about-system-select:hover .about-system-dropdown {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
            }
            
            .about-system-dropdown:hover {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
            }
            
            .about-system-dropdown {
                direction: ltr !important;
                transform-origin: top right;
            }
            
            .about-system-dropdown .badge:hover {
                transform: translateY(-1px);
                transition: all 0.3s ease;
            }
            
            .about-system-dropdown .badge {
                transition: all 0.3s ease;
            }
            
            @media (max-width: 768px) {
                .about-system-dropdown {
                    min-width: 280px !important;
                    max-width: 320px !important;
                    left: auto !important;
                    right: 0 !important;
                    transform: translateY(15px) !important;
                }
                
                .about-system-select:hover .about-system-dropdown {
                    transform: translateY(0) !important;
                }
            }
            
            @media (max-width: 480px) {
                .about-system-dropdown {
                    min-width: 260px !important;
                    max-width: 280px !important;
                    right: -20px !important;
                }
            }
        </style>


        {{-- Ai --}}
        @if(!empty(getAiContentsSettingsName('status')) && !empty(getAiContentsSettingsName('active_for_admin_panel')))
            <div class="js-show-ai-content-drawer d-flex-center size-32 rounded-8 cursor-pointer" style="background-color: rgba(255, 255, 255, 0.2);">
                <x-iconsax-lin-cpu-charge class="icons text-white" width="20px" height="20px"/>
            </div>
        @endif

        {{-- Curreny --}}
        @include('admin.includes.header.currency')

        {{-- language --}}
        @include('admin.includes.header.language')

        {{-- Notification --}}
        @include('admin.includes.header.notification')

        <div class="admin-header__item-divider"></div>

        {{-- User --}}
        @include('admin.includes.header.auth_user_info')
    </div>
</div>
