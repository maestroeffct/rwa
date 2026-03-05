<tr>
    <td>
        <div class="text-left">
            @if(!empty($payout->userSelectedBank->bank))
                <span class="d-block">{{ $payout->userSelectedBank->bank->title }}</span>
            @else
                <span class="d-block">-</span>
            @endif
            <span class="d-block font-12 text-gray-500 mt-4">{{ dateTimeFormat($payout->created_at, 'j M Y | H:i') }}</span>
        </div>
    </td>

    <td class="text-center">
        <span>{{ trans('public.manual') }}</span>
    </td>

    <td class="text-center">
        <span class="text-primary font-weight-bold">{{ handlePrice($payout->amount) }}</span>
    </td>

    <td class="text-center">
        @switch($payout->status)
            @case(\App\Models\Payout::$waiting)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-warning-20 text-warning">{{ trans('public.waiting') }}</span>
                @break;
            @case(\App\Models\Payout::$reject)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-danger-20 text-danger">{{ trans('public.rejected') }}</span>
                @break;
            @case(\App\Models\Payout::$done)
                <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 bg-success-20 text-success">{{ trans('public.done') }}</span>
                @break;
        @endswitch
    </td>

    <td class="text-center">
        {{-- For Modal --}}
        @if(!empty($payout->userSelectedBank->bank))
            @php
                $bank = $payout->userSelectedBank->bank;
            @endphp
        @endif

        @if(!empty($bank->title))
            <input type="hidden" class="js-bank-details" data-name="{{ trans("admin/main.bank") }}" value="{{ $bank->title }}">

            @foreach($bank->specifications as $specification)
                @php
                    $selectedBankSpecification = $payout->userSelectedBank->specifications->where('user_selected_bank_id', $payout->userSelectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                @endphp

                @if(!empty($selectedBankSpecification))
                    <input type="hidden" class="js-bank-details" data-name="{{ $specification->name }}" value="{{ $selectedBankSpecification->value }}">
                @endif
            @endforeach
        @endif


        <button type="button" class="js-show-details btn-transparent btn-sm" data-path="/panel/financial/payout/{{ $payout->id }}/details" data-tippy-content="{{ trans('update.show_details') }}">
            <x-iconsax-lin-eye class="icons text-dark" width="20px" height="20px"/>
        </button>
    </td>
</tr>
