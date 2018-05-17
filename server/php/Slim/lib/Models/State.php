<?php
/**
 * State
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

class State extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'states';
    protected $fillable = array(
        'name',
        'country_id',
        'is_active'
    );
    protected $casts = array(
        'country_id' => 'integer',
        'is_active' => 'integer'
    );
    public $rules = array(
        'name' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('country', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });                
            });
        }
    }
    public function user_address()
    {
        return $this->has_one('Models\UserAddress');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country', 'country_id', 'id');
    }
    /**
    * Findorsave state details
    *
    * @params string $data
    * @params int $country_id
    *
    * @return int IP id
    */
    public function findOrSaveAndGetStateId($data, $country_id)
    {
        $state = new State;
        $state_list = $state->where('name', $data)->where('country_id', $country_id)->select('id')->first();
        if (!empty($state_list)) {
            return $state_list['id'];
        } else {
            $state->name = $data;
            $state->country_id = $country_id;
            $state->save();
            return $state->id;
        }
    }
}
