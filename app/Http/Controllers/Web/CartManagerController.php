<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Cart;
use App\Models\CartDiscount;
use App\Models\EventTicket;
use App\Models\MeetingPackage;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ReserveMeeting;
use App\Models\Ticket;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartManagerController extends Controller
{
    public $cookieKey = 'carts';

    public function getCarts()
    {
        $carts = collect();

        if (auth()->check()) {
            $user = auth()->user();

            $user->carts()
                ->whereNotNull('product_order_id')
                ->where(function ($query) {
                    $query->whereDoesntHave('productOrder');
                    $query->orWhereDoesntHave('productOrder.product');
                })
                ->delete();

            $carts = $user->carts()
                ->with([
                    'webinar',
                    'bundle',
                    'installmentPayment',
                    'productOrder' => function ($query) {
                        $query->with(['product']);
                    }
                ])
                ->get();
        } else {
            $cookieCarts = Cookie::get($this->cookieKey);

            if (!empty($cookieCarts)) {
                $cookieCarts = json_decode($cookieCarts, true);

                if (!empty($cookieCarts) and count($cookieCarts)) {
                    $carts = collect();

                    foreach ($cookieCarts as $id => $cookieCart) {

                        if (!empty($cookieCart['item_name']) and $cookieCart['item_name'] == 'webinar_id') {
                            $webinar = Webinar::where('id', $cookieCart['item_id'])
                                ->where('private', false)
                                ->where('status', 'active')
                                ->first();

                            if (!empty($webinar)) {
                                $ticket = null;

                                if (!empty($cookieCart['ticket_id'])) {
                                    $ticket = Ticket::where('id', $cookieCart['ticket_id'])->first();
                                }

                                $item = new Cart();
                                $item->uid = $id;
                                $item->webinar_id = $webinar->id;
                                $item->webinar = $webinar;
                                $item->ticket = $ticket;
                                $item->ticket_id = !empty($ticket) ? $ticket->id : null;

                                $carts->add($item);
                            }
                        } elseif (!empty($cookieCart['item_name']) and $cookieCart['item_name'] == 'bundle_id') {
                            $bundle = Bundle::where('id', $cookieCart['item_id'])
                                ->where('status', 'active')
                                ->first();

                            if (!empty($bundle)) {
                                $ticket = null;

                                if (!empty($cookieCart['ticket_id'])) {
                                    $ticket = Ticket::where('id', $cookieCart['ticket_id'])->first();
                                }

                                $item = new Cart();
                                $item->uid = $id;
                                $item->bundle_id = $bundle->id;
                                $item->bundle = $bundle;
                                $item->ticket = $ticket;
                                $item->ticket_id = !empty($ticket) ? $ticket->id : null;

                                $carts->add($item);
                            }
                        } elseif (!empty($cookieCart['item_name']) and $cookieCart['item_name'] == 'product_id') {
                            $product = Product::where('id', $cookieCart['item_id'])->first();

                            if (!empty($product)) {
                                $item = new Cart();
                                $item->uid = $id;
                                $item->product_order_id = $product->id;
                                $item->productOrder = (object)[
                                    'quantity' => $cookieCart['quantity'] ?? 1,
                                    'product' => $product
                                ];

                                $carts->add($item);
                            }
                        } elseif (!empty($cookieCart['item_name']) and $cookieCart['item_name'] == 'event_ticket_id') {
                            $eventTicket = EventTicket::where('id', $cookieCart['item_id'])->first();

                            if (!empty($eventTicket)) {
                                $item = new Cart();
                                $item->uid = $id;
                                $item->event_ticket_id = $eventTicket->id;
                                $item->quantity = $cookieCart['quantity'] ?? 1;
                                $item->eventTicket = $eventTicket;

                                $carts->add($item);
                            }
                        } elseif (!empty($cookieCart['item_name']) and $cookieCart['item_name'] == 'meeting_package_id') {
                            $meetingPackage = MeetingPackage::query()->where('id', $cookieCart['item_id'])->first();

                            if (!empty($meetingPackage)) {
                                $item = new Cart();
                                $item->uid = $id;
                                $item->meeting_package_id = $meetingPackage->id;
                                $item->meetingPackage = $meetingPackage;

                                $carts->add($item);
                            }
                        }
                    }
                }
            }
        }

        return $carts;
    }

    public function storeCookieCartsToDB(Request $request)
    {
        try {
            if (auth()->check()) {
                $user = auth()->user();
                $carts = Cookie::get($this->cookieKey);

                if (!empty($carts)) {
                    $carts = json_decode($carts, true);

                    if (!empty($carts)) {
                        foreach ($carts as $cart) {
                            if (!empty($cart['item_name']) and !empty($cart['item_id'])) {

                                if ($cart['item_name'] == 'webinar_id') {
                                    $this->storeUserWebinarCart($user, $cart);
                                } elseif ($cart['item_name'] == 'product_id') {
                                    $this->storeUserProductCart($request, $user, $cart);
                                } elseif ($cart['item_name'] == 'bundle_id') {
                                    $this->storeUserBundleCart($user, $cart);
                                } elseif ($cart['item_name'] == 'event_ticket_id') {
                                    $this->storeUserEventTicketCart($user, $cart);
                                }elseif ($cart['item_name'] == 'meeting_package_id') {
                                    $this->storeUserMeetingPackageCart($user, $cart);
                                }
                            }
                        }
                    }

                    Cookie::queue($this->cookieKey, null, 0);
                }
            }
        } catch (\Exception $exception) {

        }
    }

    public function storeUserWebinarCart($user, $data)
    {
        $webinar_id = $data['item_id'];
        $ticket_id = $data['ticket_id'] ?? null;

        $webinar = Webinar::where('id', $webinar_id)
            ->where('private', false)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar) and !empty($user)) {
            $checkCourseForSale = checkCourseForSale($webinar, $user);

            if ($checkCourseForSale != 'ok') {
                return $checkCourseForSale;
            }

            $activeSpecialOffer = $webinar->activeSpecialOffer();

            Cart::updateOrCreate([
                'creator_id' => $user->id,
                'webinar_id' => $webinar_id,
            ], [
                'ticket_id' => $ticket_id,
                'special_offer_id' => !empty($activeSpecialOffer) ? $activeSpecialOffer->id : null,
                'created_at' => time()
            ]);

            return 'ok';
        }

        return [
            'title' => trans('public.request_failed'),
            'msg' => trans('cart.course_not_found'),
            'status' => 'error'
        ];
    }

    public function storeUserBundleCart($user, $data)
    {
        $bundle_id = $data['item_id'];
        $ticket_id = $data['ticket_id'] ?? null;

        $bundle = Bundle::where('id', $bundle_id)
            ->where('status', 'active')
            ->first();

        if (!empty($bundle) and !empty($user)) {
            $checkCourseForSale = checkCourseForSale($bundle, $user);

            if ($checkCourseForSale != 'ok') {
                return $checkCourseForSale;
            }

            $activeSpecialOffer = $bundle->activeSpecialOffer();

            Cart::updateOrCreate([
                'creator_id' => $user->id,
                'bundle_id' => $bundle_id,
            ], [
                'ticket_id' => $ticket_id,
                'special_offer_id' => !empty($activeSpecialOffer) ? $activeSpecialOffer->id : null,
                'created_at' => time()
            ]);

            return 'ok';
        }

        return [
            'title' => trans('public.request_failed'),
            'msg' => trans('cart.course_not_found'),
            'status' => 'error'
        ];
    }

    public function storeUserEventTicketCart($user, $data)
    {
        $eventTicket = EventTicket::query()->where('id', $data['item_id'])
            ->whereHas('event', function ($query) {
                $query->where('status', 'publish');
            })
            ->first();

        if (!empty($eventTicket) and !empty($user)) {
            $quantity = $data['quantity'] ?? 1;

            $checkForSale = checkEventTicketForSale($eventTicket, $user, $quantity);

            if ($checkForSale != 'ok') {
                return $checkForSale;
            }

            Cart::updateOrCreate([
                'creator_id' => $user->id,
                'event_ticket_id' => $eventTicket->id,
            ], [
                'quantity' => $quantity,
                'created_at' => time()
            ]);

            return 'ok';
        }

        return [
            'title' => trans('public.request_failed'),
            'msg' => trans('update.event_ticket_not_found'),
            'status' => 'error'
        ];
    }

    public function storeUserMeetingPackageCart($user, $data)
    {
        $meetingPackage = MeetingPackage::query()->where('id', $data['item_id'])
            ->where('enable', true)
            ->first();

        if (!empty($meetingPackage) and !empty($user)) {
            $checkForSale = checkMeetingPackageForSale($meetingPackage, $user);

            if ($checkForSale != 'ok') {
                return $checkForSale;
            }

            Cart::updateOrCreate([
                'creator_id' => $user->id,
                'meeting_package_id' => $meetingPackage->id,
            ], [
                'created_at' => time()
            ]);

            return 'ok';
        }

        return [
            'title' => trans('public.request_failed'),
            'msg' => trans('update.meeting_package_not_found'),
            'status' => 'error'
        ];
    }

    public function storeUserProductCart(Request $request, $user, $data)
    {
        $product_id = $data['item_id'];
        $specifications = $data['specifications'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        $product = Product::where('id', $product_id)
            ->where('status', 'active')
            ->first();

        if (!empty($product) and !empty($user)) {
            $checkProductForSale = checkProductForSale($request, $product, $user);

            if ($checkProductForSale != 'ok') {
                return $checkProductForSale;
            }

            $activeDiscount = $product->getActiveDiscount();

            $productOrder = ProductOrder::updateOrCreate([
                'product_id' => $product->id,
                'seller_id' => $product->creator_id,
                'buyer_id' => $user->id,
                'sale_id' => null,
                'status' => 'pending',
            ], [
                'specifications' => $specifications ? json_encode($specifications) : null,
                'quantity' => $quantity,
                'discount_id' => !empty($activeDiscount) ? $activeDiscount->id : null,
                'created_at' => time()
            ]);

            Cart::updateOrCreate([
                'creator_id' => $user->id,
                'product_order_id' => $productOrder->id,
            ], [
                'product_discount_id' => !empty($activeDiscount) ? $activeDiscount->id : null,
                'created_at' => time()
            ]);

            return 'ok';
        }

        return [
            'title' => trans('public.request_failed'),
            'msg' => trans('cart.course_not_found'),
            'status' => 'error'
        ];
    }

    public function storeCookieCart($data)
    {
        $carts = Cookie::get($this->cookieKey);

        if (!empty($carts)) {
            $carts = json_decode($carts, true);
        } else {
            $carts = [];
        }

        $item_id = $data['item_id'];
        $item_name = $data['item_name'];

        if (empty($data['quantity'])) {
            $data['quantity'] = 1;
        }

        $carts[$item_name . '_' . $item_id] = $data;

        Cookie::queue($this->cookieKey, json_encode($carts), 30 * 24 * 60);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'item_id' => 'required',
            'item_name' => 'nullable',
        ]);

        $data = $request->except('_token');
        $item_name = $data['item_name'];

        if (!empty($user)) { // store in DB
            $result = null;

            if ($item_name == 'webinar_id') {
                $result = $this->storeUserWebinarCart($user, $data);
            } elseif ($item_name == 'product_id') {
                $result = $this->storeUserProductCart($request, $user, $data);
            } elseif ($item_name == 'bundle_id') {
                $result = $this->storeUserBundleCart($user, $data);
            } elseif ($item_name == 'event_ticket_id') {
                $result = $this->storeUserEventTicketCart($user, $data);
            } elseif ($item_name == 'meeting_package_id') {
                $result = $this->storeUserMeetingPackageCart($user, $data);
            }

            if ($result != 'ok') {
                if ($request->ajax()) {
                    return response()->json(['toast_alert' => $result], 422);
                }

                return back()->with(['toast' => $result]);
            }
        } else { // store in cookie
            $this->storeCookieCart($data);
        }

        $toastData = [
            'title' => trans('cart.cart_add_success_title'),
            'msg' => trans('cart.cart_add_success_msg'),
            'status' => 'success',
            'code' => 200,
        ];

        if ($request->ajax()) {
            return response()->json($toastData);
        }

        return back()->with(['toast' => $toastData]);
    }

    public function quantity(Request $request, $itemId)
    {
        $user = auth()->user();
        $isDB = false;

        if (!empty($user)) {
            $cartItem = Cart::query()
                ->where('id', $itemId)
                ->where('creator_id', $user->id)
                ->first();

            $isDB = true;
        } else {
            $carts = $this->getCarts();
            $cartItem = $carts->where('uid', $itemId);
        }

        if (!empty($cartItem)) {
            $quantity = $request->get('quantity', 1);

            if (!empty($cartItem->product_order_id)) {
                $product = $cartItem->productOrder->product;
                $availability = $product->getAvailability();

                if ($quantity > $availability) {
                    $quantity = $availability;
                } else if ($quantity < 1) {
                    $quantity = 1;
                }

                if ($isDB) {
                    $cartItem->productOrder->update([
                        'quantity' => $quantity
                    ]);

                } else {
                    $newData = [
                        'item_id' => $product->id,
                        'item_name' => 'product_id',
                        'quantity' => $quantity,
                    ];

                    $this->storeCookieCart($newData);
                }
            }

            $cartItemInfo = $cartItem->getItemInfo();
            $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
            $price = 0;
            $priceOffed = 0;

            if (!empty($cartItemInfo['discountPrice'])) {
                $price = handlePrice(($cartItemInfo['discountPrice'] * $cartItemInfo['quantity']), true, true, false, null, true, $cartTaxType);
                $priceOffed = handlePrice(($cartItemInfo['price'] * $cartItemInfo['quantity']), true, true, false, null, true, $cartTaxType);
            } else {
                $price = handlePrice(($cartItemInfo['price'] * $cartItemInfo['quantity']), true, true, false, null, true, $cartTaxType);
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.your_cart_quantity_updated'),
                'price' => $price,
                'price_offed' => $priceOffed,
            ]);
        }

        return response()->json([], 422);
    }


    public function destroy($id)
    {
        if (auth()->check()) {
            $user_id = auth()->id();

            $cart = Cart::where('id', $id)
                ->where('creator_id', $user_id)
                ->first();

            if (!empty($cart)) {
                if (!empty($cart->reserve_meeting_id)) {
                    $reserve = ReserveMeeting::where('id', $cart->reserve_meeting_id)
                        ->where('user_id', $user_id)
                        ->first();

                    if (!empty($reserve)) {
                        $reserve->delete();
                    }
                } elseif (!empty($cart->installment_payment_id)) {
                    $installmentPayment = $cart->installmentPayment;

                    if (!empty($installmentPayment) and $installmentPayment->status == 'paying') {
                        $installmentOrder = $installmentPayment->installmentOrder;

                        $installmentPayment->delete();

                        if (!empty($installmentOrder) and $installmentOrder->status == 'paying') {
                            $installmentOrder->delete();
                        }
                    }
                }

                $cart->delete();
            }
        } else {
            $carts = Cookie::get($this->cookieKey);

            if (!empty($carts)) {
                $carts = json_decode($carts, true);

                if (!empty($carts[$id])) {
                    unset($carts[$id]);
                }

                Cookie::queue($this->cookieKey, json_encode($carts), 30 * 24 * 60);
            }
        }

        return response()->json([
            'code' => 200
        ], 200);
    }

    public function getDrawerInfo()
    {
        $user = auth()->user();
        $cartItems = $this->getCarts();

        $subtotal = 0;
        $notIsEmpty = (!empty($cartItems) and $cartItems->isNotEmpty());
        $extraData = [];

        if ($notIsEmpty) {
            $cartController = new CartController();
            //$calculate = $cartController->calculatePrice($cartItems, $user);

            $subtotal = Cart::getCartsTotalPrice($cartItems);

            $data = [
                'cartItems' => $cartItems,
            ];

            $html = (string)view()->make("design_1.web.cart.drawer.body", $data);
        } else {
            $cartDiscount = CartDiscount::query()
                ->where('show_only_on_empty_cart', true)
                ->where('enable', true)
                ->first();

            $data = [
                'cartDiscount' => $cartDiscount,
            ];

            $html = (string)view()->make("design_1.web.cart.drawer.empty", $data);
        }

        return response()->json([
            'code' => 200,
            'is_empty' => !$notIsEmpty,
            'subtotal' => $subtotal > 0 ? handlePrice($subtotal) : 0,
            'html' => $html,
        ]);
    }
}
