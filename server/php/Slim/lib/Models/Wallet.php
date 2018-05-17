<?php
/**
 * Wallet
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

/*
 * Wallet
*/
class Wallet extends AppModel
{
    protected $table = 'wallets';
    protected $fillable = array(
        'user_id',
        'amount',
        'payment_gateway_id	',
        'gateway_id',
        'is_payment_completed',
        'success_url',
        'cancel_url'
    );
    protected $casts = array(
        'user_id' => 'integer',
        'is_payment_completed' => 'integer',
        'payment_gateway_id' => 'integer',
        'amount' => 'double',
    );
    public $rules = array(
        'amount' => 'sometimes|required',
        'payment_gateway_id' => 'sometimes|required',
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('amount', 'ilike', "%$search%");
                $q1->orWhereHas('payment_gateway', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'ilike', "%$search%");
                });
            });
        }
    }   
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
    public function processCaptured($payment_response, $id)
    {
        global $_server_domain_url;
        $wallet = Wallet::with('payment_gateway')->where('is_payment_completed', 0)->find($id);
        if (!empty($wallet)) {
            $wallet->is_payment_completed = 1;
            if (!empty($payment_response['paykey'])) {
                if ($wallet->payment_gateway_id == \Constants\PaymentGateways::SUDOPAY) {
                  $wallet->zazpay_pay_key = $payment_response['paykey'];   
                } elseif ($wallet->payment_gateway_id == \Constants\PaymentGateways::PAYPAL) {
                  $wallet->paypal_pay_key = $payment_response['paykey'];
                }
            }
            $wallet->update();
            $wallet = Wallet::with('payment_gateway', 'user')->find($id);
            $user = User::find($wallet->user_id);            
            $user->available_wallet_amount = $user->available_wallet_amount + $wallet->amount;
            $user->update();  
            Payment::addTransactions($wallet, 'Wallet');                  
        }         
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment successfully completed'
            )
        );
        return $response;
    }
    public function processInitiated($payment_response)
    {
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment initiated',
                'fields' => ''
            )
        );
        return $response;
    }
    public function processPending($payment_response)
    {
        $response = array(
            'data' => $payment_response,
            'error' => array(
                'code' => 0,
                'message' => 'Payment is in pending state.'
            )
        );
        return $response;
    }
}
