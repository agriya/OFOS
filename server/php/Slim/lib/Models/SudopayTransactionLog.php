<?php
/**
 * SudopayTransactionLog
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

class SudopayTransactionLog extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sudopay_transaction_logs';
    protected $fillable = array(
        'class',
        'foreign_id',
        'sudopay_pay_key',
        'merchant_id',
        'gateway_id',
        'status',
        'payment_type',
        'buyer_id',
        'buyer_email',
        'buyer_address',
        'amount',
        'payment_id'
    );
    protected $casts = array(
        'foreign_id' => 'integer',
        'gateway_id' => 'integer',
        'amount' => 'double'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
    }   
    
}
