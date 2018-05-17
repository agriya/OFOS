<?php
/**
 * RestaurantBranch
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

class RestaurantBranch extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_branches';
    protected $fillable = array(
        'restaurant_id',
        'name',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'is_active',
        'latitude',
        'longitude',
        'address1'
    );
    public $rules = array(
        'restaurant_id' => 'sometimes|required|integer',
        'name' => 'sometimes|required',
        'address' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->whereHas('restaurant', function ($q) use ($search) {
                $q->where('name', 'ilike', "%$search%");
            });
        }
    }
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function city()
    {
        return $this->belongsTo('Models\City', 'city_id', 'id');
    }
    public function state()
    {
        return $this->belongsTo('Models\State', 'state_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country', 'country_id', 'id');
    }
}
