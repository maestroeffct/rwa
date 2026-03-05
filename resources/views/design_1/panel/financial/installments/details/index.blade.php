@extends('design_1.panel.layouts.panel')

@section('content')
     {{-- Top State --}}
    @include('design_1.panel.financial.installments.details.top_stats')


     <div class="bg-white pt-16 rounded-24 mt-20">

         <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
             <div class="">
                 <h3 class="font-16">{{ trans('update.installments_list') }}</h3>
                 <p class="mt-4 text-gray-500">{{ trans('update.View_and_manage_installments_in_detail') }}</p>
             </div>
         </div>


         {{-- List Table --}}
         <div class="table-responsive-lg mt-24">
             <table class="table panel-table">
                 <thead>
                 <tr>
                     <th class="text-left">{{ trans('public.title') }}</th>
                     <th class="text-center">{{ trans('panel.amount') }}</th>
                     <th class="text-center">{{ trans('update.due_date') }}</th>
                     <th class="text-center">{{ trans('update.payment_date') }}</th>
                     <th class="text-center">{{ trans('public.status') }}</th>
                     <th class="text-right">{{ trans('public.controls') }}</th>
                 </tr>
                 </thead>
                 <tbody class="">

                 @if(!empty($installment->upfront))
                     @php
                         $upfrontPayment = $payments->where('type','upfront')->first();
                     @endphp
                     <tr>
                         <td class="text-left">
                             <div class="d-flex align-items-center">
                                 <div class="d-flex-center size-64 rounded-12 border-dashed border-primary">
                                     <x-iconsax-bol-card-tick class="icons text-primary" width="24px" height="24px"/>
                                 </div>

                                 <div class="ml-16">
                                     <div class="">
                                         <span class="">{{ trans('update.upfront') }}</span>

                                         @if($installment->upfront_type == 'percent')
                                             <span class="ml-4">({{ $installment->upfront }}%)</span>
                                         @endif
                                     </div>

                                     <span class="d-block font-12 text-gray-500 mt-2">{{ trans('update.prepaid') }}</span>
                                 </div>
                             </div>
                         </td>

                         <td class="text-center">{{ handlePrice($installment->getUpfront($itemPrice)) }}</td>

                         <td class="text-center">-</td>

                         <td class="text-center">{{ !empty($upfrontPayment) ? dateTimeFormat($upfrontPayment->created_at, 'j M Y H:i') : '-' }}</td>

                         <td class="text-center">
                             @if(!empty($upfrontPayment))
                                 <span class="text-primary">{{ trans('public.paid') }}</span>
                             @else
                                 <span class="text-dark-blue">{{ trans('update.unpaid') }}</span>
                             @endif
                         </td>

                         <td class="text-right">

                         </td>
                     </tr>
                 @endif

                 @foreach($installment->steps as $step)

                     @php
                         $stepPayment = $payments->where('selected_installment_step_id', $step->id)->where('status', 'paid')->first();
                         $dueAt = ($step->deadline * 86400) + $order->created_at;
                         $isOverdue = ($dueAt < time() and empty($stepPayment));
                     @endphp

                     <tr>
                         <td class="text-left">
                             <div class="d-flex align-items-center">
                                 <div class="d-flex-center size-64 rounded-12 border-dashed border-gray-200">
                                     <x-iconsax-bol-graph class="icons text-gray-400" width="24px" height="24px"/>
                                 </div>

                                 <div class="ml-16">
                                     <div class="">
                                         <span class="">{{ $step->title }}</span>

                                         @if($step->amount_type == 'percent')
                                             <span class="ml-4 font-12 text-gray-500">({{ $step->amount }}%)</span>
                                         @endif
                                     </div>

                                     <span class="d-block mt-4 font-12 text-gray-500">{{ trans('update.n_days_after_purchase', ['days' => $step->deadline]) }}</span>
                                 </div>
                             </div>
                         </td>

                         <td class="text-center">{{ handlePrice($step->getPrice($itemPrice)) }}</td>

                         <td class="text-center">
                             <span class="{{ $isOverdue ? 'text-danger' : '' }}">{{ dateTimeFormat($dueAt, 'j M Y') }}</span>
                         </td>

                         <td class="text-center">{{ !empty($stepPayment) ? dateTimeFormat($stepPayment->created_at, 'j M Y H:i') : '-' }}</td>

                         <td class="text-center">
                             @if(!empty($stepPayment))
                                 <span class="text-primary">{{ trans('public.paid') }}</span>
                             @else
                                 <span class="{{ $isOverdue ? 'text-danger' : 'text-dark-blue' }}">{{ trans('update.unpaid') }} {{ $isOverdue ? "(". trans('update.overdue') .")" : '' }}</span>
                             @endif
                         </td>
                         <td class="text-right">
                             @if(empty($stepPayment))

                                 <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                                     <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                                         <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                                     </button>

                                     <div class="actions-dropdown__dropdown-menu dropdown-menu-top-32">
                                         <ul class="my-8">

                                             <li class="actions-dropdown__dropdown-menu-item">
                                                 <a href="/panel/financial/installments/{{ $order->id }}/steps/{{ $step->id }}/pay" target="_blank" class="">{{ trans('panel.pay') }}</a>
                                             </li>

                                         </ul>
                                     </div>
                                 </div>
                             @endif
                         </td>
                     </tr>
                 @endforeach


                 </tbody>
             </table>

             {{-- Pagination --}}


         </div>

     </div>

@endsection
