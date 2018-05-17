<?php
/**
 * Country
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

class Country extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';
    protected $fillable = array(
        'iso2',
        'iso3',
        'name',
        'continent',
        'currency',
        'currencyname',
        'phone',
        'postalcodeformat',
        'postalcoderegex',
        'languages'
    );
    public $rules = array(
        'name' => 'sometimes|required'
    );
    public function user_address()
    {
        return $this->has_one('Models\UserAddress');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('name', 'ilike', "%$search%");
                $q1->orWhere('iso2', 'ilike', "%$search%");
                $q1->orWhere('iso3', 'ilike', "%$search%");                                                                
                $q1->orWhere('phone', 'ilike', "%$search%");

            });
        }        
    }
    /**
    * Get country id
    *
    * @param int $iso2  ISO2
    *
    * @return int country Id
    */
    public function findCountryIdFromIso2($iso2)
    {
        $country = Country::where('iso2', $iso2)->select('id')->first();
        if (!empty($country)) {
            return $country['id'];
        }
    }   
}
