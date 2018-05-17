<?php
/**
 * Transaction
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

class Transaction extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';
    protected $fillable = array(
        'user_id',
        'amount',
        'foreign_id',
        'class',
        'other_user_id',
        'transaction_type_id',
        'payment_gateway_id',
        'gateway_fees',
        'restaurant_id'
    );
    protected $casts = array(
        'user_id' => 'integer',
        'restaurant_id' => 'integer',
        'other_user_id' => 'integer',
        'amount' => 'double',
        'gateway_fees' => 'double'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);         
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q->where('class', 'ilike', "%$search%");
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'ilike', "%$search%");
                });
                $q1->orWhereHas('other_user', function ($q) use ($search) {
                    $q->where('username', 'ilike', "%$search%");
                }); 
                $q1->orWhereHas('restaurant', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
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
    public function other_user()
    {
        return $this->belongsTo('Models\User', 'other_user_id', 'id');
    }
    public function transaction_type()
    {
        return $this->belongsTo('Models\TransactionType', 'transaction_type_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo('Models\Order', 'foreign_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
}
