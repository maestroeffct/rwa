@if(!empty($currencies) and count($currencies))
    @php
        $userCurrency = currency();
    @endphp

    <div class="js-currency-select language-select position-relative {{ !empty($currencyClassName) ? $currencyClassName : '' }}">
        <form action="/set-currency" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="currency" value="{{ $userCurrency }}">

            @foreach($currencies as $currencyItem)
                @if($userCurrency == $currencyItem->currency)
                    <div class="size-32 d-flex-center bg-gray-100 rounded-8 p-4 text-gray-500 font-12">{{ $currencyItem->currency }}</div>
                @endif
            @endforeach
        </form>

        <div class="language-dropdown py-8 ">
            <div class="py-8 px-16 font-12 text-gray-500">{{ trans('update.select_a_currency') }}</div>

            @foreach($currencies as $currencyItem)
                <div class="js-currency-dropdown-item language-dropdown__item cursor-pointer {{ ($userCurrency == $currencyItem->currency) ? 'active' : '' }}" data-value="{{ $currencyItem->currency }}" data-title="{{ $currencyItem->currency }}">
                    <div class=" d-flex align-items-center justify-content-between w-100 px-16 py-8 text-dark">
                        <span class="ml-8 font-14">{{ currenciesLists($currencyItem->currency) }}</span>

                        <div class="language-dropdown__item-sign-box position-relative d-flex-center rounded-8">
                            {{ currencySign($currencyItem->currency) }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endif
