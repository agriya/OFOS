<?php
/**
 * DeviceDetail
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
 * DeviceDetail
 */
class DeviceDetail extends AppModel
{
    protected $table = 'device_details';
    protected $fillable = array(
        'user_id',
        'appname',
        'appversion',
        'deviceuid',
        'devicetoken',
        'devicename',
        'devicemodel',
        'deviceversion',
        'pushbadge',
        'pushalert',
        'pushsound',
        'development',
        'status',
        'latitude',
        'longitude',
        'devicetype'
    );
    public $rules = array(
        'latitude' => 'sometimes|required',
        'longitude' => 'sometimes|required', 
        'devicetype' => 'sometimes|required' 
    );
    protected $casts = array(
        'user_id' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $q1->where('devicename', $params['q']);
                $q1->orWhere('devicemodel', $params['q']);
                $q1->orWhere('appname', $params['q']);
                $q1->orWhere('status', $params['q']);
                $q1->orWhereHas('user', function ($q) use ($params) {
                    $q->where('username', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
}
