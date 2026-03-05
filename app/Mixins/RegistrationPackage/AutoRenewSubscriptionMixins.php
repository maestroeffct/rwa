<?php

namespace App\Mixins\RegistrationPackage;

use App\Models\Accounting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sale;

class AutoRenewSubscriptionMixins
{


    /**
     * @description  Renewal of plans that expire today.
     * */
    public function renewSubscriptionsPlans()
    {
        $time = time();
        $startDate = startOfDayTimestamp($time);
        $endDate = endOfDayTimestamp($time);

        $subscribesSales = $this->getSubscribePlansByDate($startDate, $endDate);
        $registrationPackagesSales = $this->getRegistrationPlansByDate($startDate, $endDate);

        // Subscribe
        foreach ($subscribesSales as $subscribesSale) {
            $this->handleRenewSubscribePlan($subscribesSale);
        }

        // Registration Packages
        foreach ($registrationPackagesSales as $registrationPackageSale) {
            $this->handleRenewRegistrationPlan($registrationPackageSale);
        }
    }

    private function handleRenewSubscribePlan(Sale $sale)
    {
        $subscribe = $sale->subscribe;
        $user = $sale->buyer;

        $subscribeAmount = $subscribe->getPrice();
        $subscribeAmount = $subscribeAmount > 0 ? $subscribeAmount : 0;

        $this->handleOrderAndSaleAndAccounting($user, $subscribe, "subscribe", $subscribeAmount, "subscribe_id");

    }

    private function handleRenewRegistrationPlan(Sale $sale)
    {
        $registrationPackage = $sale->registrationPackage;
        $user = $sale->buyer;

        $amount = $registrationPackage->getPrice();
        $amount = $amount > 0 ? $amount : 0;

        $this->handleOrderAndSaleAndAccounting($user, $registrationPackage, "registration_package", $amount, "registration_package_id");
    }


    private function handleOrderAndSaleAndAccounting($user, $item, $itemType, $amount, $column)
    {
        $accountingCharge = $user->getAccountingCharge();

        if ($amount > $accountingCharge) {
            // not Enough money

            $notifyOptions = [
                '[plan_title]' => $item->title,
                '[plan_type]' => $itemType,
                '[time.date]' => dateTimeFormat(time(), 'j M Y H:i'),
            ];
            sendNotification('plan_not_renewed_due_to_lack_of_wallet_balance', $notifyOptions, $user->id);

        } else {
            $financialSettings = getFinancialSettings();
            $tax = $financialSettings['tax'] ?? 0;

            $taxPrice = $tax ? $amount * $tax / 100 : 0;

            $order = Order::create([
                "user_id" => $user->id,
                "status" => Order::$pending,
                'tax' => $taxPrice,
                'commission' => 0,
                "amount" => $amount,
                "total_amount" => $amount + $taxPrice,
                "created_at" => time(),
            ]);

            $orderItem = OrderItem::updateOrCreate([
                'user_id' => $user->id,
                'order_id' => $order->id,
                "{$column}" => $item->id,
            ], [
                'amount' => $order->amount,
                'total_amount' => $amount + $taxPrice,
                'tax' => $tax,
                'tax_price' => $taxPrice,
                'commission' => 0,
                'commission_price' => 0,
                'created_at' => time(),
            ]);

            Sale::createSales($orderItem, Sale::$credit);

            if ($itemType == "registration_package") {
                Accounting::createAccountingForRegistrationPackage($orderItem, 'credit');
            } else if ($itemType == "subscribe") {
                Accounting::createAccountingForSubscribe($orderItem, 'credit');
            }

            $notifyOptions = [
                '[plan_title]' => $item->title,
                '[plan_type]' => $itemType,
                '[time.date]' => dateTimeFormat(time(), 'j M Y H:i'),
            ];
            sendNotification('auto_renewal_plan_with_wallet_balance', $notifyOptions, $user->id);
        }
    }


    private function getSubscribePlansByDate($startDate, $endDate)
    {
        $items = collect();
        $subscribeSales = Sale::where('type', Sale::$subscribe)
            ->whereNull('refund_at')
            ->with([
                'subscribe'
            ])
            ->get();

        foreach ($subscribeSales as $sale) {
            if (!empty($sale->subscribe)) {
                $subscribe = $sale->subscribe;
                $expireDate = $sale->created_at + ($subscribe->days * 24 * 60 * 60);

                if ($expireDate >= $startDate and $expireDate <= $endDate) {
                    $items->push($sale);
                }
            }
        }

        return $items;
    }

    private function getRegistrationPlansByDate($startDate, $endDate)
    {
        $items = collect();
        $registrationPackageSales = Sale::where('type', Sale::$registrationPackage)
            ->whereNotNull('registration_package_id')
            ->whereNull('refund_at')
            ->with([
                'registrationPackage'
            ])
            ->get();

        foreach ($registrationPackageSales as $sale) {
            if (!empty($sale->registrationPackage)) {
                $registrationPackage = $sale->registrationPackage;
                $expireDate = $sale->created_at + ($registrationPackage->days * 24 * 60 * 60);

                if ($expireDate >= $startDate and $expireDate <= $endDate) {
                    $items->push($sale);
                }
            }
        }

        return $items;
    }


    /**/
    public function reminderBeforeExpiration()
    {
        $hours = getRemindersSettings("notification_before_subscription_plan_expires");

        if (!empty($hours) and $hours > 0) {
            $startDate = time();
            $endDate = $startDate + ($hours * 60 * 60);

            $subscribesSales = $this->getSubscribePlansByDate($startDate, $endDate);
            $registrationPackagesSales = $this->getRegistrationPlansByDate($startDate, $endDate);

            // Subscribe
            foreach ($subscribesSales as $subscribesSale) {
                $this->sendReminderBeforeExpire($subscribesSale, "subscribe");
            }

            // Registration Packages
            foreach ($registrationPackagesSales as $registrationPackageSale) {
                $this->sendReminderBeforeExpire($registrationPackageSale, "registration_package");
            }
        }
    }

    private function sendReminderBeforeExpire(Sale $sale, $itemType)
    {
        $user = $sale->buyer;

        if ($itemType == "subscribe") {
            $item = $sale->subscribe;
        } else {
            $item = $sale->registrationPackage;
        }

        if (!empty($user) and !empty($item)) {
            $expireDate = $sale->created_at + ($item->days * 24 * 60 * 60);

            $notifyOptions = [
                '[plan_title]' => $item->title,
                '[plan_type]' => $itemType,
                '[time.date]' => dateTimeFormat($expireDate, 'j M Y H:i'),
            ];
            sendNotification('reminder_before_expiration_subscribes', $notifyOptions, $user->id);
        }
    }

}
