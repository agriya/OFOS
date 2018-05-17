<?php
/**
 * OrderItem
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

class OrderItem extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_items';
    protected $fillable = array(
        'order_id',
        'restaurant_menu_id',
        'restaurant_menu_price_id',
        'quantity',
        'price',
        'total_price'
    );
    protected $casts = array (
        'order_id' => 'integer',
        'restaurant_menu_id'  => 'integer',
        'restaurant_menu_price_id' => 'integer',
        'price' => 'double',
        'total_price' => 'double',
        'quantity'  => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $q1->where('price', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant_menu', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant_addon_item', function ($q) use ($params) {
                    $q->Where('name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }   
    public function order()
    {
        return $this->belongsTo('Models\Order', 'order_id', 'id');
    }
    public function restaurant_menu()
    {
        return $this->belongsTo('Models\RestaurantMenu', 'restaurant_menu_id', 'id');
    }
    public function restaurant_menu_price()
    {
        return $this->belongsTo('Models\RestaurantMenuPrice', 'restaurant_menu_price_id', 'id');
    }
    public function order_item_addon()
    {
        return $this->hasMany('Models\OrderItemAddon', 'order_item_id', 'id');
    }    
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        self::saved(function ($orderItem) {
            if (!empty($orderItem->restaurant_menu_id)) {
                $oder_status = array (
                    \Constants\OrderStatus::PAYMENTPENDING,
                    \Constants\OrderStatus::PAYMENTFAILED,
                    \Constants\OrderStatus::REJECTED
                );
                $sold_quantity =  OrderItem::where('restaurant_menu_id', $orderItem->restaurant_menu_id)->whereHas('order', function ($query) use ($oder_status) {
                    $query->whereNotIn('order_status_id', $oder_status);
                })->sum('quantity');
                $odered_count =   OrderItem::where('restaurant_menu_id', $orderItem->restaurant_menu_id)->count();
                RestaurantMenu::where('id', $orderItem->restaurant_menu_id)->update(['sold_quantity' => $sold_quantity, 'ordered_menu_count' => $odered_count]);
            }
            
        });
    }
}
