<?php
/**
 * RestaurantAddon
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

class RestaurantAddon extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_addons';
    protected $fillable = array(
        'restaurant_id',
        'restaurant_category_id',
        'name',
        'is_active',
        'is_multiple'
    );
    protected $casts = array(
        'restaurant_id' => 'integer',
        'restaurant_category_id' => 'integer',
        'is_active' => 'integer',
        'is_multiple'  => 'integer'
    );
    public $rules = array(
        'restaurant_id' => 'sometimes|required|integer',
        'restaurant_category_id' => 'sometimes|required|integer',
        'name' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant_category', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });                
            });
        }
    }
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function restaurant_category()
    {
        return $this->belongsTo('Models\RestaurantCategory', 'restaurant_category_id', 'id');
    }
    public function restaurant_addon_item()
    {
        return $this->hasMany('Models\RestaurantAddonItem', 'restaurant_addon_id', 'id');
    }
    public function cart_addon()
    {
        return $this->hasMany('Models\CartAddon', 'restaurant_addon_id', 'id');
    } 
    public function order_item_addon()
    {
        return $this->hasMany('Models\OrderItemAddon', 'restaurant_addon_id', 'id');
    }  
    public function restaurant_menu_addon_price()
    {
        return $this->hasMany('Models\RestaurantMenuAddonPrice', 'restaurant_addon_id', 'id');
    }            
}
