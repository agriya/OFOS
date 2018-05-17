<?php
/**
 * To fetch access token, user Vimeo profile details, fetch user's friends and user's interests
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
 * Vimeo Provider API Class
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class Providers_vimeo
{
    /**
     * To get access token from Vimeo
     *
     * @param $pass_value
     * @return string
     */
    function getAccessToken($pass_value)
    {
        $params = array(
            'code' => $pass_value['code'],
            'client_id' => $pass_value['clientId'],
            'redirect_uri' => $pass_value['redirectUri'],
            'grant_type' => 'authorization_code',
            'client_secret' => $pass_value['secret_key']
        );
        $accessTokenUrl = 'https://api.vimeo.com/oauth/access_token';
        $_response = _doPost($accessTokenUrl, $params, 'plain');
        $access_token = $_response['access_token'];
        return $access_token;
    }
    /**
     * To fetch user profile basic details from Vimeo
     *
     * @param $access_token
     * @param $provider_details
     * @return object
     */
    function getUserProfile($access_token, $provider_details = '')
    {
        // ask google api for user infos
        $graphApiUrl = 'https://api.vimeo.com/me?access_token=' . $access_token;
        $_response = _doGet($graphApiUrl);
        if (!isset($_response['uri']) || isset($_response['error'])) {
            throw new Exception("User profile request failed! Vimeo returned an invalid response.", 6);
        }
        $id = str_replace('/users/', '', $_response['uri']);
        $user = (object)array();
        $user->access_token = $user->access_token_secret = '';
        $user->access_token = $access_token;
        $user->identifier = $id;
        $user->displayName = $_response['name'];
        $user->email = array_key_exists('email', $_response) ? $_response['email'] : "";
        $user->address = $_response['location'];
        $user->profileURL = array_key_exists('profileurl', $_response) ? $_response['profileurl'] : "";
        $user->webSiteURL = array_key_exists('url', $_response) ? $_response['url'] : "";
        $user->description = array_key_exists('bio', $_response) ? $_response['bio'] : "";
        //$user->photoURL      = @ $data->person->portraits->portrait[3]->_content;
        return $user;
    }
    /**
     * To fetch user contacts from Vimeo
     *
     * @param $access_token
     * @param $provider_details
     */
    function getUserContacts($access_token, $provider_details = '')
    {
    }
    /**
     * To fetch user interest from Vimeo
     *
     * @param $access_token
     */
}
?>