<?php
/**
 * Restaurant
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

class Restaurant extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurants';
    protected $fillable = array(
        'name',
        'parent_id',
        'phone',
        'mobile',
        'fax',
        'contact_name',
        'contact_phone',
        'website',
        'address1',
        'address',
        'zip_code',
        'city_id',
        'state_id',
        'country_id',
        'latitude',
        'longitude',
        'sales_tax',
        'minimum_order_for_booking',
        'is_allow_users_to_door_delivery_order',
        'estimated_time_to_delivery',
        'delivery_charge',
        'delivery_miles',
        'slug',
        'is_active',
        'user_id',
        'is_allow_users_to_pickup_order',
        'is_allow_users_to_preorder',
        'mobile_code'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'phone' => 'sometimes',
        'mobile' => 'sometimes|required',
        'contact_name' => 'sometimes|required',
        'address' => 'sometimes|required',
        'city_id' => 'sometimes|required',
        'state_id' => 'sometimes|required',
        'country_id' => 'sometimes|required'
    );
    public $casts = array (
        'parent_id' => 'integer',
        'city_id'  => 'integer',
        'state_id'  => 'integer',
        'country_id'  => 'integer',
        'latitude'  => 'double',
        'longitude'  => 'double',
        'sales_tax'  => 'double',
        'minimum_order_for_booking' => 'integer',
        'is_allow_users_to_door_delivery_order'  => 'integer',
        'estimated_time_to_delivery' => 'integer',
        'delivery_charge' => 'double',
        'is_active'  => 'integer',
        'user_id'  => 'integer',
        'is_allow_users_to_pickup_order' => 'integer',
        'is_allow_users_to_preorder' => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->Where(function ($q1) use ($search) {
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'ilike', "%$search%");
                });
                $q1->orWhere('name', 'ilike', "%$search%");
                $q1->orWhere('phone', 'ilike', "%$search%");
                $q1->orWhere('mobile', 'ilike', "%$search%"); 
                $q1->orWhere('contact_name', 'ilike', "%$search%");                                
            });
        }
        if (!empty($params['sort']) && $params['sort'] == 'distance') {
            $sortby = (!empty($params['sortby'])) ? $params['sortby'] : "ASC";
            $latitude = $params['latitude'];
            $longitude = $params['longitude'];
            $radius = isset($params['radius']) ? $params['radius'] : 50;
            $distance='ROUND(( 6371 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.')) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) )))';
            $query->selectRaw($distance . ' AS distance')->whereRaw('(' . $distance . ')<=' . $radius)->orderBy("distance", $sortby);
        }
        if (!empty($params['cuisine'])) {
            $cuisine_ids = explode(',', $params['cuisine']);
            $cusines = RestaurantCuisine::whereIn('cuisine_id', $cuisine_ids)->get();
            if (!empty($cusines)) {
                $i = 0;
                foreach ($cusines as $cuisine) {
                    $ids[$i] = $cuisine->restaurant_id;
                    $i++;
                }
                $query->whereIn('id', $ids);
            }
        }
    }
    public function attachment()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id')->where('class', 'Restaurant');
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
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo('Models\Restaurant', 'parent_id', 'id');
    }    
    public function child()
    {
        return $this->hasMany('Models\Restaurant', 'parent_id', 'id');
    }       
    public function restaurant_review()
    {
        return $this->hasMany('Models\RestaurantReview', 'restaurant_id', 'id');
    }
    public function restaurant_review_count()
    {
        return $this->restaurant_review()->selectRaw('restaurant_id, sum(rating) as total_ratings,count(id) as total_user_rating_count')->groupBy('restaurant_id');
    }
    public function restaurant_cuisine()
    {
        return $this->hasMany('Models\RestaurantCuisine', 'restaurant_id', 'id');
    }
    public function restaurant_timing()
    {
        return $this->hasMany('Models\RestaurantTiming')->orderBy('id');
    }
    public function supervisors()
    {
        return $this->hasMany('Models\Supervisor', 'restaurant_id', 'id');
    }
    public function delivery_persons()
    {
        return $this->hasMany('Models\DeliveryPerson', 'restaurant_id', 'id');
    }
    public function image()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'id')->where('class', 'Restaurant');
    }
    public function restaurant_photo()
    {
        return $this->hasMany('Models\Attachment', 'foreign_id', 'id')->where('class', 'RestaurantPhoto');
    }    
    public function trading_certificate()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'id')->where('class', 'ShopTradingCertificate');
    }
    public function order()
    {
        return $this->hasMany('Models\Order', 'restaurant_id', 'id');
    }
    public function cart()
    {
        return $this->hasMany('Models\Cart', 'restaurant_id', 'id');
    }    
    public function coupon()
    {
        return $this->hasMany('Models\Coupon', 'restaurant_id', 'id');
    }      
    public function restaurant_addon()
    {
        return $this->hasMany('Models\RestaurantAddon', 'restaurant_id', 'id');
    }  
    public function restaurant_branch()
    {
        return $this->hasMany('Models\Restaurant', 'parent_id', 'id');
    }  
    public function restaurant_category()
    {
        return $this->hasMany('Models\RestaurantCategory', 'restaurant_id', 'id')->orderBy('display_order');
    }    
    public function restaurant_delivery_person_order()
    {
        return $this->hasMany('Models\RestaurantDeliveryPersonOrder', 'restaurant_id', 'id');
    }      
    public function restaurant_menus()
    {
        return $this->hasMany('Models\RestaurantMenu', 'restaurant_id', 'id');
    }      
    public function transaction()
    {
        return $this->hasMany('Models\Transaction', 'restaurant_id', 'id');
    }             
    public function restaurantValidation($restaurant_args) 
    {        
        $validationError = array();
        $restaurant_validations = true;
        $restaurant  = new Restaurant;             
        $restaurant_validation_error = $restaurant->validate($restaurant_args);
        if(!empty($restaurant_validation_error)){
            $validationError[] = empty($restaurant_validation_error) ? [] : $restaurant_validation_error;
            $restaurant_validations = false;
        }
        $result['validation'] = $validationError;        
        $result['error_status'] = $restaurant_validations;
        return $result;
    } 
    public function saveRestaurant($restaurant_args)
    {
        $restaurant = new Restaurant;
        $restaurant->name = $restaurant_args['name'];
        $restaurant->slug = \Inflector::slug(strtolower($restaurant_args['name']), '-');
        $restaurant->user_id = $restaurant_args['user_id'];
        $restaurant->mobile = $restaurant_args['mobile'];
        $restaurant->address = $restaurant_args['address'];
        $restaurant->contact_name = $restaurant_args['contact_name'];
        $restaurant->estimated_time_to_delivery = !empty($restaurant_args['estimated_time_to_delivery']) ? $restaurant_args['estimated_time_to_delivery'] : 0;
        $restaurant->delivery_charge = !empty($restaurant_args['delivery_charge']) ? $restaurant_args['delivery_charge'] : 0;
        $restaurant->delivery_miles = !empty($restaurant_args['delivery_miles']) ? $restaurant_args['delivery_miles'] : 0;
        $restaurant->latitude = $restaurant_args['latitude'];
        $restaurant->longitude = $restaurant_args['longitude'];
        $restaurant->country_id = Country::findCountryIdFromIso2($restaurant_args['country']['iso2']);
        $restaurant->state_id = State::findOrSaveAndGetStateId($restaurant_args['state']['name'], $restaurant->country_id);
        $restaurant->city_id = City::findOrSaveAndGetCityId($restaurant_args['city']['name'], $restaurant->country_id, $restaurant->state_id);
        $this->geohash = new \Geohash();
        $restaurant->hash = $this->geohash->encode(round($restaurant_args['latitude'], 6), round($restaurant_args['longitude'], 6));
        $restaurant->save();
        $emailFindReplace = array(
            '##USERNAME##' => $restaurant_args['contact_name'],
            '##USEREMAIL##' => $restaurant_args['email'],
            '##SUPPORT_EMAIL##' => SUPPORT_EMAIL
        );
        sendMail('useraddresturantmail', $emailFindReplace, SITE_CONTACT_EMAIL);
        if(!empty($restaurant_args['image']['attachment'])){
            saveImage('Restaurant', $restaurant_args['image']['attachment'], $restaurant->id);
        }
        if(!empty($restaurant_args['image']['image_data'])){
            saveImageData('Restaurant', $restaurant_args['image']['image_data'],  $restaurant->id);
        }
        $days = array(
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            );
        foreach ($days as $day) {
            for ($i = 1; $i <= 3; $i++) {
                $restaurantTiming = new RestaurantTiming;
                $restaurantTiming->restaurant_id = $restaurant->id;
                $restaurantTiming->period_type = $i;
                $restaurantTiming->day = $day;
                $restaurantTiming->start_time = "00:00";
                $restaurantTiming->end_time = "00:00";
                $restaurantTiming->save();
            }
        }
    }   
}
