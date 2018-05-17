<?php
/**
 * EmailTemplate
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

class EmailTemplate extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_templates';
    protected $fillable = array(
        'from_email',
        'reply_to_email',
        'subject',
        'html_email_content',
        'text_email_content',
        'display_name',
        'name',
        'description',
        'email_variables',
        'to_email',
        'is_admin_email',
        'is_html'
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
                $q1->orWhere('subject', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhere('display_name', 'ilike', '%' . $params['q'] . '%');
            });
        }
    }   
}
