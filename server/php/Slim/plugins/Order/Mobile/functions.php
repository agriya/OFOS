<?php
function addPushNotification($user_id, $pushMessage = array(), $is_immediate = false)
{
    $userDevice = Models\DeviceDetail::select('id')->where('user_id', $user_id)->first();         
    if(!empty($userDevice)) {
        $pushNotification = new Models\PushNotification;
        $pushNotification->user_device_id = $userDevice->id;
        $pushNotification->message_type = $pushMessage['message_type'];
        $pushNotification->message = getNotificationMessage($pushMessage);
        $pushNotification->save();
        if ($is_immediate == true) {
            sendSinglePushNotification($pushNotification->id);
        }
    }
}
function getNotificationMessage($pushMessage = array())
{
        $notificationMessage = '';
        $user = Models\User::where('id', $pushMessage['user_id'])->select('username')->first();
        $default_content = array(
            '##USERNAME##' => $user->username,
            '##SITE_NAME##' => SITE_NAME
        );
        if(!empty($pushMessage['order_id'])){
            $order = Models\Order::with('restaurant')->find($pushMessage['order_id']);
            $default_content['##ORDER_NO##'] = $pushMessage['order_id'];
            $default_content['##BOOKING_DATE##'] = $order->created_at;
            $default_content['##RESTAURANT_NAME##'] = $order['restaurant']['name'];
            $default_content['##AMOUNT##'] = $order->total_price; 
            $default_content['##TOTAL_PRICE##'] = $order->total_price; 
            $default_content['##CURRENCY_SYMBOL##'] = CURRENCY_SYMBOL; 
            $default_content['##DELIVERY_CHARGE##'] = $order->delivery_charge;                              
            $default_content['##SALES_TAX##'] = $order->sales_tax;  
            $default_content['##TRACKID##'] = $order->track_id;  
            $default_content['##ESTIMATED_TIME_TO_DELIVERY##'] = $order['restaurant']['estimated_time_to_delivery'];            
            if($pushMessage['message_type'] == 'paid'){
                $notificationMessage = PUSH_NOTIFICATION_FOR_NEW_BOOKING;
            }elseif($pushMessage['message_type'] == 'assigned'){
                $notificationMessage = PUSH_NOTIFICATION_FOR_BOOKING_ASSIGN_TO_DELIVERY;
            }elseif($pushMessage['message_type'] == 'out for delivery'){
                $notificationMessage = PUSH_NOTIFICATION_FOR_BOOKING_OUT_FOR_DELIVERY;
            }elseif($pushMessage['message_type'] == 'delivered'){
                $notificationMessage = PUSH_NOTIFICATION_FOR_BOOKING_DELIVERED;
            }                 
        }
        if(!empty($notificationMessage)){
            $notificationMessages = strtr($notificationMessage, $default_content);
        } else {
            $notificationMessages = $message_type;
        }
        return $notificationMessages;
}
function sendSinglePushNotification($id) {
    $push_notification = Models\PushNotification::with('user_device')->where('id', $id)->first();
    if(!empty($push_notification)){
        $device_type = $push_notification['user_device']['devicetype'];
        $device_token = $push_notification['user_device']['devicetoken'];
        $message = $push_notification['message'];
        $custom_params = $push_notification['custom_param'];
        try{
            sendPushMessage($device_type,$device_token,$message,$custom_params);
            Models\PushNotification::where('id',$push_notification['id'])->delete();
        } catch (Exception $e) {
            
        }
    }
}
function sendPushMessage($deviceType, $deviceToken, $message, $custom_param){
    if($deviceType == 1){
        if (!empty(IPHONE_IS_LIVE)) {
            $ssl_url = 'ssl://gateway.push.apple.com:2195';
        } else {
            $ssl_url = 'ssl://gateway.sandbox.push.apple.com:2195';
        }
        $path = APP_PATH . '/server/php/Slim/lib/'.PEM_FILE; //Change your .pem file path
        $pass = PEM_PASSWORD; // Change your .pem File Password
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
        $fp = stream_socket_client($ssl_url, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
        );
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));            
        fclose($fp);
    }else{
        $api_access_key = API_ACCESS_KEY;
        // API access key from Google API's Console                
        $msg = array
        (
            'body' 	=> $message,
            'title'		=> "Instragram App",
            'vibrate'	=> 1,
            'sound'		=> 1,
        );      
        $fields = [
            'registration_ids'  => [$deviceToken],
            'notification'      => $msg
        ];            
        $headers = [
            'Authorization: key=' . $api_access_key,
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
    }
}