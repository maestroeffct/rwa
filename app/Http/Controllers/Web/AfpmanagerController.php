<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Accounting;
use App\Models\BecomeInstructor;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ReserveMeeting;
use App\Http\Controllers\Web\traits\PaymentsTrait;
use App\Mixins\Cashback\CashbackAccounting;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Sale;
use App\Models\TicketUser;
use Illuminate\Http\Client\ConnectionException;

class AfpmanagerController extends Controller
{

    //check auth
    protected function is_authenticated(){
        if(Auth::check()){
            return true;
        }
        return false;
    }

    // Manage payment request
    protected $baseUrl;
    protected $appId;
    protected $appSecret;
    protected $apiUsername;
    protected $apiPassword;
    protected $apiDataEncryptedKey;
    protected $apiDataEncryptedIv;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->baseUrl              = env('AFPAY_BASE_URL');
        $this->appId                = env('AFPAY_API_PUBLIC_KEY');
        $this->appSecret            = env('AFPAY_API_KEY_SECRET');
        $this->apiUsername          = env('AFPAY_API_ACCOUNT_USERNAME');
        $this->apiPassword          = env('AFPAY_API_ACCOUNT_PASSWORD');
        $this->apiDataEncryptedKey  = env('AFPAY_API_DATA_ENCRYPTED_KEY');
        $this->apiDataEncryptedIv   = env('AFPAY_API_DATA_ENCRYPTED_IV');
    }

    public function encryptedAESData($data){
        $key = base64_decode($this->apiDataEncryptedKey);
        $iv = hex2bin($this->apiDataEncryptedIv);
        $ciphertext = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($ciphertext);
    }

    protected function redirectToPayment(Request $request)
    {
        if($this->is_authenticated()){
            try {
                $username   = $this->apiUsername;
                $password   = $this->encryptedAESData($this->apiPassword);
                $public_key = $this->appId;
                $secret_key = $this->encryptedAESData($this->appSecret);
                $orderid    = $request->input('orderid');
                $amount     = $request->input('amount');
                $currency   = $request->input('currency');
                $company    = 'Reason With Angel';
                $callbacksuccess    = 'https://reasonwithangel.com/afpay-callback-success';
                $callbackfailed     = 'https://reasonwithangel.com/afpay-callback-failed';

                $url = "{$this->baseUrl}afpmanager/home-afpay-widget?sn={$username}&fn={$password}&pk={$public_key}&sv={$secret_key}&orderid={$orderid}&amount={$amount}&company={$company}&callbacksuccess={$callbacksuccess}&callbackfailed={$callbackfailed}&currency={$currency}";

                return response()->json([
                    'status'    => 200,
                    "message"   => "Opération réussie",
                    "link"      => $url
                ]);
            } catch (ConnectionException $e) {
                return response()->json([
                    'status'    => 500,
                    "message"   => "The operation failed. Your internet connection is unstable. Please try again.",
                ]);
            }
        }
        return response()->json([
            'status'    => 500,
            "message"   => "Your session has expired, please log in again and try again.",
        ]);
    }

    protected function paymentSuccessfull(Request $request)
    {
        if($this->is_authenticated()){
            try {
                /**Controle de validation des inputs */
                $orderid    = $request->get('orderid');
                $ptn        = $request->get('ptn');

                //get current payment
                $order = Order::where('id', $orderid)->first();

                //update order
                $order->update(['status' => Order::$paying]);
                $order->update(['payment_method' => Order::$paymentChannel]);
                $order->update(['reference_id' => $ptn]);

                //update order
                $this->setPaymentAccounting($order, 'payment_channel');
                $order->update(['status' => Order::$paid]);

                if ($order->type === Order::$meeting) {
                    $orderItem = OrderItem::where('order_id', $order->id)->first();

                    if ($orderItem && $orderItem->reserve_meeting_id) {
                        $reserveMeeting = ReserveMeeting::where('id', $orderItem->reserve_meeting_id)->first();

                        if ($reserveMeeting) {
                            $reserveMeeting->update(['locked_at' => null]);
                        }
                    }
                }
                return redirect('/panel/courses/purchases');
            } catch (ConnectionException $e) {
                return response()->json([
                    'status'    => 301,
                    "message"   => "The operation failed. Your internet connection is unstable. Please try again.",
                ]);
            }
        }
        return response()->json([
            'status'    => 500,
            "message"   => "Your session has expired, please log in again and try again.",
        ]);
    }


    protected function transactionFailed(Request $request)
    {
        if($this->is_authenticated()){
            try {
                /**Controle de validation des inputs */
                $orderid    = $request->get('orderid');
                $ptn        = $request->get('$ptn');

                //get current payment
                $order = Order::where('id', $orderid)->first();

                //update order
                $order->update(['status' => Order::$fail]);
                $order->update(['payment_method' => Order::$paymentChannel]);
                $order->update(['reference_id' => $ptn]);
                return redirect('/classes');
            } catch (ConnectionException $e) {
                return response()->json([
                    'status'    => 301,
                    "message"   => "The operation failed. Your internet connection is unstable. Please try again.",
                ]);
            }
        }
        return response()->json([
            'status'    => 500,
            "message"   => "Your session has expired, please log in again and try again.",
        ]);
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
                        BecomeInstructor::where('id', $orderItem->become_instructor_id)
                            ->update([
                                'package_id' => $orderItem->registration_package_id
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
            }

            // Set Cashback Accounting For All Order Items
            $cashbackAccounting->setAccountingForOrderItems($order->orderItems);
        }

        Cart::emptyCart($order->user_id);
    }
}
