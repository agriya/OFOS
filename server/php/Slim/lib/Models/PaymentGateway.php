<?php
/**
 * PaymentGateway
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

class PaymentGateway extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_gateways';
    protected $fillable = array(
        'name',
        'display_name',
        'description',
        'gateway_fees',
        'is_test_mode',
        'is_active',
        'is_enable_for_wallet'
    );
    protected $casts = array (
        'gateway_fees'=> 'double',
        'is_test_mode' => 'integer',
        'is_active' => 'integer',
        'is_enable_for_wallet' => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
        $query->where(function ($q) use ($enabled_plugins) {
               $q->whereIn('plugin', $enabled_plugins);
               $q->orWhere('plugin', null);
            });        
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('name', 'ilike', "%$search%");
                $q1->orWhere('display_name', 'ilike', "%$search%");
            });
        }
    }   
    public function payment_settings()
    {
        return $this->hasMany('Models\PaymentGatewaySetting','payment_gateway_id','id');
    }
    public function order()
    {
        return $this->hasMany('Models\Order','payment_gateway_id','id');
    }    
    public function transaction()
    {
        return $this->hasMany('Models\Transaction','payment_gateway_id','id');
    }   
    public function user_add_wallet_amount()
    {
        return $this->hasMany('Models\UserAddWalletAmount','payment_gateway_id','id');
    }    
    public function wallet()
    {
        return $this->hasMany('Models\Wallet','payment_gateway_id','id');
    }           
}
