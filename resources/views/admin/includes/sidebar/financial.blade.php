@if($authUser->can('admin_documents') or
                $authUser->can('admin_sales_list') or
                $authUser->can('admin_payouts') or
                $authUser->can('admin_offline_payments_list') or
                $authUser->can('admin_subscribe') or
                $authUser->can('admin_registration_packages') or
                $authUser->can('admin_installments')
            )
    <li class="menu-header">{{ trans('admin/main.financial') }}</li>
@endif

@can('admin_documents')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/documents*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-receipt-item class="icons" width="24px" height="24px"/>
                        <span>{{ trans('admin/main.balances') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                        @can('admin_documents_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/documents', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/documents">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan

                        @can('admin_documents_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/documents/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/documents/new">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_sales_list')
                <li class="nav-item {{ (request()->is(getAdminPanelUrl('/financial/sales*', false))) ? 'active' : '' }}">
                    <a href="{{ getAdminPanelUrl() }}/financial/sales" class="nav-link">
                        <x-iconsax-bul-bag-timer class="icons" width="24px" height="24px"/>
                        <span>{{ trans('admin/main.sales_list') }}</span>
                    </a>
                </li>
            @endcan

            @can('admin_payouts')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/payouts*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><x-iconsax-bul-empty-wallet-change class="icons" width="24px" height="24px"/> <span>{{ trans('admin/main.payout') }}</span></a>
                    <ul class="dropdown-menu">
                        @can('admin_payouts_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/payouts', false)) and request()->get('payout') == 'requests') ? 'active' : '' }}">
                                <a href="{{ getAdminPanelUrl() }}/financial/payouts?payout=requests" class="nav-link @if(!empty($sidebarBeeps['payoutRequest']) and $sidebarBeeps['payoutRequest']) beep beep-sidebar @endif">
                                    <span>{{ trans('panel.requests') }}</span>
                                </a>
                            </li>
                        @endcan

                        @can('admin_payouts_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/payouts', false)) and request()->get('payout') == 'history') ? 'active' : '' }}">
                                <a href="{{ getAdminPanelUrl() }}/financial/payouts?payout=history" class="nav-link">
                                    <span>{{ trans('public.history') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_offline_payments_list')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/offline_payments*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><x-iconsax-bul-moneys class="icons" width="24px" height="24px"/> <span>{{ trans('admin/main.offline_payments') }}</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ (request()->is(getAdminPanelUrl('/financial/offline_payments', false)) and request()->get('page_type') == 'requests') ? 'active' : '' }}">
                            <a href="{{ getAdminPanelUrl() }}/financial/offline_payments?page_type=requests" class="nav-link @if(!empty($sidebarBeeps['offlinePayments']) and $sidebarBeeps['offlinePayments']) beep beep-sidebar @endif">
                                <span>{{ trans('panel.requests') }}</span>
                            </a>
                        </li>

                        <li class="{{ (request()->is(getAdminPanelUrl('/financial/offline_payments', false)) and request()->get('page_type') == 'history') ? 'active' : '' }}">
                            <a href="{{ getAdminPanelUrl() }}/financial/offline_payments?page_type=history" class="nav-link">
                                <span>{{ trans('public.history') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @can('admin_subscribe')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/subscribes*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-flash-circle class="icons" width="24px" height="24px"/>
                        <span>{{ trans('admin/main.subscribes') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_subscribe_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/subscribes', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/subscribes">{{ trans('admin/main.packages') }}</a>
                            </li>
                        @endcan

                        @can('admin_subscribe_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/subscribes/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/subscribes/new">{{ trans('admin/main.new_package') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan


            @can('admin_rewards')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/rewards*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-cup class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.rewards') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_rewards_history')
                            <li class="{{ (request()->is(getAdminPanelUrl('/rewards', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/rewards">{{ trans('public.history') }}</a>
                            </li>
                        @endcan
                        @can('admin_rewards_items')
                            <li class="{{ (request()->is(getAdminPanelUrl('/rewards/items', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/rewards/items">{{ trans('update.conditions') }}</a>
                            </li>
                        @endcan
                        @can('admin_rewards_settings')
                            <li class="{{ (request()->is(getAdminPanelUrl('/rewards/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/rewards/settings">{{ trans('admin/main.settings') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_registration_packages')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/registration-packages*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-diamonds class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.registration_packages') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_registration_packages_lists')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/registration-packages', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/registration-packages">{{ trans('admin/main.packages') }}</a>
                            </li>
                        @endcan

                        @can('admin_registration_packages_new')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/registration-packages/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/registration-packages/new">{{ trans('admin/main.new_package') }}</a>
                            </li>
                        @endcan

                        @can('admin_registration_packages_reports')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/registration-packages/reports', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/registration-packages/reports">{{ trans('admin/main.reports') }}</a>
                            </li>
                        @endcan

                        @can('admin_registration_packages_settings')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/registration-packages/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/registration-packages/settings">{{ trans('admin/main.settings') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_installments')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/installments*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-convert-card class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.installments') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_installments_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/financial/installments/create') }}">{{ trans('update.new_plan') }}</a>
                            </li>
                        @endcan

                        @can('admin_installments_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/financial/installments') }}">{{ trans('update.plans') }}</a>
                            </li>
                        @endcan

                        @can('admin_installments_purchases')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/purchases', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/financial/installments/purchases') }}">{{ trans('update.purchases') }}</a>
                            </li>
                        @endcan

                        @can('admin_installments_overdue_lists')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/overdue', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/financial/installments/overdue') }}">{{ trans('update.overdue') }}</a>
                            </li>
                        @endcan

                        @can('admin_installments_overdue_history')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/overdue_history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/financial/installments/overdue_history') }}">{{ trans('update.overdue_history') }}</a>
                            </li>
                        @endcan

                        @can('admin_installments_verification_requests')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/verification_requests', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/financial/installments/verification_requests') }}">{{ trans('update.verification_requests') }}</a>
                            </li>
                        @endcan

                        @can('admin_installments_verified_users')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/verified_users', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/financial/installments/verified_users') }}">{{ trans('update.verified_users') }}</a>
                            </li>
                        @endcan

                        @can('admin_installments_settings')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/installments/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/financial/installments/settings') }}">{{ trans('admin/main.settings') }}</a>
                            </li>
                        @endcan

        </ul>
    </li>
@endcan
