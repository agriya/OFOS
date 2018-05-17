<?php
/**
 * City
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

class City extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cities';
    protected $fillable = array(
        'name',
        'country_id',
        'state_id',
        'is_active'
    );
    protected $casts = array (
        'country_id' => 'integer',
        'state_id'  => 'integer',
        'is_active' => 'integer'
    );
    public $rules = array(
        'name' => 'sometimes|required',
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('country', function ($q) use ($params) {
                    $q->where('countries.name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('state', function ($q) use ($params) {
                    $q->Where('states.name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
    public function user_address()
    {
        return $this->hasOne('Models\UserAddress');
    }
    public function state()
    {
        return $this->belongsTo('Models\State', 'state_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country', 'country_id', 'id');
    }
    /**
    * Findorsave city details
    *
    * @params string $data
    * @params int $country_id
    * @params int $state_id
    *
    * @return int IP id
    */
    public function findOrSaveAndGetCityId($data, $country_id, $state_id)
    {
        $city = new City;
        $city_list = $city->where('name', $data)->where('state_id', $state_id)->where('country_id', $country_id)->select('id')->first();
        if (!empty($city_list)) {
            return $city_list['id'];
        } else {
            $city->name = $data;
            $city->country_id = $country_id;
            $city->state_id = $state_id;
            $city->save();
            return $city->id;
        }
    }
}
