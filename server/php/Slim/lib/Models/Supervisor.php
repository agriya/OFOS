<?php
/**
 * Supervisor
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

class Supervisor extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_supervisors';
    protected $fillable = array(
        'user_id',
        'restaurant_id',
        'restaurant_branch_id',
        'is_active'
    );
    protected $casts = array(
        'user_id' => 'integer',
        'restaurant_id' => 'integer',
        'restaurant_branch_id' => 'integer',
        'is_active' => 'integer'
    );
    public $rules = array(
        'restaurant_id' => 'sometimes|required|integer',
        'restaurant_branch_id' => 'sometimes|required|integer',
        'user_id' => 'sometimes|required|integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->orWhereHas('user', function ($q) use ($params) {
                    $q->where('username', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant_branch', function ($q) use ($params) {
                    $q->Where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->Where('name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function restaurant_branch()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_branch_id', 'id');
    }
    public function restaurant_delivery_person()
    {
        return $this->belongsTo('Models\DeliveryPerson', 'restaurant_branch_id', 'id');
    } 
    public function restaurant_delivery_person_order()
    {
        return $this->belongsTo('Models\DeliveryPersonOrder', 'restaurant_branch_id', 'id');
    }         
}
