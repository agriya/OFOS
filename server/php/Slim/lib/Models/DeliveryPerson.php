<?php
/**
 * DeliveryPerson
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

class DeliveryPerson extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_delivery_persons';
    protected $fillable = array (
        'is_active',
        'user_id',
        'restaurant_id',
        'restaurant_branch_id',
        'restaurant_supervisor_id'
    );
    protected $casts = array (
        'is_active' => 'integer',
        'user_id' => 'integer',
        'restaurant_id' => 'integer',
        'restaurant_branch_id' => 'integer',
        'restaurant_supervisor_id' => 'integer'
    );
    public $rules = array(
        'restaurant_id' => 'sometimes|required|integer',
        'restaurant_branch_id' => 'sometimes|required|integer',
        'restaurant_supervisor_id' => 'sometimes|required|integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['status_id'])) {
            $query->whereHas('restaurant_delivery_person_order', function ($q) use ($params) {
                $q->whereHas('order', function ($q) use ($params) {
                    $q->where('order_status_id', $params['status_id']);
                });
            });
        }
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->orWhereHas('restaurant_branch', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('user', function ($q) use ($params) {
                    $q->Where('username', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('restaurant_supervisor', function ($q) use ($params) {
                    $q->whereHas('user', function ($q) use ($params) {
                        $q->where('username', 'ilike', '%' . $params['q'] . '%');
                    });
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
    public function restaurant_supervisor()
    {
        return $this->belongsTo('Models\Supervisor', 'restaurant_supervisor_id', 'id');
    }
    public function order()
    {
        return $this->hasMany('Models\Order', 'restaurant_delivery_person_id', 'id');
    }    
    public function restaurant_delivery_person_order()
    {
        $order = $this->hasMany('Models\RestaurantDeliveryPersonOrder', 'restaurant_delivery_person_id', 'id');
        $order = $order->with('order');
        return $order;
    }
}
