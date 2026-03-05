@if($authUser->can('admin_discount_codes') or
                $authUser->can('admin_cart_discount') or
                $authUser->can('admin_abandoned_cart') or
                $authUser->can('admin_product_discount') or
                $authUser->can('admin_feature_webinars') or
                $authUser->can('admin_gift') or
                $authUser->can('admin_promotion') or
                $authUser->can('admin_advertising') or
                $authUser->can('admin_newsletters') or
                $authUser->can('admin_advertising_modal') or
                $authUser->can('admin_registration_bonus') or
                $authUser->can('admin_floating_bar_create') or
                $authUser->can('admin_purchase_notifications') or
                $authUser->can('admin_product_badges')
            )
    <li class="menu-header">{{ trans('admin/main.marketing') }}</li>
@endif

@can('admin_discount_codes')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/discounts*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-ticket-discount class="icons" width="24px" height="24px"/>
                        <span>{{ trans('admin/main.discount_codes') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_discount_codes_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/discounts', false)) and empty(request()->get('instructor_coupons'))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/discounts">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan

                        @can('admin_discount_codes_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/discounts/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/discounts/new">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan

                        @can('admin_discount_codes_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/discounts', false)) and !empty(request()->get('instructor_coupons'))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/discounts?instructor_coupons=1">{{ trans('update.instructor_coupons') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_cart_discount_controls')
                <li class="nav-item {{ (request()->is(getAdminPanelUrl('/cart_discount*', false))) ? 'active' : '' }}">
                    <a href="{{ getAdminPanelUrl() }}/cart_discount" class="nav-link">
                        <x-iconsax-bul-shopping-cart class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.cart_discount') }}</span>
                    </a>
                </li>
            @endcan

            @can('admin_product_discount')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/special_offers*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-receipt-discount class="icons" width="24px" height="24px"/>
                        <span>{{ trans('admin/main.special_offers') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_product_discount_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/special_offers', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/special_offers">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan

                        @can('admin_product_discount_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/special_offers/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/special_offers/new">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_abandoned_cart')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/abandoned-cart*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-bag-timer class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.abandoned_cart') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_abandoned_cart_rules')
                            <li class="{{ (request()->is(getAdminPanelUrl('/abandoned-cart/rules/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl("/abandoned-cart/rules/create") }}">{{ trans('update.new_rule') }}</a>
                            </li>

                            <li class="{{ (request()->is(getAdminPanelUrl('/abandoned-cart/rules', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl("/abandoned-cart/rules") }}">{{ trans('update.rules') }}</a>
                            </li>
                        @endcan

                        @can('admin_abandoned_cart_users')
                            <li class="{{ (request()->is(getAdminPanelUrl('/abandoned-cart/users-carts', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl("/abandoned-cart/users-carts") }}">{{ trans('update.users_carts') }}</a>
                            </li>
                        @endcan

                        @can('admin_abandoned_cart_settings')
                            <li class="{{ (request()->is(getAdminPanelUrl('/abandoned-cart/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl("/abandoned-cart/settings") }}">{{ trans('update.settings') }}</a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endcan

            @can('admin_feature_webinars')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/webinars/features*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-crown class="icons" width="24px" height="24px"/>
                        <span>{{ trans('admin/main.feature_webinars') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                    @can('admin_feature_webinars_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/webinars/features/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/webinars/features/create">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan

                        @can('admin_feature_webinars')
                            <li class="{{ (request()->is(getAdminPanelUrl('/webinars/features', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/webinars/features">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan()
                       
                    </ul>
                </li>
            @endcan

            @can('admin_cashback')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/cashback*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-empty-wallet-change class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.cashback') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_cashback_rules')
                            <li class="{{ (request()->is(getAdminPanelUrl('/cashback/rules/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/cashback/rules/new') }}">{{ trans('update.new_rule') }}</a>
                            </li>
                        @endcan

                        @can('admin_cashback_rules')
                            <li class="{{ (request()->is(getAdminPanelUrl('/cashback/rules', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/cashback/rules') }}">{{ trans('update.rules') }}</a>
                            </li>
                        @endcan

                        @can('admin_cashback_transactions')
                            <li class="{{ (request()->is(getAdminPanelUrl('/cashback/transactions', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/cashback/transactions') }}">{{ trans('update.transactions') }}</a>
                            </li>
                        @endcan

                        @can('admin_cashback_history')
                            <li class="{{ (request()->is(getAdminPanelUrl('/cashback/history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/cashback/history') }}">{{ trans('update.history') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_gift')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/gifts*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-gift class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.gifts') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_gift_history')
                            <li class="{{ (request()->is(getAdminPanelUrl('/gifts', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl("/gifts") }}">{{ trans('public.history') }}</a>
                            </li>
                        @endcan
                        @can('admin_gift_settings')
                            <li class="{{ (request()->is(getAdminPanelUrl('/gifts/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl("/gifts/settings") }}">{{ trans('admin/main.settings') }}</a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endcan

            @can('admin_promotion')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/financial/promotions*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-ranking class="icons" width="24px" height="24px"/>
                        <span>{{ trans('admin/main.promotions') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_promotion_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/promotions', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/promotions">{{ trans('admin/main.plans') }}</a>
                            </li>
                        @endcan
                        @can('admin_promotion_list')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/promotions/sales', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/promotions/sales">{{ trans('admin/main.promotion_sales') }}</a>
                            </li>
                        @endcan

                        @can('admin_promotion_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/financial/promotions/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/financial/promotions/new">{{ trans('admin/main.new_plan') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_advertising')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/advertising*', false)) and !request()->is(getAdminPanelUrl('/advertising_modal*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-gallery class="icons" width="24px" height="24px"/>
                        <span>{{ trans('admin/main.ad_banners') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_advertising_banners')
                            <li class="{{ (request()->is(getAdminPanelUrl('/advertising/banners', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/advertising/banners">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan

                        @can('admin_advertising_banners_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/advertising/banners/new', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/advertising/banners/new">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_newsletters')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/newsletters*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-wifi-square class="icons" width="24px" height="24px"/>
                        <span>{{ trans('admin/main.newsletters') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_newsletters_lists')
                            <li class="{{ (request()->is(getAdminPanelUrl('/newsletters', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/newsletters">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan

                        @can('admin_newsletters_send')
                            <li class="{{ (request()->is(getAdminPanelUrl('/newsletters/send', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/newsletters/send">{{ trans('admin/main.send') }}</a>
                            </li>
                        @endcan

                        @can('admin_newsletters_history')
                            <li class="{{ (request()->is(getAdminPanelUrl('/newsletters/history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/newsletters/history">{{ trans('public.history') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_referrals')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/referrals*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-hierarchy-square-2 class="icons" width="24px" height="24px"/>
                        <span>{{ trans('panel.affiliate') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @can('admin_referrals_history')
                            <li class="{{ (request()->is(getAdminPanelUrl('/referrals/history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/referrals/history">{{ trans('public.history') }}</a>
                            </li>
                        @endcan

                        @can('admin_referrals_users')
                            <li class="{{ (request()->is(getAdminPanelUrl('/referrals/users', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl() }}/referrals/users">{{ trans('admin/main.affiliate_users') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('admin_registration_bonus')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/registration_bonus*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-box class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.registration_bonus') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                        @can('admin_registration_bonus_history')
                            <li class="{{ (request()->is(getAdminPanelUrl('/registration_bonus/history', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/registration_bonus/history') }}">{{ trans('update.bonus_history') }}</a>
                            </li>
                        @endcan


                        @can('admin_registration_bonus_settings')
                            <li class="{{ (request()->is(getAdminPanelUrl('/registration_bonus/settings', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/registration_bonus/settings') }}">{{ trans('admin/main.settings') }}</a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endcan

            @can('admin_advertising_modal_config')
                <li class="nav-item {{ (request()->is(getAdminPanelUrl('/advertising_modal*', false))) ? 'active' : '' }}">
                    <a href="{{ getAdminPanelUrl() }}/advertising_modal" class="nav-link">
                        <x-iconsax-bul-colors-square class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.advertising_modal') }}</span>
                    </a>
                </li>
            @endcan

            @can('admin_floating_bar_create')
                <li class="nav-item {{ (request()->is(getAdminPanelUrl('/floating_bars*', false))) ? 'active' : '' }}">
                    <a href="{{ getAdminPanelUrl() }}/floating_bars" class="nav-link">
                        <x-iconsax-bul-keyboard-open class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.top_bottom_bar') }}</span>
                    </a>
                </li>
            @endcan

            @can('admin_purchase_notifications')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/purchase_notifications*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-shopping-bag class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.purchase_notifications') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                    @can('admin_purchase_notifications_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/purchase_notifications/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/purchase_notifications/create') }}">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan

                        @can('admin_purchase_notifications_lists')
                            <li class="{{ (request()->is(getAdminPanelUrl('/purchase_notifications', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/purchase_notifications') }}">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endcan

            @can('admin_product_badges')
                <li class="nav-item dropdown {{ (request()->is(getAdminPanelUrl('/product-badges*', false))) ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <x-iconsax-bul-receipt class="icons" width="24px" height="24px"/>
                        <span>{{ trans('update.product_badges') }}</span>
                    </a>
                    <ul class="dropdown-menu">

                    @can('admin_product_badges_create')
                            <li class="{{ (request()->is(getAdminPanelUrl('/product-badges/create', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/product-badges/create') }}">{{ trans('admin/main.new') }}</a>
                            </li>
                        @endcan

                        @can('admin_product_badges_lists')
                            <li class="{{ (request()->is(getAdminPanelUrl('/product-badges', false))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ getAdminPanelUrl('/product-badges') }}">{{ trans('admin/main.list') }}</a>
                            </li>
                        @endcan

        </ul>
    </li>
@endcan
