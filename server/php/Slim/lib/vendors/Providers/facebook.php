<?php
class Providers_facebook
{
    function getAccessToken($pass_value)
    {
        global $_server_domain_url;
        $params = array(
            'code' => $pass_value['code'],
            'client_id' => $pass_value['api_key'],
            'redirect_uri' =>  $pass_value['redirectUri'],
            'client_secret' => $pass_value['secret_key']
        );
        $accessTokenUrl = 'https://graph.facebook.com/oauth/access_token';
        $_response = _doPost($accessTokenUrl, $params, 'json');
        $access_token = isset($_response['access_token']) ? $_response['access_token'] : false;
        return $access_token;
    }
    function getUserProfile($access_token, $provider_details = '')
    {
        // request user profile from fb api
        try {
            $graphApiUrl = 'https://graph.facebook.com/me?access_token=' . $access_token . '&fields=id,name,first_name,last_name,link,website,gender,email,hometown,birthday';
            $data = _doGet($graphApiUrl);
        }
        catch(FacebookApiException $e) {
            throw new Exception("User profile request failed! Facebook returned an error: $e", 6);
        }
        // if the provider identifier is not recived, we assume the auth has failed
        if (!isset($data["id"])) {
            throw new Exception("User profile request failed! Facebook api returned an invalid response.", 6);
        }
        // store the user profile.
        $user = (object)array();
        $user->access_token = $user->access_token_secret = '';
        $user->access_token = $access_token;
        $user->identifier = (array_key_exists('id', $data)) ? $data['id'] : "";
        $user->displayName = (array_key_exists('name', $data)) ? $data['name'] : "";
        $user->firstName = (array_key_exists('first_name', $data)) ? $data['first_name'] : "";
        $user->lastName = (array_key_exists('last_name', $data)) ? $data['last_name'] : "";
        $user->photoURL = "https://graph.facebook.com/" . $user->identifier . "/picture?type=large";
        $user->profileURL = (array_key_exists('link', $data)) ? $data['link'] : "";
        $user->webSiteURL = (array_key_exists('website', $data)) ? $data['website'] : "";
        $user->gender = (array_key_exists('gender', $data)) ? $data['gender'] : "";
        $user->description = (array_key_exists('bio', $data)) ? $data['bio'] : "";
        $user->email = (array_key_exists('email', $data)) ? $data['email'] : "";
        $user->emailVerified = (array_key_exists('email', $data)) ? $data['email'] : "";
        $user->region = (array_key_exists("hometown", $data) && array_key_exists("name", $data['hometown'])) ? $data['hometown']["name"] : "";
        if (array_key_exists('birthday', $data)) {
            list($birthday_month, $birthday_day, $birthday_year) = explode("/", $data['birthday']);
            $user->birthDay = (int)$birthday_day;
            $user->birthMonth = (int)$birthday_month;
            $user->birthYear = (int)$birthday_year;
        }
        return $user;
    }
    function getUserContacts($access_token, $provider_details = '')
    {
        try {
            $graphApiUrl = 'https://graph.facebook.com/me/friends?access_token=' . $access_token;
            $_response = _doGet($graphApiUrl);
        }
        catch(FacebookApiException $e) {
            throw new Exception("User contacts request failed! {$this->providerId} returned an error: $e");
        }
        if (!$_response || !count($_response["data"])) {
            return array();
        }
        $contacts = array();
        foreach ($_response["data"] as $item) {
            $uc = (object)array();
            $uc->identifier = (array_key_exists("id", $item)) ? $item["id"] : "";
            $uc->displayName = (array_key_exists("name", $item)) ? $item["name"] : "";
            $uc->profileURL = "https://www.facebook.com/profile.php?id=" . $uc->identifier;
            $uc->photoURL = "https://graph.facebook.com/" . $uc->identifier . "/picture?type=normal";
            $contacts[] = $uc;
        }
        return $contacts;
    }
}
?>