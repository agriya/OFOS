<?php
/**
 * Coupon
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
 * Coupon
 */
class Coupon extends AppModel
{
    protected $table = 'coupons';
    protected $fillable = array(
        'user_id',
        'restaurant_id',
        'coupon_code',
        'discount',
        'is_flat_discount_in_amount',
        'no_of_quantity_allowed',
        'validity_start_date',
        'validity_end_date',
        'maximum_discount_amount',
        'is_active'
    );
    protected $casts = array(
        'user_id' => 'integer',
        'restaurant_id' => 'integer',
        'is_flat_discount_in_amount' => 'integer',
        'no_of_quantity_allowed'  => 'integer',
        'maximum_discount_amount' => 'double',
        'is_active' => 'integer'
    );
    public $rules = array(
        'user_id' => 'sometimes|required', 
        'restaurant_id' => 'sometimes|required', 
        'coupon_code' => 'sometimes|required', 
        'discount' => 'sometimes|required', 
        'is_flat_discount_in_amount' => 'sometimes|required', 
        'maximum_discount_amount' => 'sometimes|required', 
        'is_active' => 'sometimes|required'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('coupon_code', 'ilike', "%$search%");
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'ilike', "%$search%");
                });
                $q1->orWhereHas('restaurant', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
            });
        }        
    }
    public function verifyAndCouponCode($coupon_code, $restaurant_id = 0)
    {
        $result = array();
        $coupon = Coupon::where('coupon_code', $coupon_code)->where('is_active', 1)->where('validity_start_date', '<=', date('Y-m-d'))->where('validity_end_date', '>=', date('Y-m-d'));
        if (!empty($restaurant_id)) {
            $coupon = $coupon->where('restaurant_id', $restaurant_id);    
        }
        $coupon = $coupon->first();
        if (!empty($coupon)) {
            $maxNumberOfTimeCanUsePerUser = Order::where('coupon_id', $coupon->id)->whereNotIn('order_status_id', [\Constants\OrderStatus::PAYMENTPENDING, \Constants\OrderStatus::PAYMENTFAILED])->count();
            if ($maxNumberOfTimeCanUsePerUser >= $coupon->no_of_quantity_allowed && !empty($coupon->no_of_quantity_allowed)) {
                $result['error']['code'] = 1;
                $result['error']['message'] = 'Maximum Coupon allowed Limit Reached';
            } else {                
                $result['data'] = $coupon->toArray();
                $result['error']['code'] = 0;
            }
        } else {
             $result['error']['code'] = 1;
             $result['error']['message'] = 'Coupon code is invalid or expired';
        }
        return $result;
    }
    public function calculateDiscountPrice($original_price, $discount, $is_flat_discount_in_amount)
    {
        if (!empty($is_flat_discount_in_amount)) {
            $result['original_price'] = $original_price - $discount;
            $result['discount_price'] = $discount;
        } else {            
            $discount_price = ($discount / 100) * $original_price;
            $result['original_price'] = $original_price - $discount_price;
            $result['discount_price'] = $discount_price;
        }
        if ($result['original_price'] < 0) {
            $result['discount_price'] = $result['discount_price'] + $result['original_price'];
            $result['original_price'] = 0;        
        }
        return $result;
    }
}
