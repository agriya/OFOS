<?php
/**
 * CartAddon
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

class CartAddon extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cart_addons';
    protected $fillable = array(
        'cart_id',
        'restaurant_addon_id',
        'restaurant_addon_item_id',
        'restaurant_menu_addon_price_id',
        'price'
    );
    protected $casts = array (
        'cart_id' => 'integer',
        'restaurant_addon_id'  => 'integer',
        'restaurant_addon_item_id' => 'integer',
        'price' => 'double',
        'restaurant_menu_addon_price_id'  => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('price', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant_addon', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant_addon_item', function ($q) use ($params) {
                    $q->Where('name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }   
    public function cart()
    {
        return $this->belongsTo('cart');
    }
    public function restaurant_menu_addon_price()
    {
        return $this->belongsTo('Models\RestaurantMenuAddonPrice', 'restaurant_menu_addon_price_id', 'id');
    }
    public function restaurant_addon()
    {
        return $this->belongsTo('Models\RestaurantAddon', 'restaurant_addon_id', 'id');
    }
    public function restaurant_addon_item()
    {
        return $this->belongsTo('Models\RestaurantAddonItem', 'restaurant_addon_item_id', 'id');
    }
}
