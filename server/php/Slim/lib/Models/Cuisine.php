<?php
/**
 * Cuisine
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

class Cuisine extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cuisines';
    protected $fillable = array(
        'name',
        'is_active'
    );
    protected $casts = array(
        'is_active' => 'integer'
    );
    public $rules = array(
        'name' => 'sometimes|required'
    );
    public function restaurant_cuisine()
    {
        return $this->hasMany('Models\RestaurantCuisine','cuisine_id','id');
    }
    public function restaurant_menu()
    {
        return $this->hasMany('Models\RestaurantMenu','cuisine_id','id');
    }    
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('name', 'ilike', "%$search%");                              
            });
        } 
        if (!empty($params['restaurant_id'])) {
            $query->where('restaurant_id', $params['restaurant_id']);            
        }        
    }   
}
