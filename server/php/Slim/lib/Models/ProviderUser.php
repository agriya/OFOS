<?php
/**
 * ProviderUser
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

class ProviderUser extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'provider_users';
    protected $fillable = array (
        'user_id',
        'provider_id',
        'foreign_id',
        'profile_picture_url',
        'access_token',
        'access_token_secret',
        'is_connected'
    );
    public $casts = array (
        'is_connected' => 'integer',
        'provider_id' => 'integer',
        'user_id' => 'integer'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function provider()
    {
        return $this->belongsTo('Models\Provider', 'provider_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $q1->whereHas('user', function ($q) use ($params) {
                    $q->where('username', 'ilike', "%". $params['q'] . "%");
                });
                $q1->orWhereHas('provider', function ($q) use ($params) {
                    $q->where('name', 'ilike', "%". $params['q'] . "%");
                });
            });
        }
    }   
}
