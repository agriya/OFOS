<?php
/**
 * To fetch access token, user Google profile details, fetch user's contacts and user's interests
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
 * Google Provider API Class
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class Providers_google
{
    /**
     * To get access token from Google
     *
     * @param $pass_value
     * @return string
     */
    function getAccessToken($pass_value)
    {
        global $_server_domain_url;
        $params = array(
            'code' => $pass_value['code'],
            'client_id' => $pass_value['api_key'],
            'redirect_uri' => $pass_value['redirectUri'],
            'grant_type' => 'authorization_code',
            'client_secret' => $pass_value['secret_key']
        );
        $accessTokenUrl = 'https://accounts.google.com/o/oauth2/token';
        $_response = _doPost($accessTokenUrl, $params, 'plain');
        $access_token = $_response['access_token'];
        return $access_token;
    }
    /**
     * To fetch user profile basic details from Google
     *
     * @param $access_token
     * @param $provider_details
     * @return object
     */
    function getUserProfile($access_token, $provider_details = '')
    {
        // ask google api for user infos
        $graphApiUrl = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $access_token;
        $_response = _doGet($graphApiUrl);
        if (!isset($_response['id']) || isset($_response['error'])) {
            throw new Exception("User profile request failed! google returned an invalid response.", 6);
        }
        $user = (object)array();
        $user->access_token = $user->access_token_secret = '';
        $user->access_token = $access_token;
        $user->identifier = (array_key_exists('id', $_response)) ? $_response['id'] : "";
        $user->firstName = (array_key_exists('given_name', $_response)) ? $_response['given_name'] : "";
        $user->lastName = (array_key_exists('family_name', $_response)) ? $_response['family_name'] : "";
        $user->displayName = (array_key_exists('name', $_response)) ? $_response['name'] : "";
        $user->photoURL = (array_key_exists('picture', $_response)) ? $_response['picture'] : "";
        $user->profileURL = "https://plus.google.com/" . $user->identifier;
        $user->gender = (array_key_exists('gender', $_response)) ? $_response['gender'] : "";
        $user->email = (array_key_exists('email', $_response)) ? $_response['email'] : "";
        $user->emailVerified = (array_key_exists('email', $_response)) ? $_response['email'] : "";
        $user->language = (array_key_exists('locale', $_response)) ? $_response['locale'] : "";
        if (array_key_exists('birthday', $_response)) {
            list($birthday_year, $birthday_month, $birthday_day) = explode('-', $_response['birthday']);
            $user->birthDay = (int)$birthday_day;
            $user->birthMonth = (int)$birthday_month;
            $user->birthYear = (int)$birthday_year;
        }
        return $user;
    }
    /**
     * To fetch user contacts from Google
     *
     * @param $access_token
     * @param $provider_details
     * @return object
     */
    function getUserContacts($access_token, $provider_details = '')
    {
        $url = "https://www.google.com/m8/feeds/contacts/default/full?access_token=" . $access_token . "&alt=json&max-results=500";
        $_response = _doGet($url);
        if (!$_response) {
            return ARRAY();
        }
        $contacts = ARRAY();
        foreach ($_response['feed']['entry'] as $idx => $entry) {
            $uc = (object)array();
            $uc->email = isset($entry['gd$email'][0]['address']) ? (string)$entry['gd$email'][0]['address'] : '';
            $uc->displayName = isset($entry['title']['$t']) ? (string)$entry['title']['$t'] : '';
            $uc->identifier = $uc->email;
            $contacts[] = $uc;
        }
        return $contacts;
    }
}
?>