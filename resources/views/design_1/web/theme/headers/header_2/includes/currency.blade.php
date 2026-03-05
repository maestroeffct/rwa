@if(!empty($currencies) and count($currencies))
    @php
        $userCurrency = currency();
    @endphp

    <div class="js-currency-select theme-header-2__dropdown position-relative">
        <form action="/set-currency" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="currency" value="{{ $userCurrency }}">

            @foreach($currencies as $currencyItem)
                @if($userCurrency == $currencyItem->currency)
                    <div class="d-flex align-items-center gap-8">
                        <div class="size-32 d-flex-center bg-gray-100 rounded-8">
                            <span class="font-12 text-gray-500">{{ currencySign($currencyItem->currency) }}</span>
                        </div>
                        <span class="js-lang-title text-gray-500 d-none d-md-flex">{{ $currencyItem->currency }}</span>
                        <x-iconsax-lin-arrow-down class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                @endif
            @endforeach
        </form>

        <div class="header-2-dropdown-menu py-8">

            <div class="py-8 px-16 font-12 text-gray-500">{{ trans('update.select_a_currency') }}</div>

            @foreach($currencies as $currencyItem)
                <div class="js-currency-dropdown-item header-2-dropdown-menu__item cursor-pointer {{ ($userCurrency == $currencyItem->currency) ? 'active' : '' }}" data-value="{{ $currencyItem->currency }}" data-title="{{ $currencyItem->currency }}">
                    <div class=" d-flex align-items-center justify-content-between w-100 px-16 py-8 bg-transparent">
                        <span class="text-gray-500 text-dark">{{ currenciesLists($currencyItem->currency) }}</span>

                        <div class="header-2-dropdown-menu__item-sign-box position-relative d-flex-center rounded-8">
                            {{ currencySign($currencyItem->currency) }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endif
