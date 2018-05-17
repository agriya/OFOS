<?php
/**
 * Cart
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

class Cart extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'carts';
    protected $fillable = array(
        'cookie_id',
        'user_id',
        'restaurant_menu_id',
        'restaurant_menu_price_id',
        'quantity',
        'price',
        'total_price',
        'restaurant_id'
    );
    protected $casts = array (
        'user_id' => 'integer',
        'restaurant_menu_id'  => 'integer',
        'restaurant_menu_price_id' => 'integer',
        'quantity'  => 'integer',
        'price' => 'double',
        'total_price' => 'double',
        'restaurant_id'  => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
         if (!empty($params['cookie_id'])) {
            $query->where('cookie_id', $params['cookie_id']);
         }
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('price', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('user', function ($q) use ($params) {
                    $q->where('username', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->Where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant_menu', function ($q) use ($params) {
                    $q->Where('name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }   
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function cart_addon()
    {
        return $this->hasMany('Models\CartAddon')->with('restaurant_addon', 'restaurant_addon_item');
    }
    public function restaurant_menu()
    {
        return $this->belongsTo('Models\RestaurantMenu', 'restaurant_menu_id', 'id');
    }
    public function restaurant_menu_price()
    {
        return $this->belongsTo('Models\RestaurantMenuPrice', 'restaurant_menu_price_id', 'id');
    }
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function addCart($body)
    {
        $cart = new Cart();
        $cart->fill($body);
        try {
            $cart->total_price = $cart->price;
            $cart->save();
            $addon_price = 0;
            if (!empty($body['restaurant_menu_addon_price'])) {
                foreach ($body['restaurant_menu_addon_price'] as $key => $value) {
                    $addonPrice = RestaurantMenuAddonPrice::find($value['id']);
                    $cart_addon = new CartAddon;
                    $cart_addon->cart_id = $cart->id;
                    $cart_addon->restaurant_addon_id = $addonPrice->restaurant_addon_id;
                    $cart_addon->restaurant_menu_addon_price_id = $addonPrice->id;
                    $cart_addon->price = $addonPrice->price;
                    $cart_addon->restaurant_addon_item_id = $addonPrice->restaurant_addon_item_id;
                    $cart_addon->save();
                    $addon_price = $addon_price + $cart_addon->price;
                }
            }
            $cart->total_price = $cart->price + $addon_price;
            $cart->save();
            $cartData = Cart::with('cart_addon', 'restaurant_menu', 'restaurant_menu_price', 'restaurant');
            $cartData = $cartData->where('cookie_id', $body['cookie_id']);
            $cart_data['cart'] = $cartData->get()->toArray();
            $response = array(
                'data' => $cart_data,
                'error' => array(
                    'code' => 0,
                    'message' => 'Menu has been added.'
                )
            );
        } catch (Exception $e) {
            $response = array(
                'data' => '',
                'error' => array(
                    'code' => 1,
                    'message' => $e->getMessage(),
                    'fields' => ''
                )
            );
        }
        return $response;
    }
    public function updateCart($id, $body)
    {
        $cart_data = array();
        $total_price = 0;
        $cart = Cart::where('id', $id)->first();
        $addons = CartAddon::where('cart_id', $cart->id)->get();
        if (!empty($cart)) {
            if ($cart['quantity'] == 1 && $body['quantity'] == 0) {
                $cookie_id = $cart->cookie_id;
                $cart->delete();
                $cart = Cart::with('cart_addon', 'restaurant_menu', 'restaurant_menu_price', 'restaurant')->where('cookie_id', $cookie_id)->get();
                if (!empty($cart)) {
                    $cart_data['cart'] = $cart->toArray();
                } else {
                    $cart_data['cart'] = [];
                }
                $response = array(
                    'data' => $cart_data,
                    'error' => array(
                        'code' => 0,
                        'message' => 'Cart has been Updated.'
                    )
                );
            } else {
                if (isset($body['quantity']) && $body['quantity'] == 1) {
                    $cart->quantity = $cart['quantity'] + 1;
                } else {
                    $cart->quantity = $cart['quantity'] - 1;
                }
                $total_addon_price = 0;
                $total_price = $cart['price'] * $cart->quantity;
                if (count($addons) > 0) {
                    $cart_data['addons'] = $addons->toArray();
                    foreach ($cart_data['addons'] as $key => $value) {
                        $total_addon_price = $total_addon_price + $value['price'];
                    }
                    $total_addon_price = $total_addon_price * $cart->quantity;
                }
                $cart->total_price = $total_price + $total_addon_price;
                $cart->save();
                $cartData = Cart::with('cart_addon', 'restaurant_menu', 'restaurant_menu_price', 'restaurant');
                $cartData = $cartData->where('cookie_id', $body['cookie_id']);
                $cart_data['cart'] = $cartData->get()->toArray();
                $response = array(
                    'data' => $cart_data,
                    'error' => array(
                        'code' => 0,
                        'message' => 'Cart has been Updated.'
                    )
                );
            }
        } else {
            $response = array(
                'data' => '',
                'error' => array(
                    'code' => 1,
                    'message' => 'Cart could not be updated',
                    'fields' => ''
                )
            );
        }
        return $response;
    }
}
