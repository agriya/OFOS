<?php
/**
 * Sample cron file
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
$app_path = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR;
require_once $app_path. '/lib/bootstrap.php';

require_once PLUGIN_PATH. DIRECTORY_SEPARATOR . 'Order' . DIRECTORY_SEPARATOR . 'Sms' . DIRECTORY_SEPARATOR . 'functions.php';

sendPushNotification();
function sendPushNotification(){
     $push_notifications = Models\PushNotification::with('user_device')->get();
     if(!empty($push_notifications)){
        $push_notifications =  $push_notifications->toArray();
        foreach($push_notifications as $push_notification){
            $device_type = $push_notification['user_device']['devicetype'];
            $device_token = $push_notification['user_device']['devicetoken'];
            $message = $push_notification['message'];
            try{
                sendPushMessage($device_type,$device_token,$message);
                Models\PushNotification::where('id',$push_notification['id'])->delete();
            } catch (Exception $e) {
                
            }
        }
     }
}
