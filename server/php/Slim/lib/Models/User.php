<?php
/**
 * User
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

use \Firebase\JWT\JWT;

class User extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $fillable = array(
        'first_name',
        'last_name',
        'mobile',
        'username',
        'email',
        'password',
        'address',
        'is_agree_terms_conditions',
        'gender_id',
        'address1',
        'latitude',
        'longitude',
        'zip_code',
        'is_subscribed',
        'is_active',
        'last_login_ip_id',
        'phone',
        'dob',
        'about_me',
        'available_wallet_amount',
        'provider_id',
        'role_id',
        'city_id',
        'state_id',
        'country_id',
        'last_logged_in_time',
        'last_login_ip_id',
        'is_email_confirmed',
        'is_active',
        'is_created_from_order_page',
        'mobile_code'
    );
    protected $casts = array(
        'is_active' => 'integer',
        'country_id' => 'integer',
        'state_id' => 'integer',
        'city_id' => 'integer',
        'role_id' => 'integer',
        'provider_id' => 'integer',
        'last_login_ip_id' => 'integer',
        'is_subscribed' => 'integer',
        'longitude' => 'double',
        'latitude' => 'double',
        'gender_id' => 'integer',
        'is_agree_terms_conditions' => 'integer',
        'available_wallet_amount' => 'double'
    );
    public $rules = array(
        'first_name' => 'sometimes|required',
        'last_name' => 'sometimes|required',
        'mobile' => 'sometimes|required',
        'username' => 'sometimes|required|alpha_num',
        'email' => 'sometimes|required|email',
        'password' => 'sometimes|required'
    );
    // Role - Admin scopes
    protected $scopes_1 = array();
    // Role - User scopes
    protected $scopes_2 = array(
        'canUpdateUser',
        'canViewUser',
        'canCreateRestaurantReview',
        'canCreateUserAddress',
        'canUpdateUserAddress',
        'canDeleteUserAddress',
        'canCreateOrder',
        'canUserUpdateOrder',
        'canUserCreateUserCashWithdrawals',
        'canUserListUserCashWithdrawals',
        'canUserDeleteUserCashWithdrawals',
        'canCreateWallet',
        'canListOrder',
        'canListAllTransactions',
        'canViewUserAddress',
        'canListUserAddress',
        'canCreateMoneyTransferAccount',
        'canListMoneyTransferAccount',
        'canCreateDeviceDetail',
        'canReorder',
        'canViewOrder',
        'canListUserCashWithdrawals',
        'canUpdateChangePassword'
    );
    // Role - Restaurant scopes
    protected $scopes_3 = array(
        'canUpdateUser',
        'canViewUser',
        'canViewRestaurantDeliveryPerson',
        'canRestaurantUpdateOrder',
        'canCreateRestaurantPhoto',
        'canDeleteRestaurantPhoto',
        'canCreateRestaurantLogo',
        'canDeleteRestaurantLogo',
        'canCreateRestaurantCategoryPhoto',
        'canDeleteRestaurantCategoryPhoto',
        'canCreateRestaurantMenuPhoto',
        'canDeleteRestaurantMenuPhoto',
        'canCreateRestaurantDeliveryPesonOrder',
        'canListRestaurantDeliveryPesonOrder',
        'canViewRestaurantDeliveryPesonOrder',
        'canUpdateRestaurantDeliveryPesonOrder',
        'canCreateRestaurant',
        'canUpdateRestaurant',
        'canViewCuisine',
        'canUpdateCuisine',
        'canViewCity',
        'canViewCountry',
        'canViewState',
        'canViewStats',
        'canRestaurantCreateRestaurantTiming',
        'canRestaurantUpdateRestaurantTiming',
        'canRestaurantDeleteRestaurantTiming',
        'canListRestaurantDeliveryPerson',
        'canViewOrder',
        'canCreateMoneyTransferAccount',
        'canUpdateMoneyTransferAccount',
        'canViewMoneyTransferAccount',
        'canDeleteMoneyTransferAccount',
        'canViewRestaurantSupervisor',
        'canCreateAttachment',
        'canCreateRestaurantReview',
        'canCreateUserAddress',
        'canUpdateUserAddress',
        'canDeleteUserAddress',
        'canCreateOrder',
        'canUserUpdateOrder',
        'canUserCreateUserCashWithdrawals',
        'canUserListUserCashWithdrawals',
        'canUserDeleteUserCashWithdrawals',
        'canCreateWallet',
        'canUpdateRestaurantMenuPosition',
        'canUpdateRestaurantCategoryPosition',
        'canListOrder',
        'canListAllTransactions',
        'canListUser',
        'canListRestaurantSupervisor',
        'canViewUserAddress',
        'canListUserAddress',
        'canListMoneyTransferAccount',
        'canCreateDeviceDetail',
        'canCreateRestaurantSupervisor',
        'canCreateRestaurantDeliveryPerson',
        'canListUserCashWithdrawals',
        'canCreateRestaurantMenu',
        'canDeleteRestaurantCuisine',
        'canUpdateRestaurantMenu',
        'canCreateRestaurantMenu',
        'canCreateRestaurantCategory',
        'canUpdateRestaurantCategory',
        'canDeleteRestaurantMenu',
        'canCreateRestaurantAddon',
        'canUpdateRestaurantAddon',
        'canUpdateChangePassword'
    );
    // Role - Supervisor scopes
    protected $scopes_4 = array(
        'canUpdateUser',
        'canViewUser',
        'canViewUserAddress',
        'canRestaurantUpdateOrder',
        'canListRestaurantDeliveryPerson',
        'canCreateRestaurantDeliveryPesonOrder',
        'canListRestaurantDeliveryPesonOrder',
        'canViewRestaurantDeliveryPesonOrder',
        'canUpdateRestaurantDeliveryPesonOrder',
        'canViewCity',
        'canViewCountry',
        'canViewState',
        'canRestaurantCreateRestaurantTiming',
        'canRestaurantUpdateRestaurantTiming',
        'canRestaurantDeleteRestaurantTiming',
        'canViewStats',
        'canViewOrder',
        'canCreateRestaurantReview',
        'canCreateUserAddress',
        'canUpdateUserAddress',
        'canDeleteUserAddress',
        'canCreateOrder',
        'canUserUpdateOrder',
        'canUserCreateUserCashWithdrawals',
        'canUserListUserCashWithdrawals',
        'canUserDeleteUserCashWithdrawals',
        'canCreateWallet',
        'canListOrder',
        'canListAllTransactions',
        'canListUser',
        'canListUserAddress',
        'canCreateMoneyTransferAccount',
        'canListMoneyTransferAccount',
        'canCreateDeviceDetail',
        'canListRestaurantSupervisor',
        'canCreateRestaurantDeliveryPerson',
        'canViewRestaurantDeliveryPerson',
        'canUpdateRestaurantMenuPosition',
        'canUpdateRestaurantCategoryPosition',
        'canCreateRestaurantCategory',
        'canUpdateRestaurantCategory',
        'canDeleteRestaurantCategory',
        'canCreateRestaurantMenu',
        'canUpdateRestaurantMenu',
        'canDeleteRestaurantMenu',
        'canUpdateChangePassword'
    );
    // Role - Delivery Persons scope
    protected $scopes_5 = array(
        'canUpdateUser',
        'canViewUser',
        'canRestaurantUpdateOrder',
        'canListRestaurantDeliveryPesonOrder',
        'canViewRestaurantDeliveryPesonOrder',
        'canUpdateRestaurantDeliveryPesonOrder',
        'canViewStats',
        'canViewOrder',
        'canCreateRestaurantReview',
        'canCreateUserAddress',
        'canUpdateUserAddress',
        'canDeleteUserAddress',
        'canCreateOrder',
        'canUserUpdateOrder',
        'canUserCreateUserCashWithdrawals',
        'canUserListUserCashWithdrawals',
        'canUserDeleteUserCashWithdrawals',
        'canCreateWallet',
        'canListOrder',
        'canListAllTransactions',
        'canListUser',
        'canViewUserAddress',
        'canListUserAddress',
        'canCreateMoneyTransferAccount',
        'canListMoneyTransferAccount',
        'canCreateDeviceDetail',
        'canUpdateChangePassword'
    );
    /**
     * To check if username already exist in user table, if so generate new username with append number
     *
     * @param string $username User name which want to check if already exsist
     *
     * @return mixed
     */
    public function checkUserName($username)
    {
        $userExist = User::where('username', $username)->first();
        if (count($userExist) > 0) {
            $org_username = $username;
            $i = 1;
            do {
                $username = $org_username . $i;
                $userExist = User::where('username', $username)->first();
                if (count($userExist) < 0) {
                    break;
                }
                $i++;
            } while ($i < 1000);
        }
        return $username;
    }
    public function generateUsername($email)
    {
        $usernames = explode('@',$email);
        $username = User::checkUserName($usernames[0]);
        return $username;
    }
    public function restaurant()
    {
        return $this->hasOne('Models\Restaurant', 'user_id', 'id')->where('parent_id', null);
    }
    public function restaurant_supervisor()
    {
        return $this->hasOne('Models\Supervisor', 'user_id', 'id');
    }
    public function restaurant_delivery_person()
    {
        return $this->hasOne('Models\DeliveryPerson', 'user_id', 'id');
    }
    public function city()
    {
        return $this->belongsTo('Models\City', 'city_id', 'id');
    }
    public function state()
    {
        return $this->belongsTo('Models\State', 'state_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country', 'country_id', 'id');
    }
    public function provider_user()
    {
        return $this->hasMany('Models\ProviderUser', 'user_id', 'id');
    }
    public function role()
    {
        return $this->belongsTo('Models\Role', 'role_id', 'id');
    }
    public function device_detail()
    {
        return $this->hasOne('Models\DeviceDetail', 'user_id', 'id');
    }
    public function provider()
    {
        return $this->belongsTo('Models\Provider', 'provider_id', 'id');
    }
    public function cart()
    {
        return $this->hasOne('Models\Cart', 'user_id', 'id');
    }
    public function coupon()
    {
        return $this->hasMany('Models\Coupon', 'user_id', 'id');
    }    
    public function money_transfer_account()
    {
        return $this->hasMany('Models\MoneyTransferAccount', 'user_id', 'id');
    }     
    public function order()
    {
        return $this->hasMany('Models\Order', 'user_id', 'id');
    }
    public function transaction()
    {
        return $this->hasMany('Models\Transaction', 'user_id', 'id');
    }   
    public function user_address()
    {
        return $this->hasMany('Models\UserAddress', 'user_id', 'id');
    } 
    public function wallet()
    {
        return $this->hasMany('Models\Wallet', 'user_id', 'id');
    }           
    public function gender()
    {
        return $this->belongsTo('Models\Gender', 'gender_id', 'id');
    }   
    public function last_login_ip()
    {
        return $this->belongsTo('Models\Ip', 'last_login_ip_id', 'id');
    }       
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        $query->where('is_created_from_order_page', 0);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('email', 'ilike', "%$search%");
                $q1->orWhere('mobile', 'ilike', "%$search%");
                $q1->orWhere('username', 'ilike', "%$search%");                                
                $q1->orWhere('first_name', 'ilike', "%$search%");
                $q1->orWhere('last_name', 'ilike', "%$search%");
                $q1->orWhereHas('role', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
                $q1->orWhereHas('provider', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
            });
        }        
    }
    /**
    * Returns an OAuth2 access token to the client
    *
    * @param array $post Post data
    *
    * @return mixed
    */
    public function getToken($user_id = 0)
    {
        $key = SITE_NAME;
        $subject = $user_id;
        $issuedAt = time() - 1000;
        $notBefore = $issuedAt + 1000; //Adding 1000 seconds
        $expire = $notBefore + \Constants\JWT::JWTTOKENEXPTIME; // Adding 6000 seconds
        $token = array(
            "sub" => $subject,
            "iat" => $issuedAt,
            "exp" => $expire,
            "nbf" => $notBefore
        );
        $jwt = JWT::encode($token, $key);
        return $jwt;
    }
}
