<?php
function sendSMS($message = array(), $to_user_id)
{
    $to_user = Models\User::find($to_user_id);
    $notificationMessages = "";
    if(!empty($to_user))
    {
        $mobile = $to_user->mobile_code . $to_user->mobile;
        $default_content = array(
            '##SITE_NAME##' => SITE_NAME
        );
        if(!empty($message['order_id'])){
            $order = Models\Order::with('restaurant')->find($message['order_id']);
            $default_content['##ORDER_NO##'] = $message['order_id'];
            $default_content['##BOOKING_DATE##'] = $order->created_at;
            $default_content['##RESTAURANT_NAME##'] = $order['restaurant']['name'];
            $default_content['##AMOUNT##'] = $order->total_price; 
            $default_content['##TOTAL_PRICE##'] = $order->total_price; 
            $default_content['##CURRENCY_SYMBOL##'] = CURRENCY_SYMBOL; 
            $default_content['##DELIVERY_CHARGE##'] = $order->delivery_charge;                                  
            $default_content['##SALES_TAX##'] = $order->sales_tax;   
            $default_content['##TRACKID##'] = $order->track_id;                                    
            if($message['message_type'] == 'paid'){
                $notificationMessage = SMS_FOR_NEW_BOOKING;
                $default_content['##ESTIMATED_TIME_TO_DELIVERY##'] = $order['restaurant']['estimated_time_to_delivery'];
            }elseif($message['message_type'] == 'assigned'){
                $notificationMessage = SMS_FOR_BOOKING_ASSIGN_TO_DELIVERY;
            }elseif($message['message_type'] == 'out for delivery'){
                $notificationMessage = SMS_FOR_BOOKING_OUT_FOR_DELIVERY;
            }elseif($message['message_type'] == 'delivered'){
                $notificationMessage = SMS_FOR_BOOKING_DELIVERED;
            }
        }
        if (!empty($notificationMessage)) {
            $notificationMessages = strtr($notificationMessage, $default_content);
        }
        if (!empty($mobile) && !empty($notificationMessages)) {
            //  send SMS
            //  @TODO
            //  try {
            //	    $Twilio = new Twilio(SMS_ACCOUNT_SID, SMS_GATEWAY_TOKEN);
            //		$message = $Twilio->account->messages->create($mobile, array (
            //			 'from' => SMS_FROM_NUMBER,
            //			  'body' => $notificationMessages
            //		 ));
            //		 Log::info($message);
            //  } catch (Exception $e) {
            //      Log::info($exp->getMessage());
            //  }
        }
    }
}
