@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.installment_plans') }}
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">


                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ trans('update.installment_plans') }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                          

                            @can('admin_installments_create')
                                   <a href="{{ getAdminPanelUrl("/financial/installments/create") }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>


                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('update.sales_count') }}</th>
                                        <th class="text-center">{{ trans('update.upfront') }}</th>
                                        <th class="text-center">{{ trans('update.number_of_installments') }}</th>
                                        <th class="text-center">{{ trans('update.amount_of_installments') }}</th>
                                        <th class="text-center">{{ trans('admin/main.capacity') }}</th>
                                        <th class="text-center">{{ trans('admin/main.created_at') }}</th>
                                        <th class="text-center">{{ trans('admin/main.end_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($installments as $installment)
                                        <tr>
                                            <td>
                                                <div class="">
                                                    <span class="d-block font-14">{{ $installment->title }}</span>
                                                    <span class="d-block text-gray-500 font-12 mt-1">{{ trans('update.target_types_'.$installment->target_type) }}</span>
                                                </div>
                                            </td>

                                            <td class="text-center">{{ $installment->sales_count }}</td>

                                            <td class="text-center">
                                                {{ ($installment->upfront_type == 'percent') ? $installment->upfront.'%' : handlePrice($installment->upfront) }}
                                            </td>

                                            <td class="text-center">{{ $installment->steps_count }}</td>

                                            <td class="text-center">
                                                @php
                                                    $stepsFixedAmount = $installment->steps->where('amount_type', 'fixed_amount')->sum('amount');
                                                    $stepsPercents = $installment->steps->where('amount_type', 'percent')->sum('amount');
                                                @endphp

                                                <span class="">{{ $stepsFixedAmount ? handlePrice($stepsFixedAmount) : '' }}</span>

                                                @if($stepsPercents)
                                                    <span>{{ $stepsFixedAmount ? ' + ' : '' }}{{ $stepsPercents }}%</span>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ $installment->capacity ?? '' }}</td>

                                            <td class="text-center">{{ dateTimeFormat($installment->created_at, 'Y M j | H:i') }}</td>

                                            <td class="text-center">{{ $installment->end_date ? dateTimeFormat($installment->end_date, 'Y M j | H:i') : '-' }}</td>

                                            <td class="text-center">
                                                @if($installment->enable)
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
            @can('admin_promotion_edit')
                <a href="{{ getAdminPanelUrl("/financial/installments/{$installment->id}/edit") }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_promotion_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl('/financial/installments/'.$installment->id.'/delete'),
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
                            {{ $installments->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
