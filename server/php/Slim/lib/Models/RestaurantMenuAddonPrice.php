<?php
/**
 * RestaurantMenuAddonPrice
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

class RestaurantMenuAddonPrice extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_menu_addon_prices';
    protected $fillable = array(
        'restaurant_menu_id',
        'restaurant_addon_item_id',
        'restaurant_addon_id',
        'is_free',
        'is_active',
        'price'
    );
    protected $casts = array(
        'restaurant_menu_id' => 'integer',
        'restaurant_addon_item_id' => 'integer',
        'restaurant_addon_id' => 'integer',
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'double'
    );
    public function restaurant_menu()
    {
        return $this->belongsTo('Models\RestaurantMenu', 'restaurant_menu_id', 'id');
    }
    public function restaurant_addon()
    {
        return $this->belongsTo('Models\RestaurantAddon', 'restaurant_addon_id', 'id');
    }
    public function restaurant_addon_item()
    {
        return $this->belongsTo('Models\RestaurantAddonItem', 'restaurant_addon_item_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['restaurant_id'])) {
            $query->where('restaurant_id', $params['restaurant_id']);            
        }
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('price', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant_menu', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant_addon', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant_addon_item', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
}
