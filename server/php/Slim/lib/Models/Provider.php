<?php
/**
 * Provider
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

class Provider extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'providers';
    protected $fillable = array(
        'name',
        'secret_key',
        'api_key',
        'is_active',
        'icon_class',
        'button_class',
        'display_order'
    );
    public $casts = array(
        'is_active' => 'integer'
    );
    public $rules = array(
        'name' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('name', 'ilike', "%$search%");
                $q1->orWhere('secret_key', 'ilike', "%$search%");
                $q1->orWhere('api_key', 'ilike', "%$search%");                                                  
            });
        }         
    }  
    public function provider_user()
    {
        return $this->hasMany('Models\ProviderUser','provider_id','id');
    }     
    public function user()
    {
        return $this->hasMany('Models\User','provider_id','id');
    }       
}
