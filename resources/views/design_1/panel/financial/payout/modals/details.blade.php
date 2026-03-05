<div class="d-flex-center flex-column text-center">
    <img src="/assets/design_1/img/panel/payout/payout_request.svg" alt="payout_request" class="" width="154px" height="150px">

    <h5 class="font-14 font-weight-bold mt-16">{{ trans('update.review_payout_information') }}</h5>
    <p class="font-12 text-gray-500 mt-8">{{ trans('update.review_payout_information_hint') }}</p>
</div>

<div class="my-16 p-16 rounded-12 border-gray-200">
    <div class="d-flex align-items-center justify-content-between">
        <span class="text-gray-500">{{ trans('update.payout_amount') }}</span>
        <span class="">{{ handlePrice($payout->amount) }}</span>
    </div>

    @if(!empty($payout->userSelectedBank) and !empty($payout->userSelectedBank->bank))
        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="text-gray-500">{{ trans('financial.account') }}</span>
            <span class="">{{ $payout->userSelectedBank->bank->title }}</span>
        </div>

        @foreach($payout->userSelectedBank->bank->specifications as $specification)
            @php
                $selectedBankSpecification = $payout->userSelectedBank->specifications->where('user_selected_bank_id', $payout->userSelectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
            @endphp

            <div class="d-flex align-items-center justify-content-between mt-12">
                <span class="text-gray-500">{{ $specification->name }}</span>
                <span>{{ (!empty($selectedBankSpecification)) ? $selectedBankSpecification->value : '' }}</span>
            </div>
        @endforeach
    @endif
</div>
