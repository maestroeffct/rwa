<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Web\CartManagerController;
use App\Mixins\Financial\MultiCurrency;
use App\Mixins\PurchaseNotifications\PurchaseNotificationsHelper;
use App\Models\Cart;
use App\Models\CartDiscount;
use App\Models\Currency;
use App\Models\FloatingBar;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;

class Share
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $this->getShareData($request);

        foreach ($data as $key => $value) {
            view()->share($key, $value);
        }

        return $next($request);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function getShareData($request): array
    {
        $data = [];

        $agent = new Agent();
        $userDeviceType = ($agent->deviceType() == "phone") ? "mobile" : "desktop";
        $data['userDeviceType'] = $userDeviceType;

        if ($userDeviceType == "desktop") { // Show Notifications Just in Desktop
            $purchaseNotificationsHelper = new PurchaseNotificationsHelper();
            $purchaseNotifications = $purchaseNotificationsHelper->getDisplayableNotifications();
            $data['purchaseNotifications'] = $purchaseNotifications;
        }

        if (auth()->check()) {
            $user = auth()->user();
            $data['authUser'] = $user;

            if (!$user->isAdmin()) {

                $unReadNotifications = $user->getUnReadNotifications();

                $data['unReadNotifications'] = $unReadNotifications;
            }
        }

        $cartManagerController = new CartManagerController();
        $carts = $cartManagerController->getCarts();
        $totalCartsPrice = Cart::getCartsTotalPrice($carts);

        $data['userCarts'] = $carts;
        $data['totalCartsPrice'] = $totalCartsPrice;
        $data['userCartCount'] = count($carts);

        $cartDiscount = CartDiscount::query()->where('enable', true)->count();
        $data['userCartDiscount'] = $cartDiscount;

        $generalSettings = getGeneralSettings();
        $data['generalSettings'] = $generalSettings;


        $currency = currencySign();
        $data['currency'] = $currency;

        if (getFinancialCurrencySettings('multi_currency')) {
            $multiCurrency = new MultiCurrency();
            $currencies = $multiCurrency->getCurrencies();

            if ($currencies->isNotEmpty()) {
                $data['currencies'] = $currencies;
            }
        }


        // locale config
        if (!Session::has('locale')) {
            Session::put('locale', mb_strtolower(getDefaultLocale()));
        }
        App::setLocale(session('locale'));

        $data['categories'] = \App\Models\Category::getCategories();


        if (!$request->is("course/learning*")) {
            $floatingBar = FloatingBar::getFloatingBar($request);
            $data['floatingBar'] = $floatingBar;
        }

        $userTimezone = getTimezone();
        config()->set('app.timezone', $userTimezone);


        // Theme Color Mode
        $data['userThemeColorMode'] = getUserThemeColorMode();

        // Theme Header
        $data['themeHeaderData'] = getThemeHeaderData($userDeviceType);

        // Theme Header
        $data['themeFooterData'] = getThemeFooterData();

        return $data;
    }
}
