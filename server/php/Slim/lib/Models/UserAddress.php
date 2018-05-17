<?php
/**
 * UserAddress
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

class UserAddress extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_addresses';
    protected $fillable = array(
        'user_id',
        'title',
        'building_address',
        'address2',
        'landmark',
        'city_id',
        'state_id',
        'country_id',
        'zip_code',
        'latitude',
        'longitude',
        'is_active',
        'hash'
    );
    protected $casts = array(
        'is_active' => 'integer',
        'country_id' => 'integer',
        'state_id' => 'integer',
        'city_id' => 'integer',
        'longitude' => 'double',
        'latitude' => 'double',
        'user_id' => 'integer'
    );
    public $rules = array(
        'user_id' => 'sometimes|required|integer',
        'title' => 'sometimes|required',
        'building_address' => 'sometimes|required',
        'address2' => 'sometimes|required',
        'zip_code' => 'sometimes|required'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
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
    public function order()
    {
        return $this->hasMany('Models\Order', 'user_address_id', 'id');
    }    
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('title', 'ilike', "%$search%");
                $q1->orWhere('building_address', 'ilike', "%$search%");
                $q1->orWhere('landmark', 'ilike', "%$search%");
                $q1->orWhereHas('city', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
                $q1->orWhereHas('state', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
                $q1->orWhereHas('country', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'ilike', "%$search%");
                });
            });
        }        
    }
}
