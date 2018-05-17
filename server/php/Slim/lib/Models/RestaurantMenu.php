<?php
/**
 * RestaurantMenu
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

class RestaurantMenu extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_menus';
    protected $fillable = array(
        'restaurant_id',
        'cuisine_id',
        'restaurant_category_id',
        'menu_type_id',
        'name',
        'description',
        'is_addon',
        'is_popular',
        'is_spicy',
        'is_active',
        'color',
        'stock',
        'sold_quantity'
    );
    protected $casts = array(
        'restaurant_id' => 'integer',
        'cuisine_id'  => 'integer',
        'restaurant_category_id' => 'integer',
        'menu_type_id' => 'integer',
        'is_addon' => 'boolean',
        'is_popular' => 'boolean',
        'is_spicy' => 'boolean',
        'is_active' => 'boolean',
        'sold_quantity' => 'integer'
    );
    public $rules = array(
        'restaurant_id' => 'sometimes|required|integer',
        'cuisine_id' => 'sometimes',
        'restaurant_category_id' => 'sometimes|required|integer',
        'menu_type_id' => 'sometimes|required|integer',
        'name' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['restaurant_id'])) {
            $query->where('restaurant_id', $params['restaurant_id']);            
        }
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('cuisine', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant_categories', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
    public function menuPrice($menu_id, $body)
    {
        $menuprice = new RestaurantMenuPrice;
        $menuprice->restaurant_menu_id = $menu_id;
        $menuprice->price_type_id = \Constants\MenuPriceTypes::FIXED;
        $menuprice->price_type_name = 'Fixed';
        $menuprice->price = $body['price'];
        $menuprice->save();
        /*if (!empty($body['RestaurantMenuPrice'])) {
            foreach ($body['RestaurantMenuPrice'] as $restaurant_menu_price) {
                $menuprice = new Models\RestaurantMenuPrice;
                //menu price comes along with addon price
                if (isset($restaurant_menu_price['price_type_id']) && $restaurant_menu_price['price_type_id'] == \Constants\MenuPriceTypes::FIXED) { //Fixed
                    $menuprice->price_type_name = 'Fixed';
                } else {
                    $menuprice->price_type_name = $restaurant_menu_price['price_type_name'];
                }
                $menuprice->restaurant_menu_id = $menu_id;
                $menuprice->price_type_id = $restaurant_menu_price['price_type_id'];
                $menuprice->price = $restaurant_menu_price['price'];
                $menuprice->save();
                if (isset($body['is_addon']) && $body['is_addon'] == 1) {
                    foreach ($body['RestaurantMenuAddon'] as $key => $value) {
                        $menupriceaddon = new RestaurantMenuAddonPrice;
                        $menupriceaddon->restaurant_menu_id = $menu_id;
                        $menupriceaddon->restaurant_addon_id = $key;
                        $menupriceaddon->restaurant_menu_price_id = $menuprice->id;
                        for ($i = 0; $i < count($body['RestaurantMenuAddon'][$key]); $i++) {
                            if (!empty($body['RestaurantMenuAddon'][$key]['is_free'])) {
                                $menupriceaddon->price = 0;
                            } else {
                                $menupriceaddon->price = $body['RestaurantMenuAddon'][$key][$i];
                            }
                            $menupriceaddon->save();
                        }
                    }
                }
            }
        }*/
        return true;
    }
    public function restaurant_menu_price()
    {
        return $this->hasMany('Models\RestaurantMenuPrice', 'restaurant_menu_id', 'id');
    }
    public function restaurant_menu_addon_price()
    {
        return $this->hasMany('Models\RestaurantMenuAddonPrice', 'restaurant_menu_id', 'id');
    }
    public function attachment()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id')->where('class', 'RestaurantMenu');
    }
    public function image()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'id')->where('class', 'RestaurantMenu');
    }
    public function cuisine()
    {
        return $this->belongsTo('Models\Cuisine', 'cuisine_id', 'id');
    }
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function restaurant_categories()
    {
        return $this->belongsTo('Models\RestaurantCategory', 'restaurant_category_id', 'id');
    }
    public function restaurant_addon()
    {
        return $this->hasMany('Models\RestaurantAddon', 'restaurant_category_id', 'restaurant_category_id');
    }
    public function cart()
    {
        return $this->hasMany('Models\Cart', 'restaurant_menu_id', 'id');
    }   
    public function order_item()
    {
        return $this->hasMany('Models\OrderItem', 'restaurant_menu_id', 'id');
    }      
}
