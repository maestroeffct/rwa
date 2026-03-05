@php
    $lastStepDueDate = null;

    if (!empty($installment->steps) and count($installment->steps)) {
        $lastStep = $installment->steps->last();
        $lastStepDueDate = ($lastStep->deadline * 86400) + $order->created_at;
    }
@endphp

<div class="bg-white rounded-16 p-16">
    <h1 class="font-14 font-weight-bold">“{{ $order->getItem()->title }}” {{ trans('update.installments_details') }}</h1>

    <div class="row pb-8">

        <div class="col-6 col-md-4 col-lg-2 mt-24">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-gray-100 rounded-12">
                    <x-iconsax-lin-calendar-add class="icons text-primary" width="24px" height="24px"/>
                </div>

                <div class="ml-8">
                    <span class="d-block font-16 font-weight-bold">{{ dateTimeFormat($order->created_at, 'j M Y') }}</span>
                    <span class="d-block font-14 text-gray-500 mt-8">{{ trans('panel.purchase_date') }}</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2 mt-24">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-gray-100 rounded-12">
                    <x-iconsax-lin-calendar-tick class="icons text-primary" width="24px" height="24px"/>
                </div>

                <div class="ml-8">
                    <span class="d-block font-16 font-weight-bold">{{ !empty($lastStepDueDate) ? dateTimeFormat($lastStepDueDate, 'j M Y') : '-' }}</span>
                    <span class="d-block font-14 text-gray-500 mt-8">{{ trans('webinars.end_date') }}</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2 mt-24">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-gray-100 rounded-12">
                    <x-iconsax-lin-money-time class="icons text-primary" width="24px" height="24px"/>
                </div>

                <div class="ml-8">
                    <span class="d-block font-16 font-weight-bold">{{ $remainedParts }} ({{ handlePrice($remainedAmount) }})</span>
                    <span class="d-block font-14 text-gray-500 mt-8">{{ trans('update.remaining_parts') }}</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2 mt-24">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-gray-100 rounded-12">
                    <x-iconsax-lin-card-tick class="icons text-primary" width="24px" height="24px"/>
                </div>

                <div class="ml-8">
                    <span class="d-block font-16 font-weight-bold">{{ count($paidParts) }} ({{ handlePrice($paidParts->sum('amount')) }})</span>
                    <span class="d-block font-14 text-gray-500 mt-8">{{ trans('update.paid_parts') }}</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2 mt-24">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-gray-100 rounded-12">
                    <x-iconsax-lin-money-remove class="icons text-primary" width="24px" height="24px"/>
                </div>

                <div class="ml-8">
                    <span class="d-block font-16 font-weight-bold">{{ $overdueParts }} ({{ handlePrice($overdueAmount) }})</span>
                    <span class="d-block font-14 text-gray-500 mt-8">{{ trans('update.overdue_parts') }}</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2 mt-24">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-gray-100 rounded-12">
                    <x-iconsax-lin-moneys class="icons text-primary" width="24px" height="24px"/>
                </div>

                <div class="ml-8">
                    <span class="d-block font-16 font-weight-bold">{{ handlePrice($order->getCompletePrice()) }}</span>
                    <span class="d-block font-14 text-gray-500 mt-8">{{ trans('financial.total_amount') }}</span>
                </div>
            </div>
        </div>

    </div>
</div>
