<?php
/**
 * Language
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

class Language extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'languages';
    protected $fillable = array(
        'name',
        'iso2',
        'iso3',
        'is_active'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'iso2' => 'sometimes|required|max:2',
        'iso3' => 'sometimes|required|max:3'
    );
    public $casts = array(
        'is_active' => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('name', 'ilike', "%$search%");
                $q1->orWhere('iso2', 'ilike', "%$search%");
                $q1->orWhere('iso3', 'ilike', "%$search%");                                                                
            });
        }         
    }   
}
