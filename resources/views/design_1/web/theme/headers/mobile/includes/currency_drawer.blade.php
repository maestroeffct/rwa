<div class="js-currency-select">
    <form action="/set-currency" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="currency" value="{{ $userCurrency }}">

    </form>

    <div class="theme-header-mobile__drawer mobile-currency-drawer">
        <div class="theme-header-mobile__drawer-back-drop"></div>

        <div class="theme-header-mobile__drawer-body py-16">
            <div class="d-flex align-items-center justify-content-between px-16 mb-12">
                <h4 class="font-22">{{ trans('update.select_a_currency') }}</h4>

                <div class="js-close-header-drawer d-flex-center size-48 rounded-12 border-gray-300">
                    <x-iconsax-lin-add class="close-icon text-gray-500" width="24px" height="24px"/>
                </div>
            </div>

            @foreach($currencies as $currencyItem)
                <div class="js-currency-dropdown-item d-flex align-items-center justify-content-between w-100 px-16 py-8 cursor-pointer {{ ($userCurrency == $currencyItem->currency) ? 'bg-gray-100 font-weight-bold' : '' }}" data-value="{{ $currencyItem->currency }}" data-title="{{ $currencyItem->currency }}">
                    <span class="text-gray-500 text-dark">{{ currenciesLists($currencyItem->currency) }}</span>

                    <div class="position-relative d-flex-center p-8 bg-gray-200 rounded-8 text-gray-500">
                        {{ currencySign($currencyItem->currency) }}
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
