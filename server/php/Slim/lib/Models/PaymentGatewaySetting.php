<?php
/**
 * PaymentGatewaySetting
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

class PaymentGatewaySetting extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_gateway_settings';
    protected $fillable = array(
        'name',
        'payment_gateway_id',
        'description',
        'label',
        'type',
        'options',
        'test_mode_value',
        'live_mode_value'
    );
    protected $casts = array (
        'payment_gateway_id'=> 'integer'
    );
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
                $q1->Where('name', 'ilike', "%$search%");
                $q1->orWhereHas('payment_gateway', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
            });
        }
    }   
}
