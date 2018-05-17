<?php
/**
 * RestaurantTiming
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

class RestaurantTiming extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'restaurant_timings';
    protected $fillable = array(
        'restaurant_id',
        'day',
        'period_type',
        'start_time',
        'end_time'
    );
    protected $casts = array(
        'restaurant_id' => 'integer'
    );
    public $rules = array(
        'restaurant_id' => 'sometimes|required',
        'day' => 'sometimes|required'
    );
    public function restaurant()
    {
        return $this->belongsTo('Models\Restaurant', 'restaurant_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('day', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('restaurant', function ($q) use ($params) {
                    $q->where('name', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }   
    
}
