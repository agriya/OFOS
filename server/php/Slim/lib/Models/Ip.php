<?php
/**
 * Ip
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

class Ip extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ips';
    protected $fillable = array(
        'ip',
        'host',
        'city_id',
        'state_id',
        'country_id',
        'latitude',
        'longitude'
    );
    protected $casts = array(
        'city_id' => 'integer',
        'state_id' => 'integer',
        'country_id' => 'integer',
        'latitude'  => 'double',
        'longitude'  => 'double'
    );
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
    public function contact()
    {
        return $this->hasMany('Models\Contact', 'ip_id', 'id');
    } 
    public function user()
    {
        return $this->hasMany('Models\User', 'ip_id', 'id');
    }        
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('ip', 'ilike', '%' . $search . '%');
                $q1->orWhereHas('city', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
                $q1->orWhereHas('state', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
                $q1->orWhereHas('country', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%$search%");
                });
            });
        }
    }   
}
