<?php
/**
 * Role
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

class Role extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';
    protected $fillable = array(
        'name',
        'is_active'
    );
    protected $casts = array(
        'is_active' => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('name', 'ilike', '%' . $params['q'] . '%');
            });
        }
    }   
    
}
