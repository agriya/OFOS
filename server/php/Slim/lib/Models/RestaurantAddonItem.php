<?php
/**
 * RestaurantAddonItem
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

class RestaurantAddonItem extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_addon_items';
    protected $fillable = array (
        'restaurant_addon_id', 
        'name',
        'is_active'
    );
    protected $casts = array (
        'restaurant_addon_id' => 'integer',
        'is_active' => 'integer'
    );
    public $rules = array( 
        'restaurant_addon_id' => 'sometimes|required|integer',
        'name' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant_addon', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });                
            });
        }
    }   
    public function restaurant_addon()
    {
        return $this->belongsTo('Models\RestaurantAddon', 'restaurant_addon_id', 'id');
    }
    public function restaurant_menu_addon_price()
    {
        return $this->hasMany('Models\RestaurantMenuAddonPrice', 'restaurant_addon_item_id', 'id');
    }
    public function cart_addon()
    {
        return $this->hasMany('Models\CartAddon', 'restaurant_addon_item_id', 'id');
    }    
}
