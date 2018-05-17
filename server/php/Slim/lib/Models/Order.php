<?php
/**
 * Order
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

class Order extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';
    protected $fillable = array(
        'user_id',
        'restaurant_id',
        'restaurant_branch_id',
        'restaurant_delivery_person_id',
        'order_status_id',
        'total_price',
        'delivery_charge',
        'sales_tax',
        'user_address_id',
        'address',
        'city_id',
        'state_id',
        'country_id',
        'latitude',
        'longitude',
        'zip_code',
        'comment',
        'payment_gateway_id',
        'later_delivery_date',
        'is_as_soon_as_delivery',
        'is_pickup_or_delivery',
        'gateway_id',
        'site_fee',
        'delivered_date',
        'success_url',
        'cancel_url',
        'track_id'
    );
    protected $casts = array(
        'user_id'  => 'integer',
        'restaurant_id' => 'integer',
        'restaurant_branch_id'  => 'integer',
        'restaurant_delivery_person_id'  => 'integer',
        'order_status_id' => 'integer',
        'total_price'  => 'double',
        'country_id'  => 'integer',
        'state_id' => 'integer',
        'city_id'  => 'integer',
        'user_address_id'  => 'integer',
        'order_status_id' => 'integer',
        'delivery_charge'  => 'double',
        'sales_tax'  => 'double',
        'site_fee'  => 'double',
        'payment_gateway_id' => 'integer',
        'gateway_id' => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->Where(function ($q1) use ($search) {
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'ilike', "%$search%");
                });
                $q1->orWhereHas('delivery_person', function ($delivery) use ($search) {
                    $delivery->whereHas('user', function ($q) use ($search) {
                        $q->where('username', 'ilike', "%$search%");
                    });
                });
                $q1->orWhereHas('restaurant', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });                
                $q1->orWhereHas('order_status', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
                if ($search == 'Pickup' || $search == 'pickup') {
                    $q1->orWhere('is_pickup_or_delivery', 0);
                } elseif ($search == 'Delivery' || $search == 'delivery') {
                    $q1->orWhere('is_pickup_or_delivery', 1);
                }
            });
        }
        if (!empty($params['filter'])) {
            if ($params['filter'] == 'pending') {
                $query->where('order_status_id', \Constants\OrderStatus::PENDING);
            } elseif ($params['filter'] == 'processing') {
                $query->where('order_status_id', \Constants\OrderStatus::PROCESSING);
            } elseif ($params['filter'] == 'rejected') {
                $query->where('order_status_id', \Constants\OrderStatus::REJECTED);
            } elseif ($params['filter'] == 'delivered') {
                $query->where('order_status_id', \Constants\OrderStatus::DELIVERED);
            } elseif ($params['filter'] == 'reviewed') {
                $query->where('order_status_id', \Constants\OrderStatus::REVIEWED);
            } elseif ($params['filter'] == 'payment failed') {
                $query->where('order_status_id', \Constants\OrderStatus::PAYMENTFAILED);
            } elseif ($params['filter'] == 'payment pending') {
                $query->where('order_status_id', \Constants\OrderStatus::PAYMENTPENDING);
            }
        }
        if (!empty($params['restaurant_delivery_person_id'])) {
            $query->where('restaurant_delivery_person_id', $params['restaurant_delivery_person_id']);
        }
        if (!empty($params['restaurant_id'])) {
            $query->where('restaurant_id', $params['restaurant_id']);            
        }
        if (!empty($params['user_id'])) {
            $query->where('user_id', $params['user_id']);            
        }        
        if (!empty($params['restaurant_branch_id'])) {
            $query->where('restaurant_branch_id', $params['restaurant_branch_id']);            
        }        
        $supervisor_status = array(
            \Constants\OrderStatus::PROCESSING,
            \Constants\OrderStatus::DELIVERED,
            \Constants\OrderStatus::DELIVERYPERSONASSIGNED,
            \Constants\OrderStatus::PENDING
        );
        $delivery_person_status = array(
            \Constants\OrderStatus::DELIVERED,
            \Constants\OrderStatus::DELIVERYPERSONASSIGNED,
            \Constants\OrderStatus::OUTFORDELIVERY
        );
        $payment_status = array(
            \Constants\OrderStatus::PAYMENTPENDING
        );              
        if (!empty($authUser) && $authUser->role_id == \Constants\ConstUserTypes::SUPERVISOR) {
            /*$supervisorId = Supervisor::where('user_id', $authUser->id)->first();
            $delivery_persons = DeliveryPerson::where('restaurant_supervisor_id', $supervisorId->id)->get()->toArray();
            foreach ($delivery_persons as $delivery_person) {
                $ids[] = $delivery_person['id'];
            }
            if (!empty($ids)) {
                $query->whereIn('orders.order_status_id', $supervisor_status)->whereIn('orders.restaurant_delivery_person_id', $ids);
            }*/
        } elseif (!empty($authUser) && $authUser->role_id == \Constants\ConstUserTypes::DELIVERYPERSON) {
            $delivery_person = DeliveryPerson::where('user_id', $authUser->id)->first();
            $query->where('orders.restaurant_delivery_person_id', $delivery_person->id)->whereIn('orders.order_status_id', $delivery_person_status);
        }               
    }
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function restaurant_branch()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_branch_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function order_items()
    {
        return $this->hasMany('Models\OrderItem')->with('restaurant_menu', 'restaurant_menu_price');
    }
    public function order_item_addons()
    {
        return $this->hasMany('Models\OrderItemAddon');
    }
    public function order_item()
    {
        return $this->hasMany('Models\OrderItem','order_id','id');
    }    
    public function city()
    {
        return $this->belongsTo('Models\City', 'city_id', 'id');
    }
    public function state()
    {
        return $this->belongsTo('Models\State', 'state_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country', 'country_id', 'id');
    }
    public function delivery_person()
    {
        return $this->belongsTo('Models\DeliveryPerson', 'restaurant_delivery_person_id', 'id');
    }
    public function order_status()
    {
        return $this->belongsTo('Models\OrderStatus', 'order_status_id', 'id');
    }
    public function user_address()
    {
        return $this->belongsTo('Models\UserAddress', 'user_address_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
    public function sudopay_transaction_log()
    {
        return $this->hasOne('Models\SudopayTransactionLog', 'foreign_id');
    }
    public function wallet_transaction_log()
    {
        return $this->hasOne('Models\WalletTransactionLog', 'foreign_id');
    }
    public function restaurant_delivery_person_order()
    {
        return $this->hasMany('Models\RestaurantDeliveryPersonOrder', 'order_id', 'id');
    }     
    public function restaurant_review()
    {
        return $this->hasMany('Models\RestaurantReview', 'order_id', 'id');
    }        
    protected static function boot()
    {
        global $authUser;
        parent::boot();
       /*self::saving(function ($data) use ($authUser) {
            if (!empty($data->restaurant->supervisors)) {
                foreach ($data['restaurant']['supervisors'] as  $supervisors) {
                    if ($authUser['id'] == $supervisors['user_id']) {
                        Order::couponCountUpdation($data->coupon_id);
                        return true;
                    }
                }
            }
            if ($authUser['role_id'] == \Constants\ConstUserTypes::ADMIN || $authUser['id'] == $data->user_id || $authUser['id'] == $data->restaurant->user_id || $authUser['id'] == $data->delivery_person->user_id ) {
                Order::couponCountUpdation($data->coupon_id);
                return true;
            }
            return false;
        });*/
        self::saved(function ($data) use ($authUser) {
            Order::couponCountUpdation($data->coupon_id);
        });
        /*self::deleting(function ($data) use ($authUser) {
            if ($authUser['role_id'] == \Constants\ConstUserTypes::ADMIM || $authUser['id'] == $data->user_id || $authUser['id'] == $data->restaurant->user_id || $authUser['id'] == $data->delivery_person->user_id ) {
                Order::couponCountUpdation($data->coupon_id);
                return true;
            }
            return false;
        });*/
    }
    public function couponCountUpdation($coupon_id)
    {
        if (!empty($coupon_id)) {
            $no_of_quantity_used = Order::where('coupon_id', $coupon_id)->whereNotIn('order_status_id', [\Constants\OrderStatus::PAYMENTPENDING, \Constants\OrderStatus::PAYMENTFAILED])->count();
            Coupon::where('id', $coupon_id)->update(['no_of_quantity_used' => $no_of_quantity_used]);
        }
    }
    public function processCaptured($payment_response, $id, $staus = '')
    {
        global $_server_domain_url;
        $order = Order::with('restaurant.user', 'payment_gateway', 'user')->whereIn('order_status_id', [\Constants\OrderStatus::PAYMENTPENDING, \Constants\OrderStatus::PAYMENTFAILED])->find($id);
        if (!empty($order)) {
            $order->order_status_id = \Constants\OrderStatus::AWAITINGCODVALIDATION;
            if (empty($staus)) {
                $order->order_status_id = \Constants\OrderStatus::PENDING;
                Payment::addTransactions($order, 'Order');
            }
            if (!empty($payment_response['paykey'])) {
                if ($order->payment_gateway_id == \Constants\PaymentGateways::SUDOPAY) {
                  $order->zazpay_pay_key = $payment_response['paykey'];   
                } elseif ($order->payment_gateway_id == \Constants\PaymentGateways::PAYPAL) {
                  $order->paypal_pay_key = $payment_response['paykey'];
                }
            }
            $order->update();
            unset($_COOKIE['new_cart_cookie']);
            Order::updateRestaurantOrder($order);
            
            $emailFindReplace = array(
                '##RESTAURANT_NAME##' => $order['restaurant']['name'],
                '##ORDERID##' => $order['id'],
                '##ORDERURL##' => $_server_domain_url . '/orders/' . $order['track_id'] . '/track'
            );
            sendMail('ordermailtorestaurant', $emailFindReplace, $order['restaurant']['user']['email']);
            if (isPluginEnabled('Order/Sms')) {
                $message = array(
                    'order_id' => $id,
                    'message_type' => 'paid'
                );
                sendSMS($message, $order->user_id);
            }
            if (isPluginEnabled('Order/Mobile')) {
                $message = array(
                    'order_id' => $id,
                    'user_id' => $order->user_id,                    
                    'message_type' => 'paid'
                );
                addPushNotification($order->user_id, $message);
            }                       
        }
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment successfully completed'
            )
        );
        return $response;
    }
    public function processInitiated($payment_response)
    {
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment initiated',
                'fields' => ''
            )
        );
        return $response;
    }
    public function processPending($payment_response)
    {
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment is in pending state.'
            )
        );
        return $response;
    }
    public function updateRestaurantOrder($order)
    {
        $restaurant = Restaurant::where('id', $order->restaurant_id)->first();
        if ($order->order_status_id == \Constants\OrderStatus::PENDING || $order->order_status_id == \Constants\OrderStatus::AWAITINGCODVALIDATION) {
            $restaurant->total_orders = $restaurant->total_orders + 1;
            $restaurant->total_revenue = $restaurant->total_revenue + $order->total_price;
            $restaurant->save();
        } elseif ($order->order_status_id == \Constants\OrderStatus::REJECTED || $order->order_status_id == \Constants\OrderStatus::CANCEL) {
            $restaurant->total_orders = $restaurant->total_orders - 1;
            $restaurant->total_revenue = $restaurant->total_revenue - $order->total_price;
            $restaurant->save();
        }
    }
    public function refundPayment($order)
    {
        $user = User::where('id', $order->user_id)->first();
       if (!empty($user)) {
            $user->available_wallet_amount = $user->available_wallet_amount + $order->total_price;
            $user->update();
            $order->order_status_id = \Constants\OrderStatus::REJECTED;
            $order->save();
            Order::updateRestaurantOrder($order);
            Payment::addTransactions($order, 'Order');
            return true;
        }
    }
}
