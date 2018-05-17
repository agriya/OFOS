<?php
/**
 * Setting
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

class SettingCategory extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'setting_categories';
    public $fillable = array(
        'name',
        'description'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
        $query->where(function ($q) use ($enabled_plugins) {
               $q->whereIn('plugin', $enabled_plugins);
               $q->orWhere('plugin', null);
            });        
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhere('description', 'ilike', '%' . $params['q'] . '%');
            });
        }
    }   
    
}
