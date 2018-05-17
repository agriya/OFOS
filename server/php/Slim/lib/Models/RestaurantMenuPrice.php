<?php
/**
 * RestaurantMenuPrice
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

class RestaurantMenuPrice extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_menu_prices';
    protected $fillable = array(
        'restaurant_menu_id',
        'price_type_id',
        'price_type_name',
        'price'
    );
    protected $casts = array(
        'restaurant_menu_id' => 'integer',
        'price_type_id' => 'integer',
        'price' => 'double'
    );
    public function restaurant_menu()
    {
        return $this->belongsTo('Models\RestaurantMenu', 'restaurant_menu_id', 'id');
    }
    public function cart()
    {
        return $this->hasMany('Models\Cart', 'restaurant_menu_price_id', 'id');
    }   
    public function order_item()
    {
        return $this->hasMany('Models\OrderItem', 'order_item_id', 'id');
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
                $q1->orWhere('price_type_name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant_menu', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
}
