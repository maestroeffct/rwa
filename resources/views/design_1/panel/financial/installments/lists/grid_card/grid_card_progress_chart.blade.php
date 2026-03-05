@if($order->isCompleted())
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-36 bg-success rounded-circle">
            <x-iconsax-bul-tick-circle class="icons text-white" width="36px" height="36px"/>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold">{{ trans('update.installment_completed') }}</h5>
            <p class="font-12 text-gray-500">{{ trans('update.you_paid_it_totally!') }}</p>
        </div>
    </div>

@else
    @php
        $percent = 20;
    @endphp

    <div class="d-flex align-items-center">
        <div class="js-installment-chart d-flex-center size-48" data-id="installmentChart_{{ $order->id }}" data-percent="{{ $percent }}">
            <canvas id="installmentChart_{{ $order->id }}" width="48" height="48"></canvas>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold">{{ ($order->selectedInstallment->steps_count - $order->remained_installments_count) }}/{{ $order->selectedInstallment->steps_count }}</h5>
            <p class="font-12 text-gray-500">{{ trans('update.installments_paid') }}</p>
        </div>
    </div>
@endif

