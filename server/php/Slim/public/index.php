<?php
/**
 * API Endpoints
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
require_once '../lib/bootstrap.php';
/**
 * POST usersRegisterPost
 * Summary: New user
 * Notes: Post new user.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/register', function ($request, $response, $args) {
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $result = array();
    $user = new Models\User($args);
    $validationErrorFields = $user->validate($args);
    if (is_object($validationErrorFields)) {
        $validationErrorFields = $validationErrorFields->toArray();
    }
    $validationErrorFields = empty($validationErrorFields) ? [] : $validationErrorFields; 
    $is_save = 0;
    $user->is_created_from_order_page = 1;
    if (!empty($args['is_create_an_account'])) {
        $user->is_created_from_order_page = false;
    }
    if(!empty($args['username']) && checkAlreadyUsernameExists($args['username'])) {
        $validationErrorFields['username'] = array('unique');   
    }
    if(!empty($args['email']) && checkAlreadyEmailExists($args['email'])) {
        $validationErrorFields['email'] = array('unique');
    }
    if(!empty($args['mobile']) && checkAlreadyMobileExists($args['mobile'])) {        
        $validationErrorFields['mobile number'] = array('unique');
    }
    if(!empty($args['mobile']) && !empty($args['mobile_code'])){
        try{
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $swissNumberProto = $phoneUtil->parse($args['mobile'], $args['mobile_code']);
            if(!$phoneUtil->isValidNumber($swissNumberProto)) {                
                $validationErrorFields['mobile'] = "Invalid mobile number and mobile code";
            } else {
                $mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                $user->mobile = $mobile_without_code = $swissNumberProto->getNationalNumber();
                $user->mobile_code = str_replace($mobile_without_code, '', $mobile);
            }
        }catch(\libphonenumber\NumberParseException $e){
            $validationErrorFields['mobile'] = $e->getMessage();
        }            
    }    
    if (!empty($args['is_become_restaurant']) && !empty($args['restaurants']) && isPluginEnabled('Restaurant/Restaurant')) {
            $restaurant_result = Models\Restaurant::restaurantValidation($args['restaurants']);            
            if (empty($restaurant_result['error_status'])) {
                $validationErrorFields['restaurant'] = $restaurant_result['validation'];
            }
    } 
    if (!empty(USER_IS_CAPTCHA_ENABLED_REGISTER) && (empty($args['access_from']) || (!empty($args['access_from']) && $args['access_from'] != 'app'))) {        
        if (!empty($args['captcha_response'])) {
            $captcha = $args['captcha_response'];
            if (captchaCheck($captcha) == false) {
                $validationErrorFields['captcha'] = 'Captcha Verification failed.';
            }
        } else {
            $validationErrorFields['captcha'] = 'Captcha Required';
        }        
    }
    if (empty($validationErrorFields)) {
        if (!empty($args['password'])) {
            $user->password = getCryptHash($args['password']);
        }
        try { 
            $user->is_email_confirmed = (USER_IS_EMAIL_VERIFICATION_FOR_REGISTER == 1) ? 0 : 1;
            $user->is_active = (USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER == 1) ? 0 : 1;
            if (USER_IS_AUTO_LOGIN_AFTER_REGISTER == 1) {
                $user->is_email_confirmed = 1;
                $user->is_active = 1;
            }
            if (!empty($args['is_become_restaurant'])) {
                $user->is_active = 0;
            }
            if (empty($args['is_create_an_account']) && empty($args['username'])) {
                $user->username = Models\User::generateUsername($args['email']);
                $user->password = getCryptHash(generateRandomPassword(8));               
            }         
            $checkUser = Models\User::where('is_created_from_order_page', 1)
                             ->where(function ($q) use ($args) {            
                                $q->where('email', $args['email']);
                                $q->orWhere('mobile', $args['mobile']);
                            })->first();
            if (!empty($checkUser)) {
                unset($user->is_email_confirmed);
                unset($user->is_active);
                $user->id = $checkUser->id;
                $is_save = 1;                
            }
            if (empty($is_save)) {
                $user->save();
            } else {                
                $checkUser->fill($user->toArray());
                $checkUser->update();
            }            
            if (!empty($args['is_become_restaurant']) && !empty($args['restaurants']) && isPluginEnabled('Restaurant/Restaurant')) {
                $args['restaurants']['user_id'] = $user->id;
                $args['restaurants']['contact_name'] = $user->username;
                $args['restaurants']['email'] = $user->email;
                Models\Restaurant::saveRestaurant($args['restaurants']);
            }
            // send to admin mail if USER_IS_ADMIN_MAIL_AFTER_REGISTER is true
            if (USER_IS_ADMIN_MAIL_AFTER_REGISTER == 1) {
                $emailFindReplace = array(
                    '##USERNAME##' => $user->username,
                    '##USEREMAIL##' => $user->email,
                    '##SUPPORT_EMAIL##' => SUPPORT_EMAIL
                );
                sendMail('newuserjoin', $emailFindReplace, SITE_CONTACT_EMAIL);
            }
            if (USER_IS_WELCOME_MAIL_AFTER_REGISTER == 1) {
                $emailFindReplace = array(
                    '##USERNAME##' => $user->username,
                    '##SUPPORT_EMAIL##' => SUPPORT_EMAIL
                );
                // send welcome mail to user if USER_IS_WELCOME_MAIL_AFTER_REGISTER is true
                sendMail('welcomemail', $emailFindReplace, $user->email);
            }
            if (USER_IS_EMAIL_VERIFICATION_FOR_REGISTER == 1) {
                $emailFindReplace = array(
                    '##USERNAME##' => $user->username,
                    '##ACTIVATION_URL##' => $_server_domain_url . '/users/' . $user->id . '/activation/' . md5($user->username)
                );
                sendMail('activationrequest', $emailFindReplace, $user->email);
            }
            if (USER_IS_AUTO_LOGIN_AFTER_REGISTER == 1) {
                $token = array(
                    'token' => Models\User::getToken($user->id)
                );
                Models\UserToken::insertUserToken($user->id, $token['token'], $request);
                $result = $token + $user->toArray();
            } else {
                $user = Models\User::find($user->id);
                $result = $user->toArray();
            }
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {       
        return renderWithJson($result, 'User could not be added. Please, try again.', $validationErrorFields, 1);
    }
});
/**
 * PUT usersUserIdActivationHashPut
 * Summary: User activation
 * Notes: Send activation hash code to user for activation. \n
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}/activation/{hash}', function ($request, $response, $args) {
    $result = array();
    $user = Models\User::where('id', $request->getAttribute('userId'))->first();
    if (!empty($user)) {
        if (md5($user['username']) == $request->getAttribute('hash')) {
            $user->is_email_confirmed = 1;
            $user->is_active = (USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER == 0 || USER_IS_AUTO_LOGIN_AFTER_REGISTER == 1) ? 1 : 0;
            $user->save();
            if (USER_IS_AUTO_LOGIN_AFTER_REGISTER == 1) {
                $token = array(
                    'token' => Models\User::getToken($user->id)
                );
                Models\UserToken::insertUserToken($user->id, $token['token'], $request);              
                $result['data'] = $token + $user->toArray();
            } else {
                $result['data'] = $user->toArray();
            }
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Invalid user deatails.', '', 1);
        }
    } else {
        return renderWithJson($result, 'Invalid user deatails.', '', 1);
    }
});
/**
 * POST usersLoginPost
 * Summary: User login
 * Notes: User login information post
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/login', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $result = array();
    $user = new Models\User;
    if (strtolower(USER_USING_TO_LOGIN) == 'username') {
        $log_user = $user->with('restaurant', 'restaurant_supervisor', 'restaurant_delivery_person')->where('username', $body['username'])->where('is_active', 1)->where('is_email_confirmed', 1)->first();
    } else {
        $log_user = $user->with('restaurant', 'restaurant_supervisor', 'restaurant_delivery_person')->where('email', $body['email'])->where('is_active', 1)->where('is_email_confirmed', 1)->first();
    }
    $password = crypt($body['password'], $log_user['password']);
    $validationErrorFields = $user->validate($body);
    if (empty($validationErrorFields) && !empty($log_user) && $password == $log_user['password']) {
        $token = array(
            'token' => Models\User::getToken($log_user->id)
        );
        Models\UserToken::insertUserToken($log_user->id, $token['token'], $request);
        $result = $token + $log_user->toArray();            
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'Your login credentials are invalid.', $validationErrorFields, 1);
    }
});
/**
 * POST userSocialLoginPost
 * Summary: User Social Login
 * Notes:  Social Login
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/social_login', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $queryParams = $request->getQueryParams();
    $result = array();
    if (!empty($queryParams['type'])) {
        include '../lib/vendors/Providers/' . $queryParams['type'] . '.php';
        $provider = Models\Provider::where('name', ucfirst($queryParams['type']))->first();
        $body['secret_key'] = $provider['secret_key'];
        $body['api_key'] = $provider['api_key'];
        $class_name = 'Providers_' . $provider['name'];
        $adapter = new $class_name();
        if (!empty($body['code'])) {
            $access_token = $adapter->getAccessToken($body);
            if ($access_token) {
                $profile = $adapter->getUserProfile($access_token, $provider);
                if (!empty($profile->email)) {
                    $response = social_login($adapter, $provider, $profile);
                } else {
                    $response = array(
                        'is_email_missing' => 1,
                        'type' => $queryParams['type'],
                        'access_token' => $access_token
                    );
                }
            } else {
                return renderWithJson($result, 'Could not login. Please try again later.', '', 1, 422);
            }
        } else if (!empty($body['access_token'])) {
            $profile = $adapter->getUserProfile($body['access_token'], $provider);
            if (!empty($body['email'])) {
                $profile->email = $body['email'];
            }
            $response = social_login($adapter, $provider, $profile);
        }
        return renderWithJson($response);
    } else {
        return renderWithJson($result, 'Please choose one provider.', '', 1);
    }
});
/**
 * Get userSocialLoginGet
 * Summary: Social Login for twitter
 * Notes: Social Login for twitter
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/social_login', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array ();
    if (!empty($queryParams['type']) && in_array($queryParams['type'], array('twitter'))) {
        include '../lib/vendors/Providers/' . $queryParams['type'] . '.php';
        $provider = Models\Provider::where('name', ucfirst($queryParams['type']))->first();
        $class_name = 'Providers_' . $provider['name'];
        $adapter = new $class_name();
        $response = $adapter->getRequestToken($provider);
        return renderWithJson($response);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
/**
 * POST usersForgotPasswordPost
 * Summary: User forgot password
 * Notes: User forgot password
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users/forgot_password', function ($request, $response, $args) {
    $result = array();
    $args = $request->getParsedBody();
    $user = Models\User::where('email', $args['email'])->first();
    if (!empty($user)) {
        $validationErrorFields = $user->validate($args);
        if (!empty(USER_IS_CAPTCHA_ENABLED_FORGOT_PASSWORD) && (empty($args['access_from']) || (!empty($args['access_from']) && $args['access_from'] != 'app'))) {        
            if (!empty($args['captcha_response'])) {
                $captcha = $args['captcha_response'];
                if (captchaCheck($captcha) == false) {
                    $validationErrorFields['captcha'] = 'Captcha Verification failed.';
                }
            } else {
                $validationErrorFields['captcha'] = 'Captcha Required';
            }        
        }
        if (empty($validationErrorFields)) {
            //Checking user registered as social network
            if (!empty($user['provider_id'])) {
                if ($user['provider_id'] == \Constants\SocialLogins::FACEBOOK) {
                    $site = 'Facebook';
                } elseif ($user['provider_id'] == \Constants\SocialLogins::TWITTER) {
                    $site = 'Twitter';
                } elseif ($user['provider_id'] == \Constants\SocialLogins::GOOGLEPLUS) {
                    $site = 'Google Plus';
                }
                $emailFindReplace = array(
                    '##SUPPORT_EMAIL##' => SUPPORT_EMAIL,
                    '##OTHER_SITE##' => $site,
                    '##USERNAME##' => $user['username']
                );
                sendMail('failedsocialuser', $emailFindReplace, $user['email']);
                return renderWithJson($result, 'Process could not be found', '', 1);
            }
            $password = uniqid();
            $user->password = getCryptHash($password);
            try {
                $user->save();
                $emailFindReplace = array(
                    '##USERNAME##' => $user['username'],
                    '##PASSWORD##' => $password,
                );
                sendMail('forgotpassword', $emailFindReplace, $user['email']);
                return renderWithJson($result, 'An email has been sent with your new password', '', 0);
            } catch (Exception $e) {
                return renderWithJson($result, $e->getMessage(), '', 1);
            }
        } else {
            return renderWithJson($result, 'Process could not be found', $validationErrorFields, 1);
        }
    } else {
        $emailFindReplace = array(
            '##SUPPORT_EMAIL##' => SUPPORT_EMAIL,
            '##USEREMAIL##' => $args['email']
        );
        sendMail('failledforgotpassword', $emailFindReplace, $args['email']);
        return renderWithJson($result, 'No data found', '', 1);
    }
});
/**
 * PUT UsersuserIdChangePasswordPut .
 * Summary: Update change password
 * Notes: update change password
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}/change_password', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    $user = Models\User::find($request->getAttribute('userId'));
    $validationErrorFields = $user->validate($args);
    if (empty($validationErrorFields)) {
        $change_password = $args['new_password'];
        $user->password = getCryptHash($change_password);
        try {
            $user->save();
            $emailFindReplace = array(
                '##PASSWORD##' => $args['new_password'],
                '##USERNAME##' => $user['username']
            );
            sendMail('adminchangepassword', $emailFindReplace, $user->email);
            $result['data'] = $user->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'User Password could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateUserPassword'));
/**
 * PUT UsersuserIdChangePasswordPut .
 * Summary: Update change password
 * Notes: update change password
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/change_password', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    $user = Models\User::find($authUser->id);
    $validationErrorFields = $user->validate($args);
    $password = crypt($args['password'], $user['password']);
    if (empty($validationErrorFields)) {
        if ($password == $user['password']) {
            $change_password = $args['new_password'];
            $user->password = getCryptHash($change_password);
            try {
                $user->save();
                $emailFindReplace = array(
                    '##PASSWORD##' => $args['new_password'],
                    '##USERNAME##' => $user['username']
                );
                sendMail('changepassword', $emailFindReplace, $user['email']);
                $result['data'] = $user->toArray();
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, $e->getMessage(), '', 1);
            }
        } else {
            return renderWithJson($result, 'Password is invalid . Please, try again', '', 1);
        }
    } else {
        return renderWithJson($result, 'User Password could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateChangePassword'));
/**
 * GET usersLogoutGet
 * Summary: User Logout
 * Notes: oauth
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/logout', function ($request, $response, $args) {
    if (!empty($_GET['token'])) {
        try {
            $oauth = Models\OauthAccessToken::where('access_token', $_GET['token'])->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson(array(), $e->getMessage(), '', 1);
        }
    }
});
/**
 * GET UsersGet
 * Summary: Get  users
 * Notes: Filter users.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $users = Models\User::Filter($queryParams)->paginate()->toArray();
        $data = $users['data'];
        unset($users['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $users
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canListUser'));
/**
 * POST UserPost
 * Summary: Create New user by admin
 * Notes: Create New user by admin
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/users', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $user = new Models\User($args);
    $validationErrorFields = $user->validate($args);
    $validationErrorFields['unique'] = array();
    if (checkAlreadyUsernameExists($args['username'])) {
        array_push($validationErrorFields['unique'], 'username');
    }
    if (checkAlreadyEmailExists($args['email'])) {
        array_push($validationErrorFields['unique'], 'email');
    }
    if (checkAlreadyMobileExists($args['mobile'])) {
        array_push($validationErrorFields['unique'], 'mobile number');
    }
    if (empty($validationErrorFields['unique'])) {
        unset($validationErrorFields['unique']);
    }
    if(!empty($args['mobile']) && !empty($args['mobile_code'])){
        try{
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $swissNumberProto = $phoneUtil->parse($args['mobile'], $args['mobile_code']);
            if(!$phoneUtil->isValidNumber($swissNumberProto)) {
                $validationErrorFields['mobile'] = "Invalid mobile number and mobile code";
            } else {
                $mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                $user->mobile = $mobile_without_code = $swissNumberProto->getNationalNumber();
                $user->mobile_code = str_replace($mobile_without_code, '', $mobile);
            }
        }catch(\libphonenumber\NumberParseException $e){
            $validationErrorFields['mobile'] = $e->getMessage();
        }            
    }
    if (empty($validationErrorFields)) {
        $user->password = getCryptHash($args['password']);
        try {
            unset($user->location);
            unset($user->state);
            unset($user->city);
            unset($user->country);
            $user->is_active = 1;
            $user->is_email_confirmed = 1;
            $user->save();
            $emailFindReplace_user = array(
                '##USERNAME##' => $user->username,
                '##LOGINLABEL##' => (USER_USING_TO_LOGIN == 'username') ? 'Username' : 'Email',
                '##USEDTOLOGIN##' => (USER_USING_TO_LOGIN == 'username') ? $user->username : $user->email,
                '##PASSWORD##' => $args['password']
            );
            sendMail('adminuseradd', $emailFindReplace_user, $user->email);
            $result = $user->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'User could not be added. Please, try again.', $validationErrorFields, 1);
    }
});
/**
 * GET UseruserIdGet
 * Summary: Get particular user details
 * Notes: Get particular user details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();    
    $result = array();
    $user = Models\User::Filter($queryParams)->find($request->getAttribute('userId'));
    if (!empty($user)) {
        $user->mobile_code_country_id = null;
        if (!empty($user->mobile_code)) {
            $country = Models\Country::select('id')->where('phone', $user->mobile_code)->first();
            $user->mobile_code_country_id = $country['id'];
        }
        $user = $user->toArray();
        if (empty($user['city'])) {
            $user['city']['name'] = '';
        }
        if (empty($user['state'])) {
            $user['state']['name'] = '';
        }
        if (empty($user['country'])) {
            $user['country']['name'] = '';
        }
        $result['data'] = $user;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'User not Found', '', 1, 404);
    }
})->add(new Acl\ACL('canViewUser'));
/**
 * PUT UsersuserIdPut
 * Summary: Update user
 * Notes: Update user
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/users/{userId}', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $user = Models\User::find($request->getAttribute('userId'));
    if (!empty($user)) {
        $user->fill($args);
        unset($user->country);
        unset($user->state);
        unset($user->city);
        if (!empty($args['country']['iso2'])) {
            $user->country_id = !empty($args['country']['iso2']) ? Models\Country::findCountryIdFromIso2($args['country']['iso2']) : 0;
        }
        if (!empty($args['state']['name'])) {
            $user->state_id = !empty($args['state']['name']) ? Models\State::findOrSaveAndGetStateId($args['state']['name'], $user->country_id) : Models\State::findOrSaveAndGetStateId('null', $user->country_id);
        }   
        if (!empty($args['city']['name'])) {
            $user->city_id = !empty($args['city']['name']) ? Models\City::findOrSaveAndGetCityId($args['city']['name'], $user->country_id, $user->state_id) : Models\City::findOrSaveAndGetCityId('null', $user->country_id, $user->state_id);
        }
        $validationErrorFields = '';
        if (!empty($args['mobile']) && (checkAlreadyMobileExistsAnotherUser($args['mobile'], $user->id))) {
            $validationErrorFields['unique'] = array();
            array_push($validationErrorFields['unique'], 'mobile number');
        }
        if (isset($args['mobile_code_country_id'])) {
            $country = Models\Country::select('iso2')->find($args['mobile_code_country_id']);
            $args['mobile_code'] = $country['iso2'];
        }
        if(!empty($args['mobile']) && !empty($args['mobile_code'])){
            try{
                $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                $swissNumberProto = $phoneUtil->parse($args['mobile'], $args['mobile_code']);
                if(!$phoneUtil->isValidNumber($swissNumberProto)) {
                    $validationErrorFields['mobile'] = "Invalid mobile number and mobile code";
                } else {
                    $mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                    $user->mobile = $mobile_without_code = $swissNumberProto->getNationalNumber();
                    $user->mobile_code = str_replace($mobile_without_code, '', $mobile);
                }
            }catch(\libphonenumber\NumberParseException $e){
                $validationErrorFields['mobile'] = $e->getMessage();
            }            
        }
        if (empty($validationErrorFields)) {
            try {
                $user->save();
                $result['data'] = $user->toArray();
                if ($authUser['role_id'] == \Constants\ConstUserTypes::ADMIN) {
                    $emailFindReplace = array(
                        '##USERNAME##' => $user->username
                    );
                    sendMail('adminuseredit', $emailFindReplace, $user->email);
                }
                return renderWithJson($result);
            } catch (Exception $e) {
                return renderWithJson($result, $e->getMessage(), '', 1);
            }
        } else {
            return renderWithJson($result, 'User could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } else {
        return renderWithJson($result, 'Invalid user Details, try again.', '', 1);
    }
})->add(new Acl\ACL('canUpdateUser'));
/**
 * DELETE UseruserId Delete
 * Summary: DELETE user by admin
 * Notes: DELETE user by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/users/{userId}', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $user = Models\User::find($request->getAttribute('userId'));
    $data = $user;
    if (!empty($user)) {
        try {
            if (!empty($user['role_id']) && $user['role_id'] == \Constants\ConstUserTypes::SUPERVISOR) {
                $supervisor = Models\Supervisor::where('user_id', $user['id'])->first();
                $supervisor->delete();
            } elseif (!empty($user['role_id']) && $user['role_id'] == \Constants\ConstUserTypes::DELIVERYPERSON) {
                $delivery_person = Models\DeliveryPerson::where('user_id', $user['id'])->first();
                $delivery_person->delete();
            }
            if (!empty($user['role_id']) && $user['role_id'] != \Constants\ConstUserTypes::ADMIN) {
                $user->delete();
                $emailFindReplace = array(
                    '##USERNAME##' => $data['username']
                );
                sendMail('adminuserdelete', $emailFindReplace, $data['email']);
                $result = array(
                    'status' => 'success',
                );
            } else {
                if ($authUser->role_id == \Constants\ConstUserTypes::ADMIN && $authUser->id != $user['id']) {
                    $user->delete();
                    $emailFindReplace = array(
                        '##USERNAME##' => $data['username']
                    );
                    sendMail('adminuserdelete', $emailFindReplace, $data['email']);
                    $result = array(
                        'status' => 'success',
                    );
                } else {
                    $result = array(
                        'status' => 'Not allowed to delete Admin account.',
                    );
                }
            }
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Invalid User details.', '', 1);
    }
})->add(new Acl\ACL('canDeleteUser'));
/**
 * GET ProvidersGet
 * Summary: all providers lists
 * Notes: all providers lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/providers', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $providers = Models\Provider::Filter($queryParams)->paginate()->toArray();
        $data = $providers['data'];
        unset($providers['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $providers
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * GET  ProvidersProviderIdGet
 * Summary: Get  particular provider details
 * Notes: GEt particular provider details.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/providers/{providerId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();    
    $result = array();
    $provider = Models\Provider::Filter($queryParams)->find($request->getAttribute('providerId'));
    if (!empty($provider)) {
        $result['data'] = $provider->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * PUT ProvidersProviderIdPut
 * Summary: Update provider details
 * Notes: Update provider details.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/providers/{providerId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $provider = Models\Provider::find($request->getAttribute('providerId'));
    $validationErrorFields = $provider->validate($args);
    if (empty($validationErrorFields)) {
        $provider->fill($args);
        try {
            $provider->save();
            $result['data'] = $provider->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Provider could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateProvider'));
/**
 * GET RoleGet
 * Summary: Get roles lists
 * Notes: Get roles lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/roles', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $roles = Models\Role::Filter($queryParams)->paginate()->toArray();
        $data = $roles['data'];
        unset($roles['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $roles
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * GET RolesIdGet
 * Summary: Get paticular role
 * Notes: Get paticular email templates
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/roles/{roleId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    $role = Models\Role::Filter($queryParams)->find($request->getAttribute('roleId'));
    if (!empty($role)) {
        $result['data'] = $role->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
/**
 * GET ContactsGet
 * Summary: Get  contact lists
 * Notes: Get contact lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/contacts', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $contacts = Models\Contact::Filter($queryParams)->paginate()->toArray();
        $data = $contacts['data'];
        unset($contacts['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $contacts
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canListContact'));
/**
 * POST contactPost
 * Summary: Add contact
 * Notes: add contact
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/contacts', function ($request, $response, $args) {
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $result = array();
    $contact = new Models\Contact($args);
    $contact->ip_id = saveIp();
    $validationErrorFields = $contact->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $contact->save();
            $contact_list = Models\Contact::with('ip')->where('id', $contact->id)->first();
            $emailFindReplace = array(
                '##FIRST_NAME##' => $contact_list['first_name'],
                '##LAST_NAME##' => $contact_list['last_name'],
                '##FROM_EMAIL##' => $contact_list['email'],
                '##IP##' => $contact_list['ip']['ip'],
                '##TELEPHONE##' => $contact_list['phone'],
                '##MESSAGE##' => $contact_list['message'],
                '##SUBJECT##' => $contact_list['subject'],
                '##SITE_CONTACT_EMAIL##' => SITE_CONTACT_EMAIL,
                '##POST_DATE##' => date('F j, Y g:i:s A (l) T (\G\M\TP)') ,
                '##CONTACT_URL##' => $_server_domain_url . '/contact'
            );
            sendMail('contactus', $emailFindReplace, SITE_CONTACT_EMAIL);
            sendMail('contactusreplymail', $emailFindReplace, $contact->email);
            $result = $contact->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Contact could not be added. Please, try again.', $validationErrorFields, 1);
    }
});
/**
 * GET ContactscontactIdGet
 * Summary: Get particular contact details
 * Notes: get particular contact details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/contacts/{contactId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $contact = Models\Contact::Filter($queryParams)->find($request->getAttribute('contactId'));
    if (!empty($contact)) {
        $result['data'] = $contact->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewContact'));
/**
 * DELETE ContactsContactIdDelete
 * Summary: DELETE contact Id by admin
 * Notes: DELETE contact Id by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/contacts/{contactId}', function ($request, $response, $args) {
    $result = array();
    $contact = Models\Contact::with('ip')->find($request->getAttribute('contactId'));
    try {
        if (!empty($contact)) {
            $contact->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteContact'));
/**
 * GET PagesGet
 * Summary: Get  pages
 * Notes: Filter pages.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/pages', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $pages = Models\Page::Filter($queryParams)->paginate()->toArray();
        $data = $pages['data'];
        unset($pages['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $pages
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * POST pagePost
 * Summary: Create New page
 * Notes: Create page.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/pages', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $page = new Models\Page($args);
    $validationErrorFields = $page->validate($args);
    if (empty($validationErrorFields)) {
        $page->slug = Inflector::slug(strtolower($page->title), '-');
        try {
            $page->save();
            $result = $page->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Page could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreatePage'));
/**
 * GET PagePageIdGet.
 * Summary: Get page by it id.
 * Notes: Get page.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/pages/{pageId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $page = Models\Page::Filter($queryParams)->find($request->getAttribute('pageId'));
    if (!empty($page)) {
        $result['data'] = $page->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'Page not found', '', 1);
    }
});
/**
 * PUT PagepageIdPut
 * Summary: Update page by admin
 * Notes: Update page by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/pages/{pageId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $page = Models\Page::find($request->getAttribute('pageId'));
    $validationErrorFields = $page->validate($args);
    if (empty($validationErrorFields)) {
        $page->fill($args);
        $page->slug = Inflector::slug(strtolower($page->title), '-');
        try {
            $page->save();
            $result['data'] = $page->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Page could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdatePage'));
/**
 * DELETE PagepageIdDelete
 * Summary: DELETE page by admin
 * Notes: DELETE page by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/pages/{pageId}', function ($request, $response, $args) {
    $result = array();
    $page = Models\Page::find($request->getAttribute('pageId'));
    try {
        if (!empty($page)) {
            $page->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeletePage'));
/**
 * GET SettingcategoriesGet
 * Summary: Get  Setting categories
 * Notes: Filter Setting categories.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/setting_categories', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $settingCategories = Models\SettingCategory::Filter($queryParams)->paginate()->toArray();
        $data = $settingCategories['data'];
        unset($settingCategories['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $settingCategories
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canListSettingCategory'));
/**
 * GET SettingcategoriesSettingCategoryIdGet
 * Summary: Get setting category.
 * Notes: Get setting categories.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/setting_categories/{settingCategoryId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $settingCategory = Models\SettingCategory::Filter($queryParams)->find($request->getAttribute('settingCategoryId'));
    if (!empty($settingCategory)) {
        $result['data'] = $settingCategory->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canListSettingCategory'));
/**
 * GET SettingGet .
 * Summary: Get settings.
 * Notes: GEt settings.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/settings', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
        $settings = Models\Setting::Filter($queryParams)->paginate()->toArray();
        $data = $settings['data'];
        unset($settings['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $settings
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * GET settingssettingIdGet
 * Summary: GET particular Setting.
 * Notes: Get setting.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/settings/{settingId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $setting = Models\Setting::Filter($queryParams)->find($request->getAttribute('settingId'));
    if (!empty($setting)) {
        $result['data'] = $setting->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewSetting'));
/**
 * PUT SettingsSettingIdPut
 * Summary: Update setting by admin
 * Notes: Update setting by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/settings/{settingId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $setting = Models\Setting::find($request->getAttribute('settingId'));
    $setting->fill($args);
    try {
        $setting->save();
        $result['data'] = $setting->toArray();
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canUpdateSetting'));
/**
 * GET EmailTemplateGet
 * Summary: Get email templates lists
 * Notes: Get email templates lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/email_templates', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
        $emailTemplates = Models\EmailTemplate::Filter($queryParams)->paginate()->toArray();
        $data = $emailTemplates['data'];
        unset($emailTemplates['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $emailTemplates
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canListEmailTemplate'));
/**
 * GET EmailTemplateemailTemplateIdGet
 * Summary: Get paticular email templates
 * Notes: Get paticular email templates
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/email_templates/{emailTemplateId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $emailTemplate = Models\EmailTemplate::Filter($queryParams)->find($request->getAttribute('emailTemplateId'));
    if (!empty($emailTemplate)) {
        $result['data'] = $emailTemplate->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewEmailTemplate'));
/**
 * PUT EmailTemplateemailTemplateIdPut
 * Summary: Update paticular email template
 * Notes: Put paticular email templates
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/email_templates/{emailTemplateId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $emailTemplate = Models\EmailTemplate::find($request->getAttribute('emailTemplateId'));
    if (!empty($args)) {
        $emailTemplate->fill($args);
        try {
            $emailTemplate->save();
            $result['data'] = $emailTemplate->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Email template could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateEmailTemplate'));
/**
 * GET CitiesGet
 * Summary: Get  cities
 * Notes: Filter cities.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/cities', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $cities = Models\City::Filter($queryParams)->paginate()->toArray();
        $data = $cities['data'];
        unset($cities['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $cities
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * POST citiesPost
 * Summary: Create new city
 * Notes: create new city
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/cities', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $city = new Models\City($args);
    $validationErrorFields = $city->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $city->save();
            $result = $city->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'city could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateCity'));
/**
 * GET CitiesGet
 * Summary: Get  particular city
 * Notes: Get  particular city
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/cities/{cityId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $city = Models\City::Filter($queryParams)->find($request->getAttribute('cityId'));
    if (!empty($city)) {
        $result['data'] = $city->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewCity'));
/**
 * PUT CitiesCityIdPut
 * Summary: Update city by admin
 * Notes: Update city by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/cities/{cityId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $city = Models\City::find($request->getAttribute('cityId'));
    $validationErrorFields = $city->validate($args);
    if (empty($validationErrorFields)) {
        $city->fill($args);
        try {
            $city->save();
            $result['data'] = $city->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'City could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateCity'));
/**
 * DELETE CitiesCityIdDelete
 * Summary: DELETE city by admin
 * Notes: DELETE city by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/cities/{cityId}', function ($request, $response, $args) {
    $result = array();
    $city = Models\City::find($request->getAttribute('cityId'));
    try {
        $city->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteCity'));
/**
 * GET StatesGet
 * Summary: Get  states
 * Notes: Filter states.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/states', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $states = Models\State::Filter($queryParams)->paginate()->toArray();
        $data = $states['data'];
        unset($states['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $states
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * POST StatesPost
 * Summary: Create New states
 * Notes: Create states.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/states', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $state = new Models\State($args);
    $validationErrorFields = $state->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $state->save();
            $result = $state->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'State could not be added. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateState'));
/**
 * GET StatesstateIdGet
 * Summary: Get  particular state
 * Notes: Get  particular state
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/states/{stateId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $state = Models\State::Filter($queryParams)->find($request->getAttribute('stateId'));
    if (!empty($state)) {
        $result['data'] = $state->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewState'));
/**
 * PUT StatesStateIdPut
 * Summary: Update states by admin
 * Notes: Update states.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/states/{stateId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $state = Models\State::find($request->getAttribute('stateId'));
    $validationErrorFields = $state->validate($args);
    if (empty($validationErrorFields)) {
        $state->fill($args);
        try {
            $state->save();
            $result['data'] = $state->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'State could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateState'));
/**
 * DELETE StatesStateIdDelete
 * Summary: DELETE states by admin
 * Notes: DELETE states by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/states/{stateId}', function ($request, $response, $args) {
    $result = array();
    $state = Models\State::find($request->getAttribute('stateId'));
    try {
        if (!empty($state)) {
            $state->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteState'));
/**
 * GET countriesGet
 * Summary: Get  countries
 * Notes: Filter countries.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/countries', function ($request, $response, $args) use ($app) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $countries = Models\Country::Filter($queryParams)->paginate()->toArray();
        $data = $countries['data'];
        unset($countries['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $countries
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * POST countriesPost
 * Summary: Create New countries
 * Notes: Create countries.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/countries', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $country = new Models\Country($args);
    $validationErrorFields = $country->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $country->save();
            $result = $country->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Country could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateCountry'));
/**
 * GET countriescountryIdGet
 * Summary: Get country
 * Notes: Get countries.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/countries/{countryId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $country = Models\Country::Filter($queryParams)->find($request->getAttribute('countryId'));
    if (!empty($country)) {
        $result['data'] = $country->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewCountry'));
/**
 * PUT countriesCountryIdPut
 * Summary: Update countries by admin
 * Notes: Update countries.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/countries/{countryId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $country = Models\Country::find($request->getAttribute('countryId'));
    $validationErrorFields = $country->validate($args);
    if (empty($validationErrorFields)) {
        $country->fill($args);
        try {
            $country->save();
            $result['data'] = $country->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Country could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateCountry'));
/**
 * DELETE countrycountryIdDelete
 * Summary: DELETE country by admin
 * Notes: DELETE country.
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/countries/{countryId}', function ($request, $response, $args) {
    $result = array();
    $country = Models\Country::find($request->getAttribute('countryId'));
    try {
        if (!empty($country)) {
            $country->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Country could not be deleted. Please, try again.', '', 1);
    }
})->add(new Acl\ACL('canDeleteCountry'));
/**
 * GET LanguageGet
 * Summary: Get  languages
 * Notes: Filter language.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/languages', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $languages = Models\Language::Filter($queryParams)->paginate()->toArray();
        $data = $languages['data'];
        unset($languages['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $languages
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * POST LanguagePost
 * Summary: Add language
 * Notes: add language
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/languages', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $language = new Models\Language($args);
    $validationErrorFields = $language->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $language->save();
            $result = $language->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Language could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateLanguage'));
/**
 * GET LanguagelanguageIdGet
 * Summary: Get particular language
 * Notes: Get particular language.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/languages/{languageId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams(); 
    $result = array();
    $language = Models\Language::Filter($queryParams)->find($request->getAttribute('languageId'));
    if (!empty($language)) {
        $result['data'] = $language->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'Language not found', '', 1);
    }
})->add(new Acl\ACL('canViewLanguage'));
/**
 * PUT LanguagelanguageIdPut
 * Summary: Update language by admin
 * Notes: Update language by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/languages/{languageId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $language = Models\Language::find($request->getAttribute('languageId'));
    $validationErrorFields = $language->validate($args);
    if (empty($validationErrorFields)) {
        $language->fill($args);
        try {
            $language->save();
            $result['data'] = $language->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Language could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateLanguage'));
/**
 * DELETE LanguageLanguageIdDelete
 * Summary: DELETE language by its id
 * Notes: DELETE language.
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/languages/{languageId}', function ($request, $response, $args) {
    $result = array();
    $language = Models\Language::find($request->getAttribute('languageId'));
    try {
        if (!empty($language)) {
            $language->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Language not found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteLanguage'));
/**
 * GET StatsGet
 * Summary: Get site stats lists
 * Notes: Get site stats lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/stats', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $excludedStatuses = array(
        \Constants\OrderStatus::PAYMENTPENDING,
        \Constants\OrderStatus::PAYMENTFAILED
    );
    //Customers
    $result['customers'] = Models\User::where('is_active', 1)->where('is_email_confirmed', 1)->count();
    if (!empty($authUser) && $authUser['role_id'] == \Constants\ConstUserTypes::RESTAURANT) {
        //Get restaurant details
        $restaurant = Models\Restaurant::select('id')->where('user_id', $authUser['id'])->first();
        //Monthly revenue
        $result['montly_revenue'] = Models\Order::whereYear('updated_at', '=', date('Y'))->whereMonth('updated_at', '=', date('m'))->whereIn('order_status_id', array(
            \Constants\OrderStatus::DELIVERED,
            \Constants\OrderStatus::REVIEWED
        ))->whereNotIn('order_status_id', $excludedStatuses)->where('restaurant_id', $restaurant['id'])->sum('total_price');
        //Total Orders count
        $result['total_orders'] = Models\Order::where('restaurant_id', $restaurant['id'])->whereNotIn('order_status_id', $excludedStatuses)->count();
        //Porcessing Orders count
        $result['processing_orders'] = Models\Order::where('order_status_id', \Constants\OrderStatus::PROCESSING)->where('restaurant_id', $restaurant['id'])->count();
        //Pending Orders count
        $result['pending_orders'] = Models\Order::where('order_status_id', \Constants\OrderStatus::PENDING)->where('restaurant_id', $restaurant['id'])->count();
        //Total revenue
        $result['total_revenue'] = Models\Transaction::where('other_user_id', $authUser['id'])->sum('amount');
        //Total transaction
        $result['total_transaction'] = Models\Transaction::where('other_user_id', $authUser['id'])->count();

        //Daily Transaction
        $result['daily_transaction'] = Models\Transaction::where('other_user_id', $authUser['id'])->where('created_at', '>=',  date('Y-m-d'))->count();
        $result['daily_transaction_amont'] = Models\Transaction::where('other_user_id', $authUser['id'])->where('created_at', '>=',  date('Y-m-d'))->sum('amount');
       
        //monthly transction
        $result['monthly_transaction'] = Models\Transaction::where('other_user_id', $authUser['id'])->where('created_at', '>=', date('Y-m-d',strtotime('-1 month', strtotime(date('Y-m-d')))))->count();
        $result['monthly_transaction_amount'] = Models\Transaction::where('other_user_id', $authUser['id'])->where('created_at', '>=', date('Y-m-d',strtotime('-1 month', strtotime(date('Y-m-d')))))->sum('amount');

        //weekly transaction
        $result['weekly_transaction'] = Models\Transaction::where('other_user_id', $authUser['id'])->where('created_at', '>=', date('Y-m-d',strtotime('-1 week', strtotime(date('Y-m-d')))))->count();
        $result['weekly_transaction_amount'] = Models\Transaction::where('other_user_id', $authUser['id'])->where('created_at', '>=', date('Y-m-d',strtotime('-1 week', strtotime(date('Y-m-d')))))->sum('amount');
        
        // yearly transaction
        $result['yearly_transaction'] = Models\Transaction::where('other_user_id', $authUser['id'])->where('created_at', '>=', date('Y-m-d',strtotime('-1 year', strtotime(date('Y-m-d')))))->count();
        $result['yearly_transaction_amount'] = Models\Transaction::where('other_user_id', $authUser['id'])->where('created_at', '>=', date('Y-m-d',strtotime('-1 year', strtotime(date('Y-m-d')))))->sum('amount');

        //Pending review count
        $result['pending_reviews'] = Models\Order::where('order_status_id', \Constants\OrderStatus::DELIVERED)->where('restaurant_id', $restaurant['id'])->count();
    } elseif (!empty($authUser) && ($authUser['role_id'] == \Constants\ConstUserTypes::SUPERVISOR)) {
        //Get supervisor details
        $supervisor = Models\Supervisor::select('id')->where('user_id', $authUser['id'])->first();
        //Get deliver person details
        $delivery_person = Models\DeliveryPerson::select('id', 'restaurant_id')->where('restaurant_supervisor_id', $supervisor['id'])->get()->toArray();
        $result['pending_orders'] = $result['processing_orders'] = $result['delivered_order'] = 0;
        foreach ($delivery_person as $key => $value) {
            // Pending Orders count
            $result['pending_orders'] = $result['pending_orders'] + Models\Order::where('order_status_id', \Constants\OrderStatus::PENDING)->where('restaurant_id', $value['restaurant_id'])->where('restaurant_delivery_person_id', $value['id'])->count();
            // Processing Orders count
            $result['processing_orders'] = $result['processing_orders'] + Models\Order::where('order_status_id', \Constants\OrderStatus::PROCESSING)->where('restaurant_id', $value['restaurant_id'])->where('restaurant_delivery_person_id', $value['id'])->count();
            //Delivered order count
            $result['delivered_order'] = $result['delivered_order'] + Models\Order::where('order_status_id', \Constants\OrderStatus::DELIVERED)->where('restaurant_id', $value['restaurant_id'])->where('restaurant_delivery_person_id', $value['id'])->count();
        }
    } elseif (!empty($authUser) && ($authUser['role_id'] == \Constants\ConstUserTypes::DELIVERYPERSON)) {
        //Get restaurant details
        $restaurant = Models\DeliveryPerson::select('id', 'restaurant_id')->where('user_id', $authUser['id'])->first();
        //Pending Orders count
        $result['pending_orders'] = Models\Order::where('order_status_id', \Constants\OrderStatus::PROCESSING)->where('restaurant_id', $restaurant['restaurant_id'])->where('restaurant_delivery_person_id', $restaurant['id'])->count();
        //Delivered order count
        $result['delivered_order'] = Models\Order::where('order_status_id', \Constants\OrderStatus::DELIVERED)->where('restaurant_id', $restaurant['restaurant_id'])->where('restaurant_delivery_person_id', $restaurant['id'])->count();
    } else {
        //Get restaurant details
        $restaurant = Models\Restaurant::select('id')->where('user_id', $authUser['id'])->first();
        //Monthly revenue
        $result['montly_revenue'] = Models\Order::whereYear('updated_at', '=', date('Y'))->whereMonth('updated_at', '=', date('m'))->whereIn('order_status_id', array(
            \Constants\OrderStatus::DELIVERED,
            \Constants\OrderStatus::REVIEWED
        ))->whereNotIn('order_status_id', $excludedStatuses)->sum('total_price');
        //Total Orders count
        $result['total_orders'] = Models\Order::whereNotIn('order_status_id', $excludedStatuses)->count();
        //Total Restaurant count
        $result['total_restaurant'] = Models\Restaurant::count();
        //Total Transaction count
        $result['total_transaction'] = Models\Transaction::count();
       
        //Daily Transaction
        $result['daily_transaction'] = Models\Transaction::where('created_at', '>=',  date('Y-m-d'))->count();
        $result['daily_transaction_amont'] = Models\Transaction::where('created_at', '>=',  date('Y-m-d'))->sum('amount');
       
        //monthly transction
        $result['monthly_transaction'] = Models\Transaction::where('created_at', '>=', date('Y-m-d',strtotime('-1 month', strtotime(date('Y-m-d')))))->count();
        $result['monthly_transaction_amount'] = Models\Transaction::where('created_at', '>=', date('Y-m-d',strtotime('-1 month', strtotime(date('Y-m-d')))))->sum('amount');

        //weekly transaction
        $result['weekly_transaction'] = Models\Transaction::where('created_at', '>=', date('Y-m-d',strtotime('-1 week', strtotime(date('Y-m-d')))))->count();
        $result['weekly_transaction_amount'] = Models\Transaction::where('created_at', '>=', date('Y-m-d',strtotime('-1 week', strtotime(date('Y-m-d')))))->sum('amount');
        
        // yearly transaction
        $result['yearly_transaction'] = Models\Transaction::where('created_at', '>=', date('Y-m-d',strtotime('-1 year', strtotime(date('Y-m-d')))))->count();
        $result['yearly_transaction_amount'] = Models\Transaction::where('created_at', '>=', date('Y-m-d',strtotime('-1 year', strtotime(date('Y-m-d')))))->sum('amount');
        
        //Total revenue
        $result['total_revenue'] = Models\Order::sum('site_fee');
    }
    return renderWithJson($result);
})->add(new Acl\ACL('canViewStats'));
/**
 * POST apiV1AttachmentsPost
 * Summary: Create new Attachment
 * Notes: Creates a new Attachment
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/attachments', function ($request, $response, $args) {
    $args = $request->getQueryParams();
    $file = $request->getUploadedFiles();
    $newfile = $file['file'];
    $size = $newfile->getsize();
    $type = pathinfo($newfile->getClientFilename(), PATHINFO_EXTENSION);
    $alloted_types = array(
        'gif',
        'jpg',
        'jpeg',
        'png',
        'swf',
        'psd',
        'wbmp'
    );
    $type = pathinfo($newfile->getClientFilename(), PATHINFO_EXTENSION);
    $name = md5(time()).rand();
    $max_size = MAX_UPLOAD_SIZE * 1000;
    if (($size <= $max_size) && (in_array($type, $alloted_types))) {
        if (!file_exists(APP_PATH . '/media/tmp/')) {
            mkdir(APP_PATH . '/media/tmp/');
        }
        if (move_uploaded_file($newfile->file, APP_PATH . '/media/tmp/' . $name . '.' . $type) === true) {
            $filename = $name . '.' . $type;
            $response = array(
                'id' => $filename,
                'error' => array(
                    'code' => 0,
                    'message' => ''
                )
            );
        } else {
            $response = array(
                'error' => array(
                    'code' => 1,
                    'message' => 'Photos could not be added.',
                    'fields' => ''
                )
            );
        }
    } else {
        $response = array(
            'error' => array(
                'code' => 1,
                'message' => 'Photos could not be added. Image size should be less than 8Mb & Upload valid image.',
                'fields' => ''
            )
        );
    }
    return renderWithJson($response);
})->add(new Acl\ACL('canCreateAttachment'));
/**
 * DELETE AttachmentAttachmentIdDelete
 * Summary: DELETE Attachment
 * Notes: DELETE Attachment.
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/attachments/{attachmentId}', function ($request, $response, $args) {
    $result = array();
    $attachment = Models\Attachment::find($request->getAttribute('attachmentId'));
    try {
        if (!empty($attachment)) {
            $attachment->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteAttachment'));

$app->GET('/api/v1/admin-config', function ($request, $response, $args) {
    global $authUser;
    $plugins = getEnabledPlugin();
    $compiledMenus = $compiledTables = $mainJson = '';
    $file = __DIR__ . '/admin-config.php';
    $list_mode = true;
    $create_mode = true;
    $edit_mode = true;
    $delete_mode = true;
    $show_mode = true;
    $resultSet = array();
    if (file_exists($file)) {
        require_once $file;
        if (!empty($menus)) {
            $resultSet['menus'] = $menus;
        }
        if (!empty($dashboard)) {
            if (!empty($resultSet['dashboard'])) {
                $resultSet['dashboard'] = array_merge($resultSet['dashboard'], $dashboard);
            } else {
                $resultSet['dashboard'] = $dashboard;
            }
        }
        if (!empty($tables)) {
            $resultSet['tables'] = $tables;
            $tableName = current(array_keys($resultSet['tables']));
            if ($list_mode === false) {
                unset($resultSet['tables'][$tableName]['listview']);
            } else {
                if ($create_mode === false) {
                    unset($resultSet['tables'][$tableName]['listview']['actions'][2]);
                }
                if ($edit_mode === false) {
                    unset($resultSet['tables'][$tableName]['listview']['listActions'][0]);
                }
                if ($show_mode === false) {
                    unset($resultSet['tables'][$tableName]['listview']['actions'][1]);
                }
                if ($delete_mode === false) {
                    unset($resultSet['tables'][$tableName]['listview']['actions'][2]);
                }
            }
            if ($create_mode === false) {
                unset($resultSet['tables'][$tableName]['creationview']);
            }
            if ($edit_mode === false) {
                unset($resultSet['tables'][$tableName]['editionview']);
            }
            if ($delete_mode === false) {
                unset($resultSet['tables'][$tableName]['showview']);
            }
        }
    }
    if (!empty($plugins)) {
        foreach ($plugins as $plugin) {
            $file = __DIR__ . '/../plugins/' . $plugin . '/admin-config.php';
            if (file_exists($file)) {
                require_once $file;
                if (!empty($resultSet['menus'])) {
                    foreach ($menus as $key => $menu) {
                        if (isset($resultSet['menus'][$key])) {
                            $resultSet['menus'][$key]['child_sub_menu'] = array_merge($resultSet['menus'][$key]['child_sub_menu'], $menu['child_sub_menu']);
                        } else {
                            $resultSet['menus'][$key] = $menu;
                        }
                    }
                } elseif (!empty($menus)) {
                    $resultSet['menus'] = $menus;
                }
                if (!empty($dashboard)) {
                    if (!empty($resultSet['dashboard'])) {
                        $resultSet['dashboard'] = array_merge($resultSet['dashboard'], $dashboard);
                    } else {
                        $resultSet['dashboard'] = $dashboard;
                    }
                }
                if (!empty($tables)) {
                    $tableName = current(array_keys($tables));
                    if ($list_mode === false) {
                        unset($tables[$tableName]['listview']);
                    } else {
                        if ($create_mode === false) {
                            unset($tables[$tableName]['listview']['actions'][2]);
                        }
                        if ($edit_mode === false) {
                            unset($tables[$tableName]['listview']['listActions'][0]);
                        }
                        if ($show_mode === false) {
                            unset($tables[$tableName]['listview']['actions'][1]);
                        }
                        if ($delete_mode === false) {
                            unset($tables[$tableName]['listview']['actions'][2]);
                        }
                    }
                    if ($create_mode === false) {
                        unset($tables['tables'][$tableName]['creationview']);
                    }
                    if ($edit_mode === false) {
                        unset($tables[$tableName]['editionview']);
                    }
                    if ($delete_mode === false) {
                        unset($tables[$tableName]['showview']);
                    }
                    if (!empty($resultSet['tables'])) {
                        $resultSet['tables'] = array_merge($resultSet['tables'], $tables);
                    } else {
                        $resultSet['tables'] = $tables;
                    }
                }
            }
        }
        usort($resultSet['menus'], function ($a, $b) {
            return $a['order'] - $b['order'];
        });
        foreach ($resultSet['menus'] as $key => $value) {
            $resultSet['menus'][$key]['child_sub_menu'] = menu_sub_array_sorting($resultSet['menus'][$key]['child_sub_menu'], 'suborder', SORT_ASC);
        }
        foreach ($resultSet['tables'] as $key => $table) {
            if($key == 'user_cash_withdrawals') {
                foreach ($table as $view_key => $view) {
                    $fields = menu_sub_array_sorting($resultSet['tables'][$key][$view_key]['fields'], 'suborder', SORT_ASC); 
                    if(count($fields) > 0) {
                        foreach ($fields as $field) {
                            $field_list[] = $field;
                        }
                        $resultSet['tables'][$key][$view_key]['fields'] = $field_list;
                        $field_list = array();
                    }
                }
            }
        }
    }
    echo json_encode($resultSet);
    exit;
});
/**
 * DELETE deviceDetailsDeviceDetailIdDelete
 * Summary: Delete device detail
 * Notes: Deletes a single device detail based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/device_details/{deviceDetailId}', function($request, $response, $args) {
	$deviceDetail = Models\DeviceDetail::find($request->getAttribute('deviceDetailId'));
	$result = array();
	try {
		if (!empty($deviceDetail)) {
			$deviceDetail->delete();
			$result = array(
				'status' => 'success',
			);
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'No record found', '', 1);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'Device detail could not be deleted. Please, try again.', '', 1);
	}
})->add(new Acl\ACL('canDeleteDeviceDetail'));

/**
 * GET deviceDetailsDeviceDetailIdGet
 * Summary: Fetch device detail
 * Notes: Returns a device detail based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/device_details/{deviceDetailId}', function($request, $response, $args) {
	global $authUser;
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $deviceDetail = Models\DeviceDetail::Filter($queryParams)->find($request->getAttribute('deviceDetailId'));
        if (!empty($deviceDetail)) {
            $result['data'] = $deviceDetail;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Device detail not found', '', 1, 404);
        }
    } catch(Exception $e) {
		return renderWithJson($result, 'Device detail not found. Please, try again.', '', 1, 422);
	}
})->add(new Acl\ACL('canViewDeviceDetail'));

/**
 * GET deviceDetailsGet
 * Summary: Fetch all device details
 * Notes: Returns all device details from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/device_details', function($request, $response, $args) {
    global $authUser;
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$deviceDetails = Models\DeviceDetail::Filter($queryParams)->paginate()->toArray();
		$data = $deviceDetails['data'];
		unset($deviceDetails['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $deviceDetails
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, 'No record found', '', 1);
	}
})->add(new Acl\ACL('canListDeviceDetail'));

/**
 * POST deviceDetailsPost
 * Summary: Creates a new device detail
 * Notes: Creates a new device detail
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/device_details', function($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    if (!empty($args['devicetoken'])) {
        Models\DeviceDetail::where('devicetoken', $args['devicetoken'])->where('user_id', '!=', $authUser->id)->delete();
    }
    $device_details = Models\DeviceDetail::where('user_id', $authUser->id)->first();
    $DeviceDetail = new Models\DeviceDetail($args);
    if (!empty($device_details)) {
        $DeviceDetail = Models\DeviceDetail::find($device_details['id']);
        $DeviceDetail->fill($args);
    }
    $DeviceDetail->user_id = $authUser->id;
    try {
        $validationErrorFields = $DeviceDetail->validate($args);
        if (empty($validationErrorFields)) {
            $DeviceDetail->save();
            $user = Models\User::find($authUser->id);
            $user->longitude = $DeviceDetail->longitude;
            $user->latitude = $DeviceDetail->latitude;
            $user->save();
            $DeviceDetail = Models\DeviceDetail::with('user')->where('id', $DeviceDetail->id)->first();
            $result['data'] = $DeviceDetail->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Device detail could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Device detail could not be added. Please, try again', $e->getMessage(), 1);
    }
})->add(new Acl\ACL('canCreateDeviceDetail'));

/**
 * GET pluginsGet
 * Summary: Fetch all plugins
 * Notes: Returns all plugins from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/plugins', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $path = SCRIPT_PATH . DIRECTORY_SEPARATOR . 'plugins';
    $directories = array();
    $directories = glob($path . '/*', GLOB_ONLYDIR);
    $available_plugin = array();
    $available_plugin_details = array();
    $pluginArray = array();
    $pluginArray['Order'] = array();
    $pluginArray['Common'] = array();
    $pluginArray['Restaurant'] = array();
    $orderRelatedPlugins = array();
    $restaurantRelatedPlugins = array();
    $plugin_name = array();
    $otherlugins = array();
    $hide_plugins = array();
    foreach ($directories as $key => $val) {
        $name = explode('/', $val);
        $sub_directories = glob($val . '/*', GLOB_ONLYDIR);
        if (!empty($sub_directories)) {
            foreach ($sub_directories as $sub_directory) {
                $json = file_get_contents($sub_directory . DIRECTORY_SEPARATOR . 'plugin.json');
                $data = json_decode($json, true);
                if (!in_array($data['name'], $hide_plugins)) {
                    if (!empty($data['dependencies'])) {
                        $pluginArray[$data['dependencies']][$data['name']] = $data;
                    } elseif (!in_array($data['name'], $pluginArray)) {
                        if (empty($pluginArray[$data['name']])) {
                            $pluginArray[] = $data;
                        }
                    }
                }
            }
        }
    }
    if (empty($pluginArray['Order'])) {
        unset($pluginArray['Order']);
    } else {
        $orderPlugins = $pluginArray['Order'];
        unset($pluginArray['Order']);
        foreach ($orderPlugins as $orderPlugin) {
            if ($orderPlugin['name'] != 'Order') {
                $orderRelatedPlugins['sub_plugins'][] = $orderPlugin;
            } else {
                $orderRelatedPlugins['main_plugins'][] = $orderPlugin;
            }
        }
    }
    if (empty($pluginArray['Restaurant'])) {
        unset($pluginArray['Restaurant']);
    } else {
        $restaurantPlugins = $pluginArray['Restaurant'];
        unset($pluginArray['Restaurant']);
        foreach ($restaurantPlugins as $restaurantPlugin) {
            if ($restaurantPlugin['name'] != 'MultiRestaurant') {
                $restaurantRelatedPlugins['sub_plugins'][] = $restaurantPlugin;
            } else {
                $restaurantRelatedPlugins['main_plugins'][] = $restaurantPlugin;
            }
        }
    }
    foreach ($pluginArray as $plugin) {
        $otherlugins[] = $plugin;
    }
    $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
    foreach ($enabled_plugins as $key => $enabled_plugin) {
        $name = explode('/', $enabled_plugin);
        $plugin_name[] = end($name);
    }
    $enabled_plugin = array_map('trim', $plugin_name);
    $result['data']['order_plugin'] = $orderRelatedPlugins;
    $result['data']['restaurant_plugin'] = $restaurantRelatedPlugins;
    $result['data']['other_plugin'] = $otherlugins;
    $result['data']['enabled_plugin'] = $enabled_plugin;
    return renderWithJson($result);
})->add(new Acl\ACL('canListPlugins'));
/**
 * PUT pluginPut
 * Summary: Update plugins ny plugin name
 * Notes: Update plugins ny plugin name
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/plugins', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $enabled_plugins = explode(',', trim(SITE_ENABLED_PLUGINS,','));
    if ($args['is_enabled'] === 1) {
        if (!in_array($args['plugin'], $enabled_plugins)) {
            // TODO (quick fix for routing problem)
            if ($args['plugin'] == 'Common/ZazPay') {
                $enabled_plugins = array_merge(array ($args['plugin']), $enabled_plugins);
            } else {
                $enabled_plugins[] = $args['plugin'];
            }
        }
        if ($args['plugin'] == 'Restaurant/MultiRestaurant') {
            $key = array_search('Restaurant/SingleRestaurant', $enabled_plugins);
            if ($key !== false) {
                unset($enabled_plugins[$key]);
            }
        } else if ($args['plugin'] == 'Restaurant/SingleRestaurant') {
            $key = array_search('Restaurant/MultiRestaurant', $enabled_plugins);
            if ($key !== false) {
                unset($enabled_plugins[$key]);
            }
        }
        $pluginStr = trim(implode(',', $enabled_plugins),',');
        Models\Setting::where('name', 'SITE_ENABLED_PLUGINS')->update(array(
            'value' => $pluginStr
        ));
        return renderWithJson($result, 'Plugin enabled', '', 0);
    } elseif ($args['is_enabled'] === 0) {
        $key = array_search($args['plugin'], $enabled_plugins);
        if ($key !== false) {
            unset($enabled_plugins[$key]);
        }
        $main_pugins = array('Order/Order');
        if (in_array($args['plugin'], $main_pugins)) {
            $main_folder = explode("/",$args['plugin']);
            $path = SCRIPT_PATH . DIRECTORY_SEPARATOR . 'plugins'. DIRECTORY_SEPARATOR . $main_folder[0];
            
            $pluginArray = $hide_plugins = array();
            $sub_plugins = '';
            $directories = glob($path . '/*', GLOB_ONLYDIR);    
            foreach ($directories as $sub_directory) {
                $json = file_get_contents($sub_directory . DIRECTORY_SEPARATOR . 'plugin.json');
                $data = json_decode($json, true);
                $sub_plugins[] = $data['plugin_name'];
            }   
            $enabled_plugins = array_diff($enabled_plugins, $sub_plugins); 
        }
        if ($args['plugin'] == 'Restaurant/MultiRestaurant') {
            array_push($enabled_plugins, 'Restaurant/SingleRestaurant');
        } else if ($args['plugin'] == 'Restaurant/SingleRestaurant') {
            array_push($enabled_plugins, 'Restaurant/MultiRestaurant');
        }
        $pluginStr = trim(implode(',', $enabled_plugins),',');
        Models\Setting::where('name', 'SITE_ENABLED_PLUGINS')->update(array(
            'value' => $pluginStr
        ));
        $scripts_path = SCRIPT_PATH;
        
        $lists = glob($scripts_path . '/plugins*.js');
        if ($lists) {
            foreach ($lists as $list) {
                @unlink($list);
            }
        }
        return renderWithJson($result, 'Plugin disabled', '', 0);
    } else {
        return renderWithJson($result, 'Invalid request.', '', 1);
    }
})->add(new Acl\ACL('canUpdatePlugin'));
$app->run();