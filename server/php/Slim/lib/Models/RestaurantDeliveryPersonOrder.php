<?php
/**
 * RestaurantDeliveryPersonOrder
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

class RestaurantDeliveryPersonOrder extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_delivery_person_orders';
    protected $fillable = array(
        'restaurant_delivery_person_id',
        'order_id',
        'comments',
        'is_delivered',
        'restaurant_id',
        'restaurant_supervisor_id',
        'restaurant_branch_id'
    );
    protected $casts = array(
        'restaurant_delivery_person_id' => 'integer',
        'order_id' => 'integer',
        'is_delivered'  => 'integer',
        'restaurant_id'  => 'integer',
        'restaurant_supervisor_id'  => 'integer',
        'restaurant_branch_id'  => 'integer'
    );
    public $rules = array(
        'restaurant_delivery_person_id' => 'sometimes|required|integer',
        'order_id' => 'sometimes|required|integer',
        'comments' => 'sometimes',
        'is_delivered' => 'sometimes'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->Where('comments', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('user', function ($q) use ($params) {
                    $q->where('username', 'ilike', '%' . $params['q'] . '%');
                }); 
                $q1->orWhereHas('restaurant_supervisor.user', function ($q) use ($params) {
                    $q->where('username', 'ilike', '%' . $params['q'] . '%');
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
        return $this->belongsTo('Models\RestaurantBranch', 'restaurant_branch_id', 'id');
    }
     public function restaurant_supervisor()
    {
        return $this->belongsTo('Models\Supervisor', 'restaurant_supervisor_id', 'id');
    }
}
