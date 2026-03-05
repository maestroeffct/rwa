<?php

namespace App\Mixins\Cart;

use App\Models\Cart;

class ClearAbandonedCartItems
{

    public function clearItems()
    {
        $settings = getAbandonedCartSettings();

        if (!empty($settings["reset_cart_items"])) {
            $hours = $settings["reset_hours"] ?? 1;
            $operation = time() - ($hours * 3600);

            $carts = Cart::query()
                ->where('created_at' ,"<", $operation)
                ->get();

            if ($carts->isNotEmpty()) {
                foreach ($carts as $cart) {
                    // ..
                    $cart->delete();
                }
            }
        }

    }
}
