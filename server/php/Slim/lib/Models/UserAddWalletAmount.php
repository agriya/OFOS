<?php
/**
 * UserAddWalletAmount
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

class UserAddWalletAmount extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_add_wallet_amounts';
    protected $fillable = array(
        'user_id',
        'description',
        'amount',
        'payment_gateway_id',
        'sudopay_gateway_id',
        'sudopay_revised_amount',
        'sudopay_token',
        'is_success'
    );
    protected $casts = array(
        'payment_gateway_id' => 'integer',
        'sudopay_revised_amount' => 'double',
        'amount' => 'double',
        'user_id' => 'integer'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('description', 'ilike', "%$search%");
                $q1->orWhere('amount', 'ilike', "%$search%");
                $q1->orWhere('sudopay_revised_amount', 'ilike', "%$search%");
                $q1->orWhereHas('payment_gateway', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'ilike', "%$search%");
                });
            });
        }        
    }
    
}
