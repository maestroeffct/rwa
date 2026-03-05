<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\PaymentsTrait;
use App\Mixins\Cashback\CashbackAccounting;
use App\Mixins\Events\EventTicketSoldMixins;
use App\Mixins\MeetingPackages\MeetingPackageSoldMixins;
use App\Models\Accounting;
use App\Models\BecomeInstructor;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\TicketUser;
use App\PaymentChannels\ChannelManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    use PaymentsTrait;


    protected $order_session_key = 'payment.order_id';

    public function paymentRequest(Request $request)
    {
        $this->validate($request, [
            'gateway' => 'required'
        ]);

        $user = auth()->user();
        $gateway = $request->input('gateway');
        $orderId = $request->input('order_id');

        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if ($order->type === Order::$meeting) {
            $orderItem = OrderItem::where('order_id', $order->id)->first();
            $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
            $reserveMeeting->update(['locked_at' => time()]);
        }

        if ($gateway === 'credit') {

            if ($user->getAccountingCharge() < $order->total_amount) {
                $order->update(['status' => Order::$fail]);

                session()->put($this->order_session_key, $order->id);

                return redirect('/payments/status');
            }

            $order->update([
                'payment_method' => Order::$credit
            ]);

            $this->setPaymentAccounting($order, 'credit');

            $order->update([
                'status' => Order::$paid
            ]);

            session()->put($this->order_session_key, $order->id);

            return redirect('/payments/status');
        }

        // Offline payment for cart checkout
        if ($gateway === 'offline') {
            // Validate offline settings enabled and create offline payment request for this order
            if (empty(getOfflineBankSettings('offline_banks_status'))) {
                $toastData = [
                    'title' => trans('cart.fail_purchase'),
                    'msg' => trans('public.channel_payment_disabled'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }

            // Required inputs: account, referral_code, date, attachment (optional)
            $this->validate($request, [
                'account' => 'required',
                'referral_code' => 'required',
                'date' => 'required',
                'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:10240'
            ]);

            $attachment = null;
            if (!empty($request->file('attachment'))) {
                // Reuse AccountingController upload helper behavior
                $path = 'financial/offlinePayments';
                $attachment = (new \App\Http\Controllers\Panel\AccountingController())->uploadFile($request->file('attachment'), $path, null, $user->id);
            }

            $date = convertTimeToUTCzone($request->input('date'), getTimezone());

            \App\Models\OfflinePayment::create([
                'user_id' => $user->id,
                'amount' => $order->total_amount,
                'offline_bank_id' => $request->input('account'),
                'reference_number' => $request->input('referral_code'),
                'status' => \App\Models\OfflinePayment::$waiting,
                'pay_date' => $date->getTimestamp(),
                'attachment' => $attachment,
                'created_at' => time(),
                'type' => \App\Models\OfflinePayment::$typeCart,
                'order_id' => $order->id,
            ]);

            $order->update([
                'status' => \App\Models\Order::$paying,
                'payment_method' => \App\Models\Order::$paymentChannel,
            ]);

            // Empty user cart after submitting offline payment
            \App\Models\Cart::emptyCart($user->id);

            $notifyOptions = [
                '[amount]' => handlePrice($order->total_amount),
                '[u.name]' => $user->full_name
            ];
            sendNotification('offline_payment_request', $notifyOptions, $user->id);
            sendNotification('new_offline_payment_request', $notifyOptions, 1);

            session()->put($this->order_session_key, $order->id);

            return redirect('/payments/status');
        }

        $paymentChannel = PaymentChannel::where('id', $gateway)
            ->where('status', 'active')
            ->first();

        if (!$paymentChannel) {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('public.channel_payment_disabled'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $order->payment_method = Order::$paymentChannel;
        $order->save();


        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            $redirect_url = $channelManager->paymentRequest($order);

            if (in_array($paymentChannel->class_name, PaymentChannel::$gatewayIgnoreRedirect)) {
                return $redirect_url;
            }

            return Redirect::away($redirect_url);

        } catch (\Exception $exception) {
            //dd($exception->getMessage());

            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }
    }

    public function paymentVerify(Request $request, $gateway)
    {
        $paymentChannel = PaymentChannel::where('class_name', $gateway)
            ->where('status', 'active')
            ->first();

        try {
            $channelManager = ChannelManager::makeChannel($paymentChannel);
            $order = $channelManager->verify($request);

            return $this->paymentOrderAfterVerify($order);

        } catch (\Exception $exception) {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];
            return redirect('cart')->with(['toast' => $toastData]);
        }
    }


    private function paymentOrderAfterVerify($order)
    {
        if (!empty($order)) {

            if ($order->status == Order::$paying) {
                $this->setPaymentAccounting($order);

                $order->update(['status' => Order::$paid]);
            } else {
                if ($order->type === Order::$meeting) {
                    $orderItem = OrderItem::where('order_id', $order->id)->first();

                    if ($orderItem && $orderItem->reserve_meeting_id) {
                        $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();

                        if ($reserveMeeting) {
                            $reserveMeeting->update(['locked_at' => null]);
                        }
                    }
                }
            }

            session()->put($this->order_session_key, $order->id);

            return redirect("/payments/status?t={$order->id}");
        } else {
            $toastData = [
                'title' => trans('cart.fail_purchase'),
                'msg' => trans('cart.gateway_error'),
                'status' => 'error'
            ];

            return redirect('cart')->with($toastData);
        }
    }

    public function setPaymentAccounting($order, $type = null)
    {
        $cashbackAccounting = new CashbackAccounting();

        if ($order->is_charge_account) {
            Accounting::charge($order);

            $cashbackAccounting->rechargeWallet($order);
        } else {
            foreach ($order->orderItems as $orderItem) {
                $updateInstallmentOrderAfterSale = false;
                $updateProductOrderAfterSale = false;

                if (!empty($orderItem->gift_id)) {
                    $gift = $orderItem->gift;

                    $gift->update([
                        'status' => 'active'
                    ]);

                    $gift->sendNotificationsWhenActivated($orderItem->total_amount);
                }

                if (!empty($orderItem->subscribe_id)) {
                    Accounting::createAccountingForSubscribe($orderItem, $type);
                } elseif (!empty($orderItem->promotion_id)) {
                    Accounting::createAccountingForPromotion($orderItem, $type);
                } elseif (!empty($orderItem->registration_package_id)) {
                    Accounting::createAccountingForRegistrationPackage($orderItem, $type);

                    if (!empty($orderItem->become_instructor_id)) {
                        BecomeInstructor::query()->where('id', $orderItem->become_instructor_id)
                            ->update([
                                'package_id' => $orderItem->registration_package_id,
                                'status' => 'pending',
                            ]);
                    }
                } elseif (!empty($orderItem->installment_payment_id)) {
                    Accounting::createAccountingForInstallmentPayment($orderItem, $type);

                    $updateInstallmentOrderAfterSale = true;
                } else {
                    // webinar and meeting and product and bundle

                    Accounting::createAccounting($orderItem, $type);
                    TicketUser::useTicket($orderItem);

                    if (!empty($orderItem->product_id)) {
                        $updateProductOrderAfterSale = true;
                    }
                }

                // Set Sale After All Accounting
                $sale = Sale::createSales($orderItem, $order->payment_method);

                if (!empty($orderItem->reserve_meeting_id)) {
                    $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();
                    $reserveMeeting->update([
                        'sale_id' => $sale->id,
                        'reserved_at' => time()
                    ]);

                    $reserver = $reserveMeeting->user;

                    if ($reserver) {
                        $this->handleMeetingReserveReward($reserver);
                    }
                }

                if ($updateInstallmentOrderAfterSale) {
                    $this->updateInstallmentOrder($orderItem, $sale);
                }

                if ($updateProductOrderAfterSale) {
                    $this->updateProductOrder($sale, $orderItem);
                }

                // Make Ticket Code
                if (!empty($orderItem->event_ticket_id)) {
                    (new EventTicketSoldMixins())->makeTicket($orderItem, $sale);
                }

                // Meeting Package Sessions
                if (!empty($orderItem->meeting_package_id)) {
                    (new MeetingPackageSoldMixins())->makeUserSessions($orderItem, $sale);
                }
            }

            // Set Cashback Accounting For All Order Items
            $cashbackAccounting->setAccountingForOrderItems($order->orderItems);
        }

        Cart::emptyCart($order->user_id);
    }

    public function payStatus(Request $request)
    {
        $orderId = $request->get('t', null);

        if (!empty(session()->get($this->order_session_key, null))) {
            $orderId = session()->get($this->order_session_key, null);
            session()->forget($this->order_session_key);
        }

        $authId = auth()->id();

        $order = Order::where('id', $orderId)
            ->where('user_id', $authId)
            ->first();

        if (!empty($order)) {
            $data = [
                'pageTitle' => trans('public.cart_page_title'),
                'order' => $order,
            ];

            return view('design_1.web.cart.payment.status.index', $data);
        }

        return redirect('/panel');
    }

}
