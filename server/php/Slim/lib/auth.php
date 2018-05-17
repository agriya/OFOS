<?php
use \Firebase\JWT\JWT;

class Auth
{
    //To set auth for user
    public function __invoke($request, $response, $next)
    {
        global $authUser;
        $requestUri = $request->getRequestTarget();
        $token = "";
        $docodedData = array();
        $result = array(
            'error' => true,
            'message' => 'Authorization Failed.'
        );
        if (strpos($requestUri, 'paypal/process_payment')) {
            $response = $next($request, $response);
            return $response;
        }
        if ((!isset($request->getHeaders()['HTTP_X_AG_APP_SECRET']) || !isset($request->getHeaders()['HTTP_X_AG_APP_ID']))) {
            return $response->withJson($result, 401);
        } else {
            $api_key = $request->getHeaders() ['HTTP_X_AG_APP_ID'][0];
            $api_secret = $request->getHeaders() ['HTTP_X_AG_APP_SECRET'][0];
            $oauth_clients = Models\OauthClient::where(['api_key' => $api_key, 'api_secret' => $api_secret ])->first();
            if (!$oauth_clients) {
                return $response->withJson($result, 401);
            } else {           
                if (isset($request->getHeaders() ['HTTP_AUTHORIZATION']) && !empty($request->getHeaders() ['HTTP_AUTHORIZATION'])) {
                    $token = $request->getHeaders() ['HTTP_AUTHORIZATION'][0];
                    if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
                        $token = $matches[1];
                    }
                }
                if (!empty($token)) {
                    $accessToken = Models\UserToken::where('token', $token)->first();
                    $expires = !empty($accessToken['expires']) ? strtotime($accessToken['expires']) : 0;
                    if (empty($accessToken['token']) || ($expires > 0 && $expires < time() && !($oauth_clients->api_key == '4542632501382585' && $oauth_clients->api_secret == '3f7C4l1Y2b0S6a7L8c1E7B3Jo3'))) {
                        return $response->withJson($result, 401);
                    } else {
                        $authUser = Models\User::select('id', 'role_id', 'email', 'username', 'mobile_code', 'first_name', 'last_name')->with('restaurant', 'restaurant_supervisor.restaurant', 'restaurant_delivery_person.restaurant')->where('id', $accessToken['user_id'])->where('is_active', 1)->first();
                        if(!$authUser){
                            return $response->withJson($result, 401);
                        }
                        $authUser['scope'] = $authUser['scopes_' . $authUser['role_id']];
                        $response = $next($request, $response);
                    }
                } else {
                    $response = $next($request, $response);
                }
                return $response;
            }
            return $response;
        }           
    }
}
