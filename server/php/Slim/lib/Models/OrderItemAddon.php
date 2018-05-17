<?php
/**
 * OrderItemAddon
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

class OrderItemAddon extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_item_addons';
    protected $fillable = array(
        'order_id',
        'order_item_id',
        'restaurant_addon_id',
        'restaurant_menu_addon_price_id',
        'price'
    );
    protected $casts = array (
        'order_id' => 'integer',
        'order_item_id'  => 'integer',
        'restaurant_addon_id' => 'integer',
        'price' => 'double',
        'restaurant_menu_addon_price_id' => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $q1->where('price', 'ilike', '%' . $params['q'] . '%');
            });
        }
    }   
    public function order()
    {
        return $this->belongsTo('Models\Order', 'order_id', 'id');
    }
    public function order_item()
    {
        return $this->belongsTo('Models\OrderItem', 'order_item_id', 'id');
    }
    public function restaurant_menu_addon_price()
    {
        return $this->belongsTo('Models\RestaurantMenuAddonPrice', 'restaurant_menu_addon_price_id', 'id');
    }
    public function restaurant_addon()
    {
        return $this->belongsTo('Models\RestaurantAddon', 'restaurant_addon_id', 'id');
    }
}
