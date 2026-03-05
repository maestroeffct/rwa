@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            {{-- Stats --}}
            @include("admin.abandoned_cart.users_carts.top_stats")

            {{-- Filters --}}
            @include("admin.abandoned_cart.users_carts.filters")

            <div class="card">

                    <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            <a href="{{ getAdminPanelUrl("/abandoned-cart/users-carts?excel=1") }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                            <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                            <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                            </a>
                          
                            </div>
                           
                       </div>

                <div class="card-body">
                    <div>
                        <table class="table custom-table font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.user') }}</th>
                                <th class="text-center">{{ trans('public.user_role') }}</th>
                                <th class="text-center">{{ trans('cart.cart_items') }}</th>
                                <th class="text-center">{{ trans('admin/main.amount') }}</th>
                                <th class="text-center">{{ trans('update.coupons') }}</th>
                                <th class="text-center">{{ trans('update.reminders') }}</th>
                                <th>{{ trans('public.controls') }}</th>
                            </tr>

                            @foreach($carts as $cart)
                                <tr>
                                    <td>{{ $cart->user->full_name }}</td>

                                    <td class="text-center">{{ trans('admin/main.'.$cart->user->role_name) }}</td>

                                    <td class="text-center">{{ $cart->total_items }}</td>

                                    <td class="text-center">{{ handlePrice($cart->total_amount) }}</td>

                                    <td class="text-center">
                                        {{ $cart->send_coupons }}
                                    </td>

                                    <td class="text-center">
                                        {{ $cart->send_reminders }}
                                    </td>

                                    <td width="100">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ getAdminPanelUrl("/abandoned-cart/users-carts/{$cart->creator_id}/view-items") }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                <span class="text-gray-500 font-14">{{ trans('update.view_items') }}</span>
            </a>

            @include('admin.includes.delete_button', [
                'url' => getAdminPanelUrl("/abandoned-cart/users-carts/{$cart->creator_id}/send-reminder"),
                'btnClass' => 'dropdown-item text-primary mb-3 py-3 px-0 font-14',
                'btnText' => trans('admin/main.send_reminder'),
                'btnIcon' => 'notification-bing',
                'iconType' => 'lin',
                'iconClass' => 'text-primary mr-2',
            ])

            @can('admin_users_impersonate')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $cart->creator_id }}/impersonate" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                </a>
            @endcan

            @can('admin_users_edit')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $cart->creator_id }}/edit" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('update.edit_user') }}</span>
                </a>
            @endcan

            @include('admin.includes.delete_button', [
                'url' => getAdminPanelUrl("/abandoned-cart/users-carts/{$cart->creator_id}/empty"),
                'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                'btnText' => trans('update.empty_cart'),
                'btnIcon' => 'trash',
                'iconType' => 'lin',
                'iconClass' => 'text-danger mr-2',
            ])
        </div>
    </div>
</td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $carts->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection
