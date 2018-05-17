<?php
/**
 * SudopayPaymentGroup
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

class SudopayPaymentGroup extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sudopay_payment_groups';
    protected $fillable = array(
        'sudopay_group_id',
        'name',
        'thumb_url'
    );
    protected $casts = array(
        'is_marketplace_supported' => 'integer',
        'sudopay_payment_group_id' => 'integer',
        'sudopay_gateway_id' => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
    }   
    
}
