<?php
/**
 * RestaurantReview
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

class RestaurantReview extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_reviews';
    protected $fillable = array(
        'user_id',
        'restaurant_id',
        'order_id',
        'rating',
        'message',
        'is_active'
    );
    protected $casts = array(
        'user_id' => 'integer',
        'restaurant_id' => 'integer',
        'order_id' => 'integer',
        'is_active' => 'integer'
    );
    public $rules = array(
        'user_id' => 'sometimes|required|integer',
        'restaurant_id' => 'sometimes|required|integer',
        'order_id' => 'sometimes|required|integer',
        'rating' => 'sometimes|required|integer',
        'message' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('message', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
                $q1->orWhereHas('user', function ($q) use ($params) {
                    $q->Where('username', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo('Models\Order', 'order_id', 'id');
    }
    public function save1($restaurant_id, $user_id)
    {
        // @TODO
        // before save code
        parent::save();
        // after save code
        $restaurantReviewCount = RestaurantReview::where('restaurant_id', $restaurant_id)->count();
        $restaurantUserReviewCount = RestaurantReview::where('user_id', $user_id)->count();
        $restaurantReviewSum = RestaurantReview::where('restaurant_id', $restaurant_id)->sum('rating');
        $user = User::where('id', $user_id)->first();
        $user->total_reviews = $restaurantUserReviewCount + 1;
        $user->update();
        $restaurant = Restaurant::where('id', $restaurant_id)->first();
        $restaurant->total_reviews = $restaurantReviewCount + 1;
        $restaurant->avg_rating = $restaurantReviewSum / $restaurantReviewCount;
        $restaurant->update();
    }
}
