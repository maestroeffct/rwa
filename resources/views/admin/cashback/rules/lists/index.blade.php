@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.cashback_rules') }}
                </div>
            </div>
        </div>

        {{-- Stats --}}
        @include('admin.cashback.rules.lists.stats')

        {{-- Filters --}}
        @include('admin.cashback.rules.lists.filters')

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                    <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_cashback_rules')
                                   <a href="{{ getAdminPanelUrl("/cashback/rules/new") }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                   </a>
                               @endcan

                            </div>
                            </div>

                        <div class="card-body">
                            <div>
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('update.target_type') }}</th>
                                        <th class="text-center">{{ trans('admin/main.amount') }}</th>
                                        <th class="text-center">{{ trans('public.paid_amount') }}</th>
                                        <th class="text-center">{{ trans('admin/main.users') }}</th>
                                        <th class="text-center">{{ trans('admin/main.start_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.end_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($rules as $rule)
                                        <tr>
                                            <td>
                                                <span class="d-block font-16 font-weight-500">{{ $rule->title }}</span>
                                            </td>

                                            <td>
                                                <span class="">{{ trans('update.target_types_'.$rule->target_type) }}</span>
                                            </td>

                                            <td class="text-center">
                                                {{ ($rule->amount_type == 'percent') ? $rule->amount.'%' : handlePrice($rule->amount) }}
                                            </td>

                                            <td class="text-center">0</td>

                                            <td class="text-center">0</td>

                                            <td class="text-center">{{ $rule->start_date ? dateTimeFormat($rule->start_date, 'Y M j | H:i') : '-' }}</td>

                                            <td class="text-center">{{ $rule->end_date ? dateTimeFormat($rule->end_date, 'Y M j | H:i') : trans('update.unlimited') }}</td>

                                            <td class="text-center">
                                                @if($rule->enable)
                                                    <span class="badge-status text-success bg-success-30">{{ trans('admin/main.active') }}</span>
                                                @else
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_cashback_rules')
                <a href="{{ getAdminPanelUrl("/cashback/rules/{$rule->id}/edit") }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_cashback_rules')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl('/cashback/rules/'. $rule->id.'/statusToggle'),
                    'btnClass' => 'dropdown-item text-' . ($rule->enable ? 'danger' : 'success') . ' mb-3 py-3 px-0 font-14',
                    'btnText' => $rule->enable ? trans('admin/main.inactive') : trans('admin/main.active'),
                    'btnIcon' => $rule->enable ? 'close-square' : 'tick-square',
                    'iconType' => 'lin',
                    'iconClass' => 'text-' . ($rule->enable ? 'danger' : 'success') . ' mr-2',
                ])
            @endcan

            @can('admin_cashback_rules')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl('/cashback/rules/'. $rule->id.'/delete'),
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans('admin/main.delete'),
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
            </div>
        </div>
    </section>
@endsection
