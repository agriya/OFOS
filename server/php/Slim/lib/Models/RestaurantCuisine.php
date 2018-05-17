<?php
/**
 * RestaurantCuisine
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

class RestaurantCuisine extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_cuisines';
    protected $fillable = array(
        'cuisine_id',
        'restaurant_id'
    );
    protected $casts = array(
        'cuisine_id' => 'integer',
        'restaurant_id' => 'integer'
    );
    public $rules = array(
        'restaurant_id' => 'sometimes|required|integer',
        'cuisine_id' => 'sometimes|required|integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('cuisine', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });                
                
            });
        }
    }   
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function cuisine()
    {
        return $this->belongsTo('Models\Cuisine', 'cuisine_id', 'id');
    }
}
