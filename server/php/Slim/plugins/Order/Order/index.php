<?php
/**
 * API Endpoints
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */

/**
 * GET ordersGet
 * Summary: Get orders details
 * Notes: Get orders details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/orders', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $payment_status = array(
            \Constants\OrderStatus::PAYMENTPENDING
        );
        $orders = Models\Order::Filter($queryParams)->whereNotIn('orders.order_status_id', $payment_status)->paginate()->toArray();
        $data = $orders['data'];
        unset($orders['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $orders
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canListOrder'));
/**
 * GET OrdersOrderIdGet
 * Summary: Get user particular orders lists
 * Notes: Get user particular orders lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/orders/{orderId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    $order = Models\Order::Filter($queryParams)->find($request->getAttribute('orderId'));
    if (!empty($order)) {
        $result['data'] = $order->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewOrder'));
/**
 * GET OrderStatusesGet
 * Summary: Get  order statuses
 * Notes: Filter order statuses.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/order_statuses', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $order_statuses['data'] = Models\OrderStatus::Filter($queryParams)->get();
        $data = $order_statuses['data'];
        unset($order_statuses['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $order_statuses
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * POST usersUserIdCartPost
 * Summary: Add cart
 * Notes: Add cart.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/carts', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $result = array();
    $cart = new Models\Cart;
    $cart_data = $cart->where('cookie_id', $body['cookie_id'])->where('restaurant_menu_id', $body['restaurant_menu_id'])->where('restaurant_menu_price_id', $body['restaurant_menu_price_id'])->first();
    if (empty($cart_data)) {
        $response = $cart->addCart($body);
    } else {
        //If suppose cart have addon records then we will insert new cart record
        if (!empty($body['restaurant_menu_addon_price'])) {
            $response = $cart->addCart($body);
        } else {
            $response = $cart->updateCart($cart_data['id'], $body);
        }
    }
    if (!empty($response['data'])) {
        $result = $response['data'];
    }
    return renderWithJson($result, $response['error']['message'], '', $response['error']['code']);
});
/**
 * GET UsersUserIdCartsCartIdCookieIdGet
 * Summary: Get user cart lists
 * Notes: Get user cart lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/carts', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    //Get restaurant details
    $result = array();
    $carts['cart'] = Models\Cart::Filter($queryParams)->get()->toArray();
    if (count($carts['cart']) > 0) {
        //get restaurant values
        $carts['restaurant'] = Models\Restaurant::with("attachment","restaurant_timing")->where('id', $carts['cart'][0]['restaurant_id'])->first();
        return renderWithJson($carts);
    } else {
        return renderWithJson($result, 'No data found', '', 1);
    }
});
/**
 * PUT UsersUserIdCartsCartIdCookieIdPut
 * Summary: Update user cart lists
 * Notes: Update user cart lists. \n
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/carts/{cartId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();    
    $body = $request->getParsedBody();
    $result = array();
    $cart = Models\Cart::Filter($queryParams)->where('id', $request->getAttribute('cartId'))->first();
    if (!empty($cart)) {
        $response = $cart->updateCart($cart->id, $body);
    } else {
        return renderWithJson($result, 'No data found', '', 1);
    }
    if (!empty($response['data'])) {
        $result = $response['data'];
    }
    return renderWithJson($result, $response['error']['message'], '', $response['error']['code']);
});
/**
 * DELETE UsersUserIdCartsCartIdCookieIdDelete
 * Summary: Delete cart
 * Notes: Delete cart
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/carts/{cartId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();    
    $result = array();
    $cart = Models\Cart::Filter($queryParams)->where('id', $request->getAttribute('cartId'))->first();
    try {
        if ($cart->delete()) {
            //Check cart addons for delete
            $cart_addons = Models\CartAddon::where('cart_id', $request->getAttribute('cartId'))->get();
            if (!empty($cart_addons)) {
                Models\CartAddon::where('cart_id', $request->getAttribute('cartId'))->delete();
            }
        }
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * PUT UsersUserIdOrdersOrderIdPut
 * Summary: Update order
 * Notes: update order
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/orders/{orderId}', function ($request, $response, $args) {
    global $authUser,$_server_domain_url;
    $body = $request->getParsedBody();
    $queryParams = $request->getQueryParams();
    $payment = new Models\Payment;
    $result = array();
    $order = Models\Order::with('restaurant.supervisors', 'user', 'sudopay_transaction_log', 'wallet_transaction_log', 'delivery_person')->where('id', $request->getAttribute('orderId'));
    if (!empty($queryParams['restaurant_id'])) {
        $order = $order->where('restaurant_id', $queryParams['restaurant_id']);
    }
    $order = $order->first();
    if (!empty($order)) {
        try {
            if ($order['order_status_id'] == \Constants\OrderStatus::PAYMENTPENDING) {
                $order->fill($body);
                if ($order->is_as_soon_as_delivery == 0) {
                    unset($order->later_delivery_date);
                }
                unset($order->credit_card_number);
                unset($order->credit_card_expire);
                unset($order->credit_card_name_on_card);
                unset($order->credit_card_code);
                unset($order->phone);
                unset($order->payment_note);
                unset($order->email);
                unset($order->country);
                unset($order->state);
                unset($order->city);
                unset($order->zip_code);
                unset($order->credit_card_expire_month);
                unset($order->credit_card_expire_year);
                unset($order->order_id);
                $order->update();
                //Sudopayment
                $response = $payment->processPayment($order->id, $body, 'Order', '');
                if (!empty($response['data'])) {
                    $result = $response['data'];
                }
                return renderWithJson($result, $response['error']['message'], '', $response['error']['code']);
            } elseif ($body['order_status_id'] == \Constants\OrderStatus::REJECTED) {
                if ($order->order_status_id != \Constants\OrderStatus::PENDING) {
                    return renderWithJson($result, 'Order is in pending status only can be rejected', '', 1);
                }
                if ($authUser->role_id == \Constants\ConstUserTypes::SUPERVISOR) {
                    if (!empty($order->restaurant->supervisors)) {
                        $supervisor = "";
                        foreach ($order['restaurant']['supervisors'] as  $supervisors) {
                            if ($authUser['id'] == $supervisors['user_id']) {
                                $supervisor = $supervisors['user_id']; 
                            }
                        }
                        if (empty($supervisor)) {
                           return renderWithJson($result, 'Invalid user', '', 1); 
                        }
                    }
                } else {
                    if ($order->restaurant->user_id != $authUser->id && $authUser->role_id != \Constants\ConstUserTypes::ADMIN ) {
                        return renderWithJson($result, 'Invalid user', '', 1);
                    }
                }
                if (in_array($order->payment_gateway_id, [\Constants\PaymentGateways::SUDOPAY, \Constants\PaymentGateways::PAYPAL, \Constants\PaymentGateways::WALLET]) ) {
                    $is_payment_transaction = $order->refundPayment($order);
                    if ($is_payment_transaction) {
                        $emailFindReplace = array(
                            '##RESTAURANT_NAME##' => $order['restaurant']['name'],
                            '##USERNAME##' => $order['user']['username']
                        );
                        sendMail('orderrejected', $emailFindReplace, $order['user']['email']);
                        $result['data'] = $order->toArray();
                        return renderWithJson($result);
                    } else {
                        return renderWithJson($result, 'Order could not be updated. Please, try again1.', '', 1);
                    }
                } else if ($order->payment_gateway_id == \Constants\PaymentGateways::COD && $order->order_status_id == \Constants\OrderStatus::AWAITINGCODVALIDATION) {
                    $order->order_status_id = \Constants\OrderStatus::REJECTED;
                    $order->save();
                    $order->updateRestaurantOrder($order);
                    $emailFindReplace = array(
                        '##RESTAURANT_NAME##' => $order['restaurant']['name'],
                        '##USERNAME##' => $order['user']['username']
                    );
                    sendMail('orderrejected', $emailFindReplace, $order['user']['email']);
                    $result['data'] = $order->toArray();
                    return renderWithJson($result);
                }
            }elseif($order->order_status_id == \Constants\OrderStatus::AWAITINGCODVALIDATION && $body['order_status_id'] == \Constants\OrderStatus::CANCEL)
            {
                    $order->order_status_id = \Constants\OrderStatus::CANCEL;
                    $order->save();
                    $order->updateRestaurantOrder($order);
                    $emailFindReplace = array(
                            '##RESTAURANT_NAME##' => $order['restaurant']['name'],
                            '##USERNAME##' => $order['user']['username'],
                            '##AMOUNT##' => $order['total_price'],
                            '##DATE##' => date('d-m-Y', strtotime($order['created_at']))
                    );
                    sendMail('ordercancelled', $emailFindReplace, $order['user']['email']);
                    $result['data'] = $order->toArray();
                    return renderWithJson($result);
            } elseif ($body['order_status_id'] == \Constants\OrderStatus::PROCESSING) {
                $order->order_status_id = \Constants\OrderStatus::PROCESSING;
                $order->site_fee = ($order->total_price / 100) * SITE_COMMISSION;
                if ($order->save()) {
                    // Saving wallet amount for restaurant
                    $restaurant = Models\Restaurant::find($queryParams['restaurant_id']);
                    $user = Models\User::find($restaurant->user_id);
                    $user->available_wallet_amount = $user->available_wallet_amount + ($order->total_price - (($order->total_price / 100) * SITE_COMMISSION));
                    $user->save();
                    $payment->addTransactions($order, 'Order');
                    $emailFindReplace = array(
                        '##RESTAURANT##' => $order['restaurant']['name'],
                        '##USERNAME##' => $order['user']['username'],
                        '##ORDER_NO##' => $order['id'],
                        '##CURRENCY_SYMBOL##' => CURRENCY_SYMBOL,
                        '##AMOUNT##' => $order['total_price'],
                        '##ORDER_URL##' => $_server_domain_url . '/orders/' . $order['track_id'] . '/track'
                    );
                    sendMail('orderProcessing', $emailFindReplace, $order['user']['email']);
                    $result = $order->toArray();
                    return renderWithJson($result);
                } else {
                    return renderWithJson($result, 'Order could not be updated. Please, try again.', '', 1);
                }
            } elseif ($body['order_status_id'] == \Constants\OrderStatus::DELIVERYPERSONASSIGNED) {
                $delivery_person = Models\DeliveryPerson::where('id', $body['restaurant_delivery_person_id'])->first();
                if ($order->restaurant_branch_id == $delivery_person->restaurant_branch_id) {
                    $order->order_status_id = \Constants\OrderStatus::DELIVERYPERSONASSIGNED;
                    $order->restaurant_delivery_person_id = $body['restaurant_delivery_person_id'];
                    $order->save();                
                    if (isPluginEnabled('Order/Sms')) {
                        $message = array(
                            'order_id' => $order->id,
                            'message_type' => 'assigned'
                        );
                        sendSMS($message, $order->user_id);
                    } 
                    if (isPluginEnabled('Order/Mobile')) {
                        $message = array(
                            'order_id' => $order->id,
                            'user_id' => $order->user_id,
                            'message_type' => 'assigned'
                        );
                        addPushNotification($order->user_id, $message);
                    }                                          
                    $result['data'] = $order->toArray();
                    return renderWithJson($result);
                } else {
                    return renderWithJson($result, 'Delivery Person could not be assigned. Please, try again.', '', 1);
                }
            } elseif ($body['order_status_id'] == \Constants\OrderStatus::OUTFORDELIVERY) {
                $order->order_status_id = \Constants\OrderStatus::OUTFORDELIVERY;
                $order->save();
                $restaurant = Models\Restaurant::find($queryParams['restaurant_id']);
                $user = Models\User::find($restaurant->user_id);                    
                $emailFindReplace = array(
                    '##RESTAURANT##' => $order['restaurant']['name'],
                    '##USERNAME##' => $order['user']['username'],
                    '##ORDER_NO##' => $order['id'],
                    '##CURRENCY_SYMBOL##' => CURRENCY_SYMBOL,
                    '##AMOUNT##' => $order['total_price'],
                    '##ORDER_URL##' => $_server_domain_url . '/orders/' . $order['track_id'] . '/track'
                );
                sendMail('orderOutForDelivery', $emailFindReplace, $order['user']['email']);                    
                if (isPluginEnabled('Order/Sms')) {
                    $message = array(
                        'order_id' => $order->id,
                        'message_type' => 'out for delivery'
                    );
                    sendSMS($message, $order->user_id);
                }  
                if (isPluginEnabled('Order/Mobile')) {
                    $message = array(
                        'order_id' => $order->id,
                        'user_id' => $order->user_id,                        
                        'message_type' => 'out for delivery'
                    );
                    addPushNotification($order->user_id, $message);
                }                                  
                $result['data'] = $order->toArray();
                return renderWithJson($result);
            } elseif ($body['order_status_id'] == \Constants\OrderStatus::DELIVERED) {
                $order->order_status_id = \Constants\OrderStatus::DELIVERED;
                $order->delivered_date = date('Y-m-d h:i:s');
                $order->save();
                if (isPluginEnabled('Order/Sms')) {
                    $message = array(
                        'order_id' => $order->id,
                        'message_type' => 'delivered'
                    );
                    sendSMS($message, $order->user_id);
                }  
                if (isPluginEnabled('Order/Mobile')) {
                    $message = array(
                        'order_id' => $order->id,
                        'user_id' => $order->user_id,                        
                        'message_type' => 'delivered'
                    );
                    addPushNotification($order->user_id, $message);
                }                                  
                $result['data'] = $order->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'Order could not be updated. Please, try again.', '', 1);
            }
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Order is not found.', '', 1);
    }
})->add(new Acl\ACL('canUserUpdateOrder'));
/**
 * POST usersUserIdCartcookieIdOrderPost .
 * Summary:Cart Order.
 * Notes: Cart Order.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/checkout', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $args = $request->getParsedBody();
    $user_id = $queryParams['user_id'];
    $payment = new Models\Payment;
    $result = $coupon = array();
    $total_price = 0;    
    $carts = Models\Cart::Filter($queryParams)->with('cart_addon')->get();
    if (count($carts) > 0) {
        $order = new Models\Order();
        $userAddress = new Models\UserAddress();
        if (!empty($queryParams['cookie_id'])) {
            $cart =  Models\Cart::where('cookie_id', $queryParams['cookie_id'])->with('restaurant')->first();
            $restaurant = $cart['restaurant'];
            if (!empty($restaurant['parent_id'])) {
                $order->restaurant_id = $restaurant['parent_id'];
                $order->restaurant_branch_id = $restaurant['id'];
            } else {
                $order->restaurant_id = $restaurant['id'];
            }            
        }        
        if (!empty($args['coupon_code'])) {
            $coupon = Models\Coupon::verifyAndCouponCode($args['coupon_code'], $order->restaurant_id);
            if ($coupon['error']['code']) {
                return renderWithJson($result, $coupon['error']['message'], '', 1);
            }
        }
        $order->user_id = $user_id;                
        $order->is_pickup_or_delivery = $args['is_allow_users_to_door_delivery_order'];
        $delivery_charge = ($args['is_allow_users_to_door_delivery_order'] == 1 ? $restaurant['delivery_charge'] : '0.00');
        $order->order_status_id = \Constants\OrderStatus::PAYMENTPENDING;
        $order->delivery_charge = $delivery_charge;
        try {
            if (!empty($args['gateway_id'])) {
                $order->gateway_id = $args['gateway_id'];
            }
            $order->payment_gateway_id = $args['payment_gateway_id'];
            $order->success_url = !empty($args['success_url']) ? $args['success_url'] : '';
            $order->cancel_url = !empty($args['cancel_url']) ? $args['cancel_url'] : '';
            if (!empty($args['address_id'])) {
                $getUserAddress = Models\UserAddress::where('id', $args['address_id'])->first();
                $order->user_address_id = $getUserAddress->id;
                $order->address = $getUserAddress->address2;
                $order->city_id = $getUserAddress->city_id;
                $order->state_id = $getUserAddress->state_id;
                $order->country_id = $getUserAddress->country_id;
                $order->latitude = $getUserAddress->latitude;
                $order->longitude = $getUserAddress->longitude;
                $order->zip_code = $getUserAddress->zip_code;
            } elseif (!empty($args['user_address'])) {
                $userAddress->fill($args['user_address']);
                // Set user address title as Home as default
                $userAddress->title = 'Home';
                $userAddress->user_id = $user_id;
                unset($userAddress->user_address);
                unset($userAddress->address_id);
                unset($userAddress->state);
                unset($userAddress->city);
                unset($userAddress->country);
                unset($userAddress->location);
                unset($userAddress->payment_gateway_id);
                unset($userAddress->gateway_id);
                unset($userAddress->is_allow_users_to_door_delivery_order);
                //get country, state and city ids
                if (!empty($args['user_address']['country']['iso2']) && !empty($args['user_address']['city']['name']) && !empty($args['user_address']['city']['name'])) {
                    $userAddress->country_id = Models\Country::findCountryIdFromIso2($args['user_address']['country']['iso2']);
                    $userAddress->state_id = Models\State::findOrSaveAndGetStateId($args['user_address']['state']['name'], $userAddress->country_id);
                    $userAddress->city_id = Models\City::findOrSaveAndGetCityId($args['user_address']['city']['name'], $userAddress->country_id, $userAddress->state_id);
                } else {
                    $userAddress->country_id = $restaurant->country_id;
                    $userAddress->state_id = $restaurant->state_id;
                    $userAddress->city_id = $restaurant->city_id;
                }
                $userAddress->latitude = !empty($args['user_address']['latitude']) ? $args['user_address']['latitude'] : $restaurant->latitude;
                $userAddress->longitude = !empty($args['user_address']['longitude']) ? $args['user_address']['longitude'] : $restaurant->longitude;
                $userAddress->zip_code = !empty($args['user_address']['zip_code']) ? $args['user_address']['zip_code'] : $restaurant->zip_code;
                $userAddress->save();
                $order->user_address_id = $userAddress->id;
                $order->address = !empty($args['user_address']['address2']) ? $args['user_address']['address2'] : '';
                $order->city_id = $userAddress->city_id;
                $order->state_id = $userAddress->state_id;
                $order->country_id = $userAddress->country_id;
                $order->latitude = $userAddress->latitude;
                $order->longitude = $userAddress->longitude;
                $order->zip_code = $userAddress->zip_code;
            }            
            if ($order->save()) {
                $order->track_id = md5($order->id.SECURITY_SALT);
                $findReplace = array(
                    '##TRACKID##' => $order->track_id
                );
                $order->success_url = strtr($order->success_url, $findReplace);
                $order->cancel_url = strtr($order->cancel_url, $findReplace);
                $order->save();                
                foreach ($carts as $cart) {
                    $orderitem = new Models\OrderItem();
                    $orderitem->order_id = $order->id;
                    $orderitem->restaurant_menu_id = $cart->restaurant_menu_id;
                    $orderitem->restaurant_menu_price_id = $cart->restaurant_menu_price_id;
                    $orderitem->quantity = $cart->quantity;
                    $orderitem->price = $cart->price;
                    $orderitem->total_price = $cart->total_price;
                    $total_price = $total_price + $cart->total_price;
                    $orderitem->save();
                    if (count($cart->cart_addon) > 0) {
                        foreach ($cart->cart_addon as $addon) {
                            $orderaddon = new Models\OrderItemAddon();
                            $orderaddon->order_id = $order->id;
                            $orderaddon->order_item_id = $orderitem->id;
                            $orderaddon->restaurant_menu_addon_price_id = $addon->restaurant_menu_addon_price_id;
                            $orderaddon->price = $addon->price;
                            $restaurantMenuAddon = Models\RestaurantMenuAddonPrice::where('id', $addon->restaurant_menu_addon_price_id)->select('restaurant_addon_id')->first();
                            $orderaddon->restaurant_addon_id = $restaurantMenuAddon['restaurant_addon_id'];
                            $orderaddon->save();
                        }
                    }
                }
                //Sales tax amount
                $sale_tax_amount = ($total_price * $restaurant['sales_tax']) / 100;
                $overall_total_price = $sale_tax_amount + $delivery_charge + $total_price;
                //Update over all total price
                $update_order = Models\Order::find($order->id);
                $update_order->sales_tax = $sale_tax_amount;
                $update_order->total_price = $overall_total_price;
                if (!empty($args['coupon_code']) && !empty($coupon['data']['id'])) {
                    $discountDetails = Models\Coupon::calculateDiscountPrice($overall_total_price, $coupon['data']['discount'], $coupon['data']['is_flat_discount_in_amount']);
                    $update_order->coupon_id = $coupon['data']['id'];
                    $update_order->discount_amount = $discountDetails['discount_price'];
                    $update_order->total_price = $discountDetails['original_price'];
                }
                $update_order->save();
                // Fetch user address
                if (!empty($user_id)) {
                    $data['user_address'] = Models\UserAddress::where('user_id', $user_id)->get();
                    if (count($data['user_address']) < 0) {
                        $data['user_address'] = [];
                    }                
                }              
                //Fetch orders info
                $order = Models\Order::with('restaurant', 'user')->where('id', $order['id'])->first();
                if (!empty($queryParams['cookie_id'])) {            
                if ($order->total_price > 0) {
                        global $_server_domain_url;     
                        $args['name'] = $args['description'] = $order->restaurant->name;
                        $args['amount'] = $order->total_price;                    
                        $args['id'] = $order->id;                
                        $args['notify_url'] = $_server_domain_url . '/ipn/process_ipn/' . $order->id . '/hash/' . md5(SECURITY_SALT . $order->id . SITE_NAME).'/Order';
                        $args['success_url'] = $order->success_url;
                        $args['cancel_url'] = $order->cancel_url;
                        $args['email'] = $order->user->email;
                        $args['phone'] = $order->user->mobile;
                        $order_user  = Models\User::where('id', $order->user_id)->first();
                        if (!empty($order_user)) {
                            $args['first_name'] = !empty($order_user->first_name) ? $order_user->first_name : $order_user->username; 
                            $args['last_name'] = !empty($order_user->last_name) ? $order_user->last_name : $order_user->username;
                            //TODO quick fix
                            if (!empty($order_user->mobile_code)) {
                                $CountryIso2 = Models\Country::where('phone', $order_user->mobile_code)->select('iso2', 'id')->first();
                                if (!empty($CountryIso2)) {
                                    $args['buyer_country_iso2'] = $CountryIso2['iso2'];
                                }
                            } else {
                                $args['buyer_country_iso2'] = 'IN';
                            }
                        }
                        $result = $payment->processPayment($order->id, $args, 'Order');
                    } else {
                        $order->order_status_id = \Constants\OrderStatus::PENDING;
                        $order->update();
                        $result['data'] = $order->toArray();
                    }
                return renderWithJson($result);
                }
            } else {
               return renderWithJson($result, 'order could not be added. Try again.', '', 1); 
            }
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Your cart is empty.', '', 1);
    }
});
$app->POST('/api/v1/orders/{orderId}/reorder', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $total_price = 0;
    $payment = new Models\Payment;    
    try {
        $order = Models\Order::with('order_items.order_item_addon','restaurant')->find($request->getAttribute('orderId'));
        if(!empty($order)){
            $re_order = new Models\Order();
            $re_order->restaurant_id = $order->restaurant_id;   
            $re_order->restaurant_branch_id = $order->restaurant_branch_id;    
            if (!empty($authUser['id'])) {
                $re_order->user_id = $authUser['id'];
            }
            $re_order->is_pickup_or_delivery = $order->is_pickup_or_delivery;  
            $delivery_charge = !empty($order['restaurant']['delivery_charge']) ? $order['restaurant']['delivery_charge'] : '0.00';               
            $re_order->delivery_charge = $order->delivery_charge; 
            $re_order->order_status_id = \Constants\OrderStatus::PAYMENTPENDING;
            $re_order->user_address_id = $order->user_address_id;
            $re_order->address = $order->address;
            $re_order->city_id = $order->city_id;
            $re_order->state_id = $order->state_id;
            $re_order->country_id = $order->country_id;
            $re_order->latitude = $order->latitude;
            $re_order->longitude = $order->longitude;
            $re_order->zip_code = $order->zip_code;  
            if($re_order->save()){
                if(!empty($order['order_items'])){
                    foreach($order['order_items'] as $order_items){
                        $re_order_items = new Models\OrderItem();   
                        $re_order_items->order_id =  $re_order->id;
                        $re_order_items->restaurant_menu_id = $order_items->restaurant_menu_id;
                        $re_order_items->restaurant_menu_price_id = $order_items->restaurant_menu_price_id;
                        $re_order_items->quantity = $order_items->quantity;
                        $re_order_items->price = $order_items->price;
                        $re_order_items->total_price = $order_items->total_price;
                        $re_order_items->save();
                        $total_price = $total_price + $order_items->total_price;   
                        if(!empty($order_items['order_item_addon'])) {
                            foreach($order_items['order_item_addon'] as $order_item_addons){
                                $re_order_addon = new Models\OrderItemAddon();
                                $re_order_addon->order_id = $re_order->id;
                                $re_order_addon->order_item_id = $re_order_items->id;
                                $re_order_addon->restaurant_menu_addon_price_id = $order_item_addons->restaurant_menu_addon_price_id;
                                $re_order_addon->price = $order_item_addons->price;
                                $re_order_addon->restaurant_addon_id = $order_item_addons->restaurant_addon_id;
                                $re_order_addon->save();                               
                            }
                        }                            
                    }
                }
                //Sales tax amount
                $sale_tax_amount = ($total_price * $order['restaurant']['sales_tax']) / 100;
                $overall_total_price = $sale_tax_amount + $delivery_charge + $total_price;
                //Update over all total price
                $update_order = Models\Order::with('order_items.order_item_addon','restaurant','user')->find($re_order->id);
                $update_order->sales_tax = $sale_tax_amount;
                $update_order->total_price = $overall_total_price;
                $update_order->save();    
                $result['data'] = $update_order->toArray();                   
                return renderWithJson($result);
            }else{
                return renderWithJson($result, 'order could not be added. Try again.', '', 1);  
            }   
        }else{
            return renderWithJson($result, 'order could not be added. Try again.', '', 1);  
        }
    }catch(Exception $e){
        return renderWithJson($result, $e->getMessage(), '', 1);        
    }
})->add(new Acl\ACL('canReorder'));
/**
 * GET userAddressGet
 * Summary: Get users addresses lists
 * Notes: Get users addresses lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_addresses', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $userAddresses = Models\UserAddress::Filter($queryParams)->paginate()->toArray();
        $data = $userAddresses['data'];
        unset($userAddresses['data']);
        if (!empty($data) && !empty($queryParams['restaurant_id']) && !empty($queryParams['user_id']) ) {
            $restaurant = Models\Restaurant::where('id', $queryParams['restaurant_id'])->first();
            if (!empty($restaurant->latitude) && !empty($restaurant->longitude)) {
                $lat = $latitude = $restaurant->latitude;
                $lng = $longitude = $restaurant->longitude;
                $radius = $restaurant->delivery_miles;
                $distance = 'ROUND(( 6371 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ')) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) )))';
                $nearestUserAddresses = Models\UserAddress::select('user_addresses.*');
                $nearestUserAddresses = $nearestUserAddresses->where('user_id', $queryParams['user_id']);
                $nearestUserAddresses = $nearestUserAddresses->selectRaw($distance . ' AS distance');
                $nearestUserAddresses = $nearestUserAddresses->whereRaw('(' . $distance . ')<=' . $radius);
                $nearestUserAddresses = $nearestUserAddresses->orderBy("distance", 'desc')->get()->toArray();
            }
            foreach ($data as $allUserAddress) {
                $userAddresses[$allUserAddress['id']] = $allUserAddress;
                $userAddresses[$allUserAddress['id']]['is_outside_delivery_area'] = false;
            }
            foreach ($nearestUserAddresses as $nearestUserAddress) {
                $userAddresses[$nearestUserAddress['id']] = $nearestUserAddress;
                $userAddresses[$nearestUserAddress['id']]['is_outside_delivery_area'] = true;
            }
            foreach ($userAddresses as $user_address) {
                $user_address_data[] = $user_address;
            }
            $data = $userAddresses;
            $userAddresses = '';
        }
        if (!empty($data)) {
            $result = array(
                'data' => $data,
                '_metadata' => $userAddresses
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canListUserAddress'));
/**
 * POST UsersUserIdUserAddressPost
 * Summary: Add user address
 * Notes: Add user address.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/user_addresses', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $userAddress = new Models\UserAddress($args);
    $validationErrorFields = $userAddress->validate($args);
    if (empty($validationErrorFields)) {
        unset($userAddress->state);
        unset($userAddress->city);
        unset($userAddress->country);
        unset($userAddress->location);
        //get country, state and city ids
        $userAddress->country_id = Models\Country::findCountryIdFromIso2($args['country']['iso2']);
        $userAddress->state_id = Models\State::findOrSaveAndGetStateId($args['state']['name'], $userAddress->country_id);
        $userAddress->city_id = Models\City::findOrSaveAndGetCityId($args['city']['name'], $userAddress->country_id, $userAddress->state_id);
        $userAddress->user_id = $authUser['id'];
        //$this->geohash = new Geohash();
        //$userAddress->hash = $this->geohash->encode(round($args['latitude'], 6), round($args['longitude'], 6));
        try {
            $userAddress->save();
            $result['data'] = Models\UserAddress::with('city', 'state', 'country')->where('id', $userAddress->id)->get();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'User address could not be added. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateUserAddress'));
/**
 * GET userAddressUserIdUserAddressUserAddressIdGet
 * Summary: Get user address book details
 * Notes: Get user address book details. \n
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_addresses/{userAddressId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $userAddresses = Models\UserAddress::Filter($queryParams)->find($request->getAttribute('userAddressId'));
    if (!empty($userAddresses)) {
        $result['data'] = $userAddresses->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewUserAddress'));
/**
 * PUT UserUserIdUserAddressesUserAddressIdPut
 * Summary: Update user address
 * Notes: Update user address. \n
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/user_addresses/{userAddressId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $userAddress = Models\UserAddress::find($request->getAttribute('userAddressId'));
    $validationErrorFields = $userAddress->validate($args);
    if (empty($validationErrorFields)) {
        $userAddress->fill($args);
        unset($userAddress->state);
        unset($userAddress->city);
        unset($userAddress->country);
        unset($userAddress->location);
        //get country, state and city ids
        $userAddress->country_id = Models\Country::findCountryIdFromIso2($args['country']['iso2']);
        $userAddress->state_id = Models\State::findOrSaveAndGetStateId($args['state']['name'], $userAddress->country_id);
        $userAddress->city_id = Models\City::findOrSaveAndGetCityId($args['city']['name'], $userAddress->country_id, $userAddress->state_id);
        try {
            $userAddress->save();
            $result['data'] = Models\UserAddress::with('city', 'state', 'country')->where('id', $userAddress->id)->get();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'User address could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateUserAddress'));
/**
 * DELETE UsersUserIdUserAddressUserAddressIdDelete
 * Summary: Delete Address
 * Notes: Delete Address\n
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/user_addresses/{userAddressId}', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $userAddress = Models\UserAddress::where('id', $request->getAttribute('userAddressId'));
    if ($authUser->role_id != \Constants\ConstUserTypes::ADMIN ) {
        $userAddress = $userAddress->where('user_id', $authUser['id']);
    }
    $userAddress = $userAddress->first();
    try {
        $userAddress->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteUserAddress'));
/**
 * GET TransactionGet
 * Summary: Get all transactions list.
 * Notes: Get all transactions list.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/transactions', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $transactions = Models\Transaction::Filter($queryParams)->paginate()->toArray();
        $transactions['data'] = transactionDescription($transactions['data']);
        $data = $transactions['data'];
        unset($transactions['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $transactions
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canListAllTransactions'));
/**
 * GET Paymentgateways list.
 * Summary: Paymentgateway list.
 * Notes: Paymentgateways list.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways/list', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    try {
        if (isPluginEnabled('Common/ZazPay')) {
            //Fetch sudopay info
            //$data['sudopay'] = SudopayPaymentGateway::with('sudopay_group')->get();
            $settings = Models\PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::SUDOPAY);
            $settings = $settings->get();
            foreach ($settings as $value) {
                $sudopay[$value->name] = $value->test_mode_value;
            }
            $s = new SudoPay_API(array(
                'api_key' => $sudopay['sudopay_api_key'],
                'merchant_id' => $sudopay['sudopay_merchant_id'],
                'website_id' => $sudopay['sudopay_website_id'],
                'secret_string' => $sudopay['sudopay_secret_string'],
                'is_test' => true,
                'cache_path' => APP_PATH . '/tmp/cache/'
            ));
            $data['sudopay'] = $s->callGetGateways();
            $data['sudopay']['enabled'] = true;
        }
        if (isPluginEnabled('Common/Wallet')) {
            $data['wallet'] = array(
                'enabled' => true
            );
        }
        if (isPluginEnabled('Common/COD')) {
            $data['cod'] = array(
                'enabled' => true
            );
        }
        if (isPluginEnabled('Common/Paypal')) {        
            $data['Paypal'] = array(
                'enabled' => true
            );
        }        
        return renderWithJson($data);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), $e->getMessage(), 1);
    }
});
/**
 * GET paymentGatewayGet
 * Summary: Get  payment gateways
 * Notes: Filter payment gateway.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $paymentGateways = Models\PaymentGateway::Filter($queryParams)->paginate()->toArray();
        $data = $paymentGateways['data'];
        unset($paymentGateways['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $paymentGateways
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canListPaymentGateway'));
/**
 * GET paymentGatewayGet
 * Summary: Get  payment gateways
 * Notes: Filter payment gateway.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways/{paymentGatewayId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $paymentGateway = Models\PaymentGateway::Filter($queryParams)->find($request->getAttribute('paymentGatewayId'));
    if (!empty($paymentGateway)) {
        $result['data'] = $paymentGateway->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
$app->PUT('/api/v1/payment_gateway_settings/{paymentGatewayId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $live_mode_value = isset($args['live_mode_value']) ? $args['live_mode_value'] : [];
    $test_mode_value = isset($args['test_mode_value']) ? $args['test_mode_value'] : [];
    $payment_gateway_settings = Models\PaymentGatewaySetting::where('payment_gateway_id', $request->getAttribute('paymentGatewayId'))->get()->toArray();
    try {
        foreach ($payment_gateway_settings as $payment_gateway_setting) {
            $field = $payment_gateway_setting['name'];
            $paymentGatewaySetting = Models\PaymentGatewaySetting::find($payment_gateway_setting['id']);
            if (array_key_exists($field, $live_mode_value)) {
                $paymentGatewaySetting->live_mode_value = $live_mode_value[$field];
            }
            if (array_key_exists($field, $test_mode_value)) {
                $paymentGatewaySetting->test_mode_value = $test_mode_value[$field];
            }
            $paymentGatewaySetting->update();
            if (isset($args['is_live_mode'])) {
                $is_test = empty($args['is_live_mode']) ? 1 : 0;
                Models\PaymentGateway::where('id', $request->getAttribute('paymentGatewayId'))->update(array(
                    "is_test_mode" => $is_test
                ));
            }
        }
        $result['data'] = $payment_gateway_settings;
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * PUT paymentGatewayspaymentGatewayIdPut
 * Summary: Update Payment gateway by its id
 * Notes: Update Payment gateway.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/payment_gateways/{paymentGatewayId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $paymentGateway = Models\PaymentGateway::find($request->getAttribute('paymentGatewayId'));
    $paymentGateway->fill($args);
    try {
        $paymentGateway->save();
        $result['data'] = $paymentGateway->toArray();
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canUpdatePaymentGateway'));
/**
 * GET OrdersOrderTrackidGet
 * Summary: Get user particular orders lists
 * Notes: Get user particular orders lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/orders/{trackId}/track', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    $order = Models\Order::Filter($queryParams)->where('track_id', $request->getAttribute('trackId'))->first();
    if (!empty($order)) {
        $result['data'] = $order->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});