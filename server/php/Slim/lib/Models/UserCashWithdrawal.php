<?php
/**
 * UserCashWithdrawals
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

class UserCashWithdrawal extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_cash_withdrawals';
    protected $fillable = array(
        'user_id',
        'amount',
        'remark',
        'status',
        'money_transfer_account_id'
    );
    protected $casts = array(
        'user_id' => 'integer',
        'status' => 'integer',
        'amount' => 'double',
        'money_transfer_account_id'  => 'integer'
    );    
    public $rules = array(
        'user_id' => 'sometimes|required|integer',
        'money_transfer_account_id' => 'sometimes|required|integer',
        'amount' => 'sometimes|required|min:' . USER_MINIMUM_WITHDRAW_AMOUNT . '|max:' . USER_MAXIMUM_WITHDRAW_AMOUNT,
        'status' => 'sometimes'
    );
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function money_transfer_account()
    {
        return $this->belongsTo('Models\MoneyTransferAccount', 'money_transfer_account_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('remark', 'ilike', "%$search%");
                $q1->orWhere('amount', 'ilike', "%$search%");
                $q1->orWhereHas('money_transfer_account', function ($q) use ($search) {
                    $q->where('account', 'ilike', "%$search%");
                });
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'ilike', "%$search%");
                });
            });
        }
    }
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        static ::addGlobalScope('user', function (\Illuminate\Database\Eloquent\Builder $builder) use ($authUser) {
            if ($authUser['role_id'] != \Constants\ConstUserTypes::ADMIN) {
                $builder->where('user_id', $authUser['id']);
            }
        });
        self::saving(function ($data) use ($authUser) {
            if ($authUser['role_id'] == \Constants\ConstUserTypes::ADMIN || $authUser['id'] == $data->user_id) {
                return true;
            }
            return false;
        });
        self::deleting(function ($data) use ($authUser) {
            if ($authUser['role_id'] == \Constants\ConstUserTypes::ADMIN || $authUser['id'] == $data->user_id) {
                return true;
            }
            return false;
        });
    }
}
