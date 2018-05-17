<?php
/**
 * WalletTransactionLog
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

class WalletTransactionLog extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wallet_transaction_logs';
    protected $fillable = array(
        'amount',
        'foreign_id',
        'class	',
        'status',
        'payment_type'
    );
    protected $casts = array(
        'foreign_id' => 'integer',
        'amount' => 'double'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('amount', 'ilike', "%$search%");
                $q1->orWhere('class', 'ilike', "%$search%");
                $q1->orWhere('status', 'ilike', "%$search%");
                $q1->orWhere('payment_type', 'ilike', "%$search%");
            });
        }
    }   
}
