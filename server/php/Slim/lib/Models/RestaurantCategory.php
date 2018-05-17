<?php
/**
 * RestaurantCategory
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

class RestaurantCategory extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_categories';
    protected $fillable = array(
        'restaurant_id',
        'name',
        'is_active',
        'display_order'
    );
    protected $casts = array(
        'restaurant_id' => 'integer',
        'is_active' => 'integer',
        'display_order'  => 'integer'
    );
    public $rules = array(
        'restaurant_id' => 'sometimes|required|integer',
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
                $q1->Where('name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                
            });
        }
    }
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function attachment()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id')->where('class', 'RestaurantCategory');
    }
    public function image()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'id')->where('class', 'RestaurantCategory');
    }
    public function menu()
    {
        return $this->hasMany('Models\RestaurantMenu', 'restaurant_category_id');
    }
    public function restaurant_addon()
    {
        return $this->hasMany('Models\RestaurantAddon', 'restaurant_category_id', 'id');
    }
    public function restaurant_menu()
    {
        return $this->hasMany('Models\RestaurantMenu', 'restaurant_category_id', 'id');
    }    
}
