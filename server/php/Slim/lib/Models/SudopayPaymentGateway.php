<?php
/**
 * SudoPaymentGateway
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

class SudopayPaymentGateway extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sudopay_payment_gateways';
    protected $fillable = array(
        'sudopay_gateway_name',
        'sudopay_gateway_id',
        'sudopay_payment_group_id',
        'sudopay_gateway_details',
        'is_marketplace_supported'
    );
    protected $casts = array(
        'is_marketplace_supported' => 'integer',
        'sudopay_payment_group_id' => 'integer',
        'sudopay_gateway_id' => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
         parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('sudopay_gateway_name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('sudopay_group', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });                
            });
        }
    }   
    public function sudopay_group()
    {
        return $this->belongsTo('Models\SudopayPaymentGroup', 'sudopay_payment_group_id', 'id');
    }
}
