<?php
/**
 * To fetch access token, user Twitter profile details, fetch user's followers and user's interests
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
 * Twitter Provider API Class
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
require 'twitter/twitteroauth.php';

class Providers_twitter
{
    /**
     * To get access token from Twitter
     *
     * @param $pass_value
     * @return string
     */
    function getRequestToken($pass_value)
    {
        global $_server_domain_url;
        $twitter = new TwitterOAuth($pass_value['api_key'], $pass_value['secret_key']);
        $oauth_callback = $pass_value['redirectUri'];
        $credentials = $twitter->getRequestToken($oauth_callback);
        $credentials['url'] = $twitter->getAuthorizeUrl($credentials['oauth_token']);
        return $credentials;
    }
    /**
     * To get access token from Twitter
     *
     * @param $pass_value
     * @return string
     */
    function getAccessToken($pass_value)
    {
        global $_server_domain_url;
        $twitter = new TwitterOAuth($pass_value['api_key'], $pass_value['secret_key'], $pass_value['oauth_token'], $pass_value['oauth_verifier']);
        $access_token = $twitter->getAccessToken($pass_value['oauth_verifier']);
        return $access_token;
    }
    /**
     * To fetch user profile basic details from Twitter
     *
     * @param $access_token
     * @param $provider_details
     * @return object
     */
    function getUserProfile($access_token, $provider_details = '')
    {
        //request user profile from fb api
        try {
            $twitter = new TwitterOAuth($provider_details['api_key'], $provider_details['secret_key'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
            $data = $twitter->get('account/verify_credentials');
        }
        catch(FacebookApiException $e) {
            throw new Exception("User profile request failed! Twitter returned an error: $e", 6);
        }
        // if the provider identifier is not recived, we assume the auth has failed
        if (!isset($data->id)) {
            throw new Exception("User profile request failed! Twitter api returned an invalid response.", 6);
        }
        // store the user profile.
        $user = (object)array();
        $user->access_token = $user->access_token_secret = '';
        $user->access_token_secret = $access_token['oauth_token_secret'];
        $user->access_token = $access_token['oauth_token'];
        $user->identifier = (property_exists($data, 'id')) ? $data->id : "";
        $user->displayName = (property_exists($data, 'screen_name')) ? $data->screen_name : "";
        $user->description = (property_exists($data, 'description')) ? $data->description : "";
        $user->firstName = (property_exists($data, 'name')) ? $data->name : "";
        $user->photoURL = (property_exists($data, 'profile_image_url')) ? $data->profile_image_url : "";
        $user->profileURL = (property_exists($data, 'screen_name')) ? ("http://twitter.com/" . $data->screen_name) : "";
        $user->webSiteURL = (property_exists($data, 'url')) ? $data->url : "";
        $user->region = (property_exists($data, 'location')) ? $data->location : "";
        return $user;
    }
    /**
     * To fetch user contacts from Twitter
     *
     * @param $access_token
     * @param $provider_details
     * @return object
     */
    function getUserContacts($access_token, $provider_details = '')
    {
        require_once 'twitter/twitteroauth.php';
        try {
            $twitter = new TwitterOAuth($provider_details['api_key'], $provider_details['secret_key'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
            $_response = $twitter->get('friends/ids');
        }
        catch(FacebookApiException $e) {
            throw new Exception("User contacts request failed! {$this->providerId} returned an error: $e");
        }
        if (!$_response || !count($_response->ids)) {
            return array();
        }
        $contactsids = array_chunk($_response->ids, 75);
        $contacts = array();
        foreach ($contactsids as $chunk) {
            $parameters = array(
                'user_id' => implode(",", $chunk)
            );
            $_resp = $twitter->get('users/lookup', $parameters);
            $uc = (object)array();
            if ($_resp && count($_resp)) {
                foreach ($_resp as $item) {
                    $uc->identifier = (property_exists($item, 'id')) ? $item->id : "";
                    $uc->displayName = (property_exists($item, 'name')) ? $item->name : "";
                    $uc->screenName = (property_exists($item, 'screen_name')) ? $item->screen_name : "";
                    $uc->profileURL = (property_exists($item, 'screen_name')) ? ("http://twitter.com/" . $item->screen_name) : "";
                    $uc->photoURL = (property_exists($item, 'profile_image_url')) ? $item->profile_image_url : "";
                    $uc->description = (property_exists($item, 'description')) ? $item->description : "";
                    $contacts[] = $uc;
                }
            }
        }
        return $contacts;
    }
}
?>