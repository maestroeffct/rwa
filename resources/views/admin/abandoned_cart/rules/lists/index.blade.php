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
            @include("admin.abandoned_cart.rules.lists.top_stats")

            <div class="card mt-20">

                <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_abandoned_cart_rules')
                                   <a href="{{ getAdminPanelUrl("/abandoned-cart/rules/create") }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.title') }}</th>
                                <th class="text-center">{{ trans('update.activities') }}</th>
                                <th class="text-center">{{ trans('admin/main.sales') }}</th>
                                <th class="text-center">{{ trans('update.minimum_amount') }}</th>
                                <th class="text-center">{{ trans('update.maximum_amount') }}</th>
                                <th class="text-center">{{ trans('admin/main.action') }}</th>
                                <th class="text-center">{{ trans('admin/main.start_date') }}</th>
                                <th class="text-center">{{ trans('update.expire_date') }}</th>
                                <th class="text-center">{{ trans('admin/main.status') }}</th>
                                <th>{{ trans('public.controls') }}</th>
                            </tr>

                            @foreach($rules as $rule)
                                <tr>
                                    <td>{{ $rule->title }}</td>

                                    <td class="text-center">0</td>

                                    <td class="text-center">0</td>

                                    <td class="text-center">
                                        @if(!empty($rule->minimum_cart_amount))
                                            {{ handlePrice($rule->minimum_cart_amount) }}
                                        @else
                                            {{ trans('update.unlimited') }}
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if(!empty($rule->maximum_cart_amount))
                                            {{ handlePrice($rule->maximum_cart_amount) }}
                                        @else
                                            {{ trans('update.unlimited') }}
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{ trans('update.'.$rule->action) }}
                                    </td>

                                    <td class="text-center">
                                        @if(!empty($rule->start_at))
                                            {{ dateTimeFormat($rule->start_at, 'j M Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if(!empty($rule->end_at))
                                            {{ dateTimeFormat($rule->end_at, 'j M Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>


                                    <td class="text-center">
                                        @if(!$rule->enable)
                                            <span class="text-danger">{{ trans('admin/main.disabled') }}</span>
                                        @elseif(!empty($rule->start_at) and $rule->start_at > time())
                                            <span class="text-warning">{{ trans('admin/main.pending') }}</span>
                                        @elseif(!empty($rule->end_at) and $rule->end_at < time())
                                            <span class="text-danger">{{ trans('panel.expired') }}</span>
                                        @else
                                            <span class="text-success">{{ trans('admin/main.active') }}</span>
                                        @endif
                                    </td>

                                    <td width="100">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_abandoned_cart_rules')
                <a href="{{ getAdminPanelUrl("/abandoned-cart/rules/{$rule->id}/edit") }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_abandoned_cart_rules')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl("/abandoned-cart/rules/{$rule->id}/delete"),
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans("admin/main.delete"),
                    'btnIcon' => 'trash',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2',
                ])
            @endcan
        </div>
    </div>
</td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $rules->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>


@endsection
