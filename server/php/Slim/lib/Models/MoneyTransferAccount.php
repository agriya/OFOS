<?php
/**
 * MoneyTransferAccount
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

class MoneyTransferAccount extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'money_transfer_accounts';
    protected $fillable = array(
        'account',
        'is_primary',
        'is_active',
        'user_id'
    );
    protected $casts = array(
        'is_primary'  => 'integer',
        'is_active' => 'integer',
        'user_id'  => 'integer'
    );
    public $rules = array(
        'user_id' => 'sometimes|required|integer',
        'account' => 'sometimes|required'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function user_cash_withdrawal()
    {
        return $this->belongsTo('Models\UserCashWithdrawal', 'money_transfer_account_id', 'id');
    }    
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('account', 'ilike', '%' . $search . '%');
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
            if (($authUser['role_id'] == \Constants\ConstUserTypes::ADMIN) || ($authUser['id'] == $data->user_id)) {
                return true;
            }
            return false;
        });
        self::deleting(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::ADMIN) || ($authUser['id'] == $data->user_id)) {
                return true;
            }
            return false;
        });
    }
}
