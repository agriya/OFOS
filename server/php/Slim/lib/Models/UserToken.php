<?php
/**
 * Models/UserTokens.php
 *
 * This file model for UserTokens
 *
 *
 * @category Models
 */
namespace Models;

class UserToken extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "user_tokens";
    protected $fillable = array(
        'user_id',
        'token',
        'expires',
        'oauth_client_id'
    );
    protected $casts = array(
        'user_id' => 'integer'
    );    
    public $rules = array(
        'user_id' => 'sometimes|required|integer',
        'token' => 'sometimes|required',
        'expires' => 'sometimes|required'
    );
    public function insertUserToken($user_id, $jwt_token, $request = '', $manual_oauth = false) {
        if($manual_oauth) {
            $oauth_clients = OauthClient::where('name', 'Web')->first();
        }elseif(!empty($request->getHeaders()['HTTP_X_AG_APP_SECRET']) && !empty($request->getHeaders()['HTTP_X_AG_APP_ID'])) {
            $api_key = $request->getHeaders() ['HTTP_X_AG_APP_ID'][0];
            $api_secret = $request->getHeaders() ['HTTP_X_AG_APP_SECRET'][0];
            $oauth_clients = OauthClient::where(['api_key' => $api_key, 'api_secret' => $api_secret ])->first();
        }
        if($oauth_clients){
            $issuedAt = time();
            $notBefore = $issuedAt + 11000; //Adding 1000 seconds
            $expire = $notBefore + getenv("JWT_TOKEN_EXP_TIME"); // Adding 6000 seconds    
            $expirationDateTime = date('Y-m-d H:i:s', $expire);
            $insertToken = array(
                'user_id' => $user_id,
                'token' => $jwt_token,
                'expires' => $expirationDateTime,
                'oauth_client_id' => $oauth_clients->id
            );
            $jwtToken = new UserToken();
            $jwtToken->fill($insertToken);
            $jwtToken->save();
        }
    }
}
