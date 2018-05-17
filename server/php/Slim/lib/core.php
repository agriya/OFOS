<?php
/**
 * Core configurations
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
/**
 * sendmail
 *
 * @param string $template    template name
 * @param array  $replace_content   replace content
 * @param string  $to  to email address
 * @param string  $reply_to_mail  reply email address
 *
 * @return true or false
 */
 use \Firebase\JWT\JWT;
 use ReCaptcha\ReCaptcha;

function sendMail($template, $replace_content, $to, $reply_to_mail = '')
{
    global $_server_domain_url;
    $transport = Swift_MailTransport::newInstance();
    $mailer = Swift_Mailer::newInstance($transport);
    $default_content = array(
        '##SITE_NAME##' => SITE_NAME,
        '##SITE_URL##' => $_server_domain_url,
        '##FROM_EMAIL##' => SITE_FROM_EMAIL,
        '##CONTACT_EMAIL##' => SITE_CONTACT_EMAIL,
        '##TO_EMAIL##' => $to
    );
    $emailFindReplace = array_merge($default_content, $replace_content);
    $email_templates = Models\EmailTemplate::where('name', $template)->first();
    if (count($email_templates) > 0) {
        $content = $content_type = '';
        if ($email_templates['is_html']) {
            $content = $email_templates['html_email_content'];
            $content_type = 'text/html';
        } else {
            $content = $email_templates['text_email_content'];
            $content_type = 'text/plain';
        }
        $message = strtr($content, $emailFindReplace);
        $subject = strtr($email_templates['subject'], $emailFindReplace);
        $from_email = strtr($email_templates['from_email'], $emailFindReplace);
        $to_admins = strtr($email_templates['to_email'], $emailFindReplace);
        $message = Swift_Message::newInstance($subject)->setFrom(array(
            $from_email => SITE_NAME
        ))->setBody($message)->setContentType($content_type);
        $admins = explode (',', $to_admins);
        foreach($admins as $admin){
            $message->addTo($admin);
        }
        return $mailer->send($message);
    }
    return false;


}
/**
 * Insert current access ip address into IPs table
 *
 * @return int IP id
 */
function saveIp()
{
    $ip = new Models\Ip;
    $ips = $ip->where('ip', $_SERVER['REMOTE_ADDR'])->first();
    if (!empty($ips)) {
        return $ips['id'];
    } else {
        $save_ip = new Models\Ip;
        $save_ip->ip = $_SERVER['REMOTE_ADDR'];
        $save_ip->host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $save_ip->save();
        return $save_ip->id;
    }
}
/**
 * Checking already username is exists in users table
 *
 * @return true or false
 */
function checkAlreadyUsernameExists($username)
{
    $user = Models\User::where('username', $username)->where('is_created_from_order_page', 0)->first();
    if (!empty($user)) {
        return true;
    }
    return false;
}
/**
 * Checking already name is exists in cuisines table
 *
 * @return true or false
 */
function checkAlreadyCuisineNameExists($name)
{
    $cuisine = Models\Cuisine::where('name', $name)->first();
    if (!empty($cuisine)) {
        return true;
    }
    return false;
}
/**
 * Checking already email is exists in users table
 *
 * @return true or false
 */
function checkAlreadyEmailExists($email)
{
    $user = Models\User::where('email', $email)->where('is_created_from_order_page', 0)->first();
    if (!empty($user)) {
        return true;
    }
    return false;
}
/**
 * Checking already mobile is exists in users profile table
 *
 * @return true or false
 */
function checkAlreadyMobileExists($mobile)
{
    $user = Models\User::where('mobile', $mobile)->where('is_created_from_order_page', 0)->first();
    if (!empty($user)) {
        return true;
    }
    return false;
}
/**
 * Checking already name is exists in restaurants table
 *
 * @return true or false
 */
function checkAlreadyRestaurantNameExists($name)
{
    $restaurant = Models\Restaurant::where('name', $name)->first();
    if (!empty($restaurant)) {
        return true;
    }
    return false;
}
/**
 * Checking already name is exists in restaurant_categories table
 *
 * @return true or false
 */
function checkAlreadyCategoryNameExists($name, $restaurant_id)
{
    $restaurant_category = Models\RestaurantCategory::where('name', $name)->where('restaurant_id', $restaurant_id)->first();
    if (!empty($restaurant_category)) {
        return true;
    }
    return false;
}
/**
 * Checking already mobile is exists in users profile table
 *
 * @return true or false
 */
function checkAlreadyMobileExistsAnotherUser($mobile, $user_id)
{
    $user = Models\User::where('mobile', $mobile)->whereNotIn('id', [$user_id])->count();
    if ($user > 0) {
        return true;
    }
    return false;
}

/**
 * To generate random string
 *
 * @param array  $arr_characters Random string options
 * @param string $length         Length of the random string
 *
 * @return string
 */
function getRandomStr($arr_characters, $length)
{
    $rand_str = '';
    $characters_length = count($arr_characters);
    for ($i = 0; $i < $length; ++$i) {
        $rand_str.= $arr_characters[rand(0, $characters_length - 1) ];
    }
    return $rand_str;
}
/**
 * To generate the encrypted password
 *
 * @param string $str String to be encrypted
 *
 * @return string
 */
function getCryptHash($str)
{
    $salt = '';
    if (CRYPT_BLOWFISH) {
        if (version_compare(PHP_VERSION, '5.3.7') >= 0) { // http://www.php.net/security/crypt_blowfish.php
            $algo_selector = '$2y$';
        } else {
            $algo_selector = '$2a$';
        }
        $workload_factor = '12$'; // (around 300ms on Core i7 machine)
        $val_arr = array(
            '.',
            '/'
        );
        $range1 = range('0', '9');
        $range2 = range('a', 'z');
        $range3 = range('A', 'Z');
        $res_arr = array_merge($val_arr, $range1, $range2, $range3);
        $salt = $algo_selector . $workload_factor . getRandomStr($res_arr, 22); // './0-9A-Za-z'
    } elseif (CRYPT_MD5) {
        $algo_selector = '$1$';
        $char1 = chr(33);
        $char2 = chr(127);
        $range = range($char1, $char2);
        $salt = $algo_selector . getRandomStr($range, 12); // actually chr(0) - chr(255), but used ASCII only
    } elseif (CRYPT_SHA512) {
        $algo_selector = '$6$';
        $workload_factor = 'rounds=5000$';
        $char1 = chr(33);
        $char2 = chr(127);
        $range = range($char1, $char2);
        $salt = $algo_selector . $workload_factor . getRandomStr($range, 16); // actually chr(0) - chr(255)
    } elseif (CRYPT_SHA256) {
        $algo_selector = '$5$';
        $workload_factor = 'rounds=5000$';
        $char1 = chr(33);
        $char2 = chr(127);
        $range = range($char1, $char2);
        $salt = $algo_selector . $workload_factor . getRandomStr($range, 16); // actually chr(0) - chr(255)
    } elseif (CRYPT_EXT_DES) {
        $algo_selector = '_';
        $val_arr = array(
            '.',
            '/'
        );
        $range1 = range('0', '9');
        $range2 = range('a', 'z');
        $range3 = range('A', 'Z');
        $res_arr = array_merge($val_arr, $range1, $range2, $range3);
        $salt = $algo_selector . getRandomStr($res_arr, 8); // './0-9A-Za-z'.
    } elseif (CRYPT_STD_DES) {
        $algo_selector = '';
        $val_arr = array(
            '.',
            '/'
        );
        $range1 = range('0', '9');
        $range2 = range('a', 'z');
        $range3 = range('A', 'Z');
        $res_arr = array_merge($val_arr, $range1, $range2, $range3);
        $salt = $algo_selector . getRandomStr($res_arr, 2); // './0-9A-Za-z'
    }
    return crypt($str, $salt);
}
/**
 * To login using social networking site accounts
 *
 * @params $profile
 * @params $provider_id
 * @params $provider
 * @params $adapter
 * @return array
 */
function social_login($adapter, $provider, $profile)
{
    global $authUser;
    if ($provider['id'] == \Constants\SocialLogins::TWITTER) {
        $access_token = $profile->access_token;
        $access_token_secret = $profile->access_token_secret;
    } else {
        $access_token = $profile->access_token;
    }
    $providerUser = Models\ProviderUser::where('provider_id', $provider['id'])->where('foreign_id', $profile->identifier)->where('is_connected', true)->first();
    if (!empty($providerUser)) {
        $providerUser->access_token = $access_token;
        $providerUser->update();
        if (empty($authUser)) {
            $loggedin_user_id = $providerUser['user_id'];
        } else if (!empty($authUser) && $authUser['id'] != $providerUser['user_id']) {
            $response = array(
                'error' => array(
                    'code' => 1,
                    'message' => 'Some other user connected'
                )
            );
        }
    } else {
        if (!empty($authUser)) {
            $providerUser = new Models\ProviderUser;
            $providerUser->user_id = $authUser['id'];
            $providerUser->provider_id = $provider['id'];
            $providerUser->foreign_id = $profile->identifier;
            $providerUser->access_token = $access_token;
            $providerUser->access_token_secret = !empty($access_token_secret) ? $access_token_secret : '';
            $providerUser->is_connected = true;
            $providerUser->profile_picture_url = !empty($profile->photoURL) ? $profile->photoURL : '';
            $providerUser->save();
            $response = array(
                'error' => array(
                    'code' => 0,
                    'message' => 'Connected succesfully'
                )
            );
        } else {
            $isEmailExist = Models\User::where('email', $profile->email)->first();
            if (empty($isEmailExist)) {
                $user = new Models\User;
                $username = strtolower(str_replace(' ', '', $profile->displayName));
                $username = $user->checkUserName($username);
                $user->username = Inflector::slug($username, '-');
                $user->email = $profile->email;
                $user->password = getCryptHash('default'); // dummy password
                $user->is_email_confirmed = true;
                $user->is_active = true;
                $user->last_logged_in_time = date('Y-m-d H:i:s');
                $user->provider_id = $provider['id'];
                $ip_id = saveIp();
                if (!empty($ip_id)) {
                    $user->last_login_ip_id = $ip_id;
                }
                $user->save();
                $providerUser = new Models\ProviderUser;
                $providerUser->user_id = $user->id;
                $providerUser->provider_id = $provider['id'];
                $providerUser->foreign_id = $profile->identifier;
                $providerUser->access_token = $access_token;
                $providerUser->access_token_secret = !empty($access_token_secret) ? $access_token_secret : '';
                $providerUser->is_connected = true;
                $providerUser->profile_picture_url = !empty($profile->photoURL) ? $profile->photoURL : '';
                $providerUser->save();
                $loggedin_user_id = $user->id;
            } else {
                $response = array(
                    'error' => array(
                        'code' => 1,
                        'message' => 'Email already exist'
                    )
                );
            }
        }
    }
    if (!empty($loggedin_user_id)) {
        $condition = array(
            'id' => $loggedin_user_id,
            'is_active' => 1,
            'is_email_confirmed' => 1
        );
        $user = Models\User::where($condition)->first();
        if (!empty($user)) {
            $token = array(
                'token' => Models\User::getToken($user->id)
            );
            Models\UserToken::insertUserToken($user->id, $token['token'], '', true);
            $response = $token + $user->toArray();
            $response['error']['code'] = 0;
        } else {
            $response = array(
                'error' => array(
                    'code' => 1,
                    'message' => 'Your account is deactivated.'
                )
            );
        }
    }
    return $response;
}
/**
 * Curl _execute
 *
 * @params string $url
 * @params string $method
 * @params array $method
 * @params string $format
 *
 * @return array
 */
function _execute($url, $method = 'get', $post = array(), $format = 'plain')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    if ($method == 'get') {
        curl_setopt($ch, CURLOPT_POST, false);
    } elseif ($method == 'post') {
        if ($format == 'json') {
            $post_string = json_encode($post);
            $header = array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_string)
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        } else {
            $post_string = http_build_query($post, '', '&');
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    } elseif ($method == 'put') {
        if ($format == 'json') {
            $post_string = json_encode($post);
            $header = array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_string)
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        } else {
            $post_string = http_build_query($post, '', '&');
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    } elseif ($method == 'delete') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // Note: timeout also falls here...
    if (curl_errno($ch)) {
        $return['error']['message'] = curl_error($ch);
        curl_close($ch);
        return $return;
    }
    switch ($http_code) {
        case 201:
        case 200:
            if (isJson($response)) {
                $return = safe_json_decode($response);
            } else {
                $return = $response;
            }
            break;

        case 401:
            $return['error']['code'] = 1;
            $return['error']['message'] = 'Unauthorized';
            break;

        default:
            $return['error']['code'] = 1;
            $return['error']['message'] = 'Not Found';
    }
    curl_close($ch);
    return $return;
}
/**
 * To check whether it is json or not
 *
 * @param json $string To check string is a JSON or not
 *
 * @return mixed
 */
function isJson($string)
{
    json_decode($string);
    //check last json error
    return (json_last_error() == JSON_ERROR_NONE);
}
/**
 * safe Json code
 *
 * @param json $json   json data
 *
 * @return array
 */
function safe_json_decode($json)
{
    $return = json_decode($json, true);
    if ($return === null) {
        $error['error']['code'] = 1;
        $error['error']['message'] = 'Syntax error, malformed JSON';
        return $error;
    }
    return $return;
}
/**
 * Get request by using CURL
 *
 * @param string $url    URL to execute
 *
 * @return mixed
 */
function _doGet($url)
{
    $return = _execute($url);
    return $return;
}
/**
 * Post request by using CURL
 *
 * @param string $url    URL to execute
 * @param array  $post   Post data
 * @param string $format To differentiate post data in plain or json format
 *
 * @return mixed
 */
function _doPost($url, $post = array(), $format = 'plain')
{
    return _execute($url, 'post', $post, $format);
}
/**
 * Render Json Response
 *
 * @param array $response    response
 * @param string  $message  Messgae
 * @param string  $fields  fields
 * @param int  $isError  isError
 * @param int  $statusCode  Status code
 *
 * @return json response
 */
function renderWithJson($response, $message = '', $fields = '', $isError = 0, $statusCode = 200)
{
    global $app;
    $appResponse = $app->getContainer()->get('response');
    if (!empty($fields)) {
        $statusCode = 422;
    }
    $error = array(
        'error' => array(
            'code' => $isError,
            'message' => $message,
            'fields' => $fields
        )
    );
    return $appResponse->withJson($response + $error, $statusCode);
}

function gen_uuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        // 48 bits for "node"
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}
/**
 * Attachment save process
 *
 */
function saveImage($class_name, $file, $foreign_id, $is_multi = false)
{
    if ((!empty($file)) && (file_exists(APP_PATH . '/media/tmp/' . $file))) {
        //Removing and ree inserting new image
        $userImg = Models\Attachment::where('foreign_id', $foreign_id)->where('class', $class_name)->first();
        if (!empty($userImg) && !($is_multi)) {
            if (file_exists(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $userImg['filename'])) {
                unlink(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $userImg['filename']);
                $userImg->delete();
            }
            // Removing Thumb folder images
            $mediadir = IMAGES_PATH . DS;

            foreach (THUMB_SIZES as $key => $value) {
                $list = glob($mediadir . $key . '/' . $class_name . '/' . $foreign_id . '.*');
                if ($list) {
                    @unlink($list[0]);
                }
            }
        }
        $attachment = new Models\Attachment;
        if (!file_exists(APP_PATH . '/media/' . $class_name . '/' . $foreign_id)) {
            mkdir(APP_PATH . '/media/' . $class_name . '/' . $foreign_id, 0777, true);
        }
        $src = APP_PATH . '/media/tmp/' . $file;
        $dest = APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $file;
        copy($src, $dest);
        unlink($src);
        $info = getimagesize($dest);
        $width = $info[0];
        $height = $info[1];
        $attachment->filename = $file;
        $attachment->width = $width;
        $attachment->height = $height;
        $attachment->dir = $class_name . '/' . $foreign_id;
        $attachment->foreign_id = $foreign_id;
        $attachment->class = $class_name;
        $attachment->mimetype = $info['mime'];
        $attachment->save();
        return $attachment->id;
    }
}
/**
 * base64 atachment save process
 *
 */
function saveImageData($class_name, $file, $foreign_id, $is_multi = false)
{
        if (!empty($file)) {  
            $data = explode( ',', $file );
            $file = $data['0'];
            $image = base64_decode($file);
            $f = finfo_open();
            $mime_type = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
            $imageextension = explode("/", $mime_type);
            $name = md5(time());
            $file = $name. '.' .$imageextension[1];     
            //Removing and ree inserting new image
            $userImg = Models\Attachment::where('foreign_id', $foreign_id)->where('class', $class_name)->first();
            if (!empty($userImg) && !($is_multi)) {
                if (file_exists(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $userImg['filename'])) {
                    unlink(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $userImg['filename']);
                    $userImg->delete();
                }
                // Removing Thumb folder images
                $mediadir = IMAGES_PATH . DS;

                foreach (THUMB_SIZES as $key => $value) {
                    $list = glob($mediadir . $key . '/' . $class_name . '/' . $foreign_id . '.*');
                    if ($list) {
                        @unlink($list[0]);
                    }
                }
        
            }
            $attachment = new Models\Attachment;
            if (!file_exists(APP_PATH . '/media/' . $class_name . '/' . $foreign_id)) {
                mkdir(APP_PATH . '/media/' . $class_name . '/' . $foreign_id, 0777, true);
            }
            $fp = fopen(APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $file, 'w+') or die("Unable to open file!");
            fwrite($fp, $image);
            fclose($fp); 
            $dest = APP_PATH . '/media/' . $class_name . '/' . $foreign_id . '/' . $file;
            $info = getimagesize($dest);
            $width = $info[0];
            $height = $info[1];
            $attachment->filename = $file;
            $attachment->width = !empty($width) ? $width : 0;
            $attachment->height = !empty($height) ? $height : 0;
            $attachment->dir = $class_name . '/' . $foreign_id;
            $attachment->foreign_id = $foreign_id;
            $attachment->class = $class_name;
            $attachment->mimetype = !empty($info['mime']) ? $info['mime'] : '';
            $attachment->save();
            return $attachment->id;
    }
}
//transaction list page description
function transactionDescription($transactions)
{
    global $authUser;
    $new_transaction = array ();
    if (!empty($transactions)) {
        try {
            $i = 0;
            foreach ($transactions as $transaction) {
                $id = $transaction['id'];
                $new_transaction[$i] = $transaction;
                if (!empty($transaction['order']['restaurant_id'])) {
                    $restaurant = Models\Restaurant::find($transaction['order']['restaurant_id']);
                }
                if (!empty($restaurant)) {
                    $restaurant_name = $restaurant->name;
                } else {
                    $restaurant_name = !empty($transaction['other_user']['restaurant']['name']) ? $transaction['other_user']['restaurant']['name'] : '';
                }
                $transactionReplace = array(
                    '##USER##' => $transaction['user']['username'],
                    '##RESTAURANT##' => $restaurant_name,
                    '##ORDER_ID##' => $transaction['order']['id']
                );
                if (!empty($transaction['transaction_type']['message_for_receiver']) && $transaction['other_user_id'] == $authUser->id) {
                    $new_transaction[$i]['description'] = strtr($transaction['transaction_type']['message_for_receiver'], $transactionReplace);
                } elseif ($authUser->role_id == \Constants\ConstUserTypes::ADMIN) {
                    $new_transaction[$i]['description'] = strtr($transaction['transaction_type']['message_for_admin'], $transactionReplace);
                } else {
                    $new_transaction[$i]['description'] = strtr($transaction['transaction_type']['message'], $transactionReplace);
                }
                if ($authUser->role_id == \Constants\ConstUserTypes::ADMIN) {
                    $transaction_types = array(
                        \Constants\ConstTransactionTypes::ORDERPLACED,
                        \Constants\ConstTransactionTypes::REFUNDFORREJECTEDORDER,
                        \Constants\ConstTransactionTypes::PAIDAMOUNTTORESTAURANT
                    );
                    if ($transaction['other_user_id'] == $authUser->id || ($transaction['class'] == "Wallet" && $transaction['transaction_type_id'] == \Constants\ConstTransactionTypes::ADDEDTOWALLET)) {
                        $new_transaction[$i]['debit_amount'] = 0;
                        $new_transaction[$i]['credit_amount'] = $transaction['amount'];
                    } elseif ($transaction['class'] == "Order" && in_array($transaction['transaction_type_id'], $transaction_types)) {
                        $new_transaction[$i]['debit_amount'] = $transaction['amount'];
                        $new_transaction[$i]['credit_amount'] = 0;
                    }
                } else {
                    if ($transaction['other_user_id'] == $authUser->id) {
                        $new_transaction[$i]['debit_amount'] = 0;
                        $new_transaction[$i]['credit_amount'] = $transaction['amount'];
                    } elseif ($transaction['class'] == "Wallet" && $transaction['transaction_type_id'] == \Constants\ConstTransactionTypes::ADDEDTOWALLET) {
                        $new_transaction[$i]['debit_amount'] = 0;
                        $new_transaction[$i]['credit_amount'] = $transaction['amount'];
                    } elseif ($transaction['user_id'] == $authUser->id) {
                        $new_transaction[$i]['debit_amount'] = $transaction['amount'];
                        $new_transaction[$i]['credit_amount'] = 0;
                    }
                }
                $i++;
            }
            return $new_transaction;
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    }
    return $new_transaction;
}
function merged_menus($menus, $merge_menus)
{
    foreach ($merge_menus as $key => $menu) {
        if (isset($menus[$key])) {
            $menus[$key]['child_sub_menu'] = array_merge($menus[$key]['child_sub_menu'], $menu['child_sub_menu']);
        } else {
            $menus[$key] = $menu;
        }
        
    }
    return $menus;
}
function menu_sub_array_sorting($array, $on, $order = SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();
    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }
        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;

            case SORT_DESC:
                arsort($sortable_array);
                break;
        }
        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }
    return $new_array;
}
function getReturnURL($model, $response)
{
    global $_server_domain_url;
    $result = array();
    $result['success_url'] = $response->success_url;
    $result['failure_url'] = $response->cancel_url;
    return $result;
}
function generateRandomPassword($length = 8)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}
function captchaCheck($captcha)
{
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $secret = CAPTCHA_SECRET_KEY;
    $recaptcha = new ReCaptcha($secret);
    $response = $recaptcha->verify($captcha, $remoteip);
    if ($response->isSuccess()) {
        return true;
    } else {
        return false;
    }
}