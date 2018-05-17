<?php
/**
 * PushNotification
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
 * PushNotification
*/
class PushNotification extends AppModel
{
    protected $table = 'push_notifications';
    public $fillable = array (
        'message_type',
        'message',
        'user_device_id'
    );
    public $casts = array (
        'user_device_id' => 'integer'
    );    
    public $rules = array (
        'message_type' => 'sometimes|required',
        'message' => 'sometimes|required'
    );
    public function user_device()
    {
        return $this->belongsTo('Models\DeviceDetail', 'user_device_id', 'id');
    }    
}
