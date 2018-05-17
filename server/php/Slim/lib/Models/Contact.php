<?php
/**
 * Contact
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

class Contact extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contacts';
    protected $fillable = array(
        'first_name',
        'last_name',
        'email',
        'phone',
        'subject',
        'message',
        'ip_id'
    );
    public $rules = array(
        'first_name' => 'sometimes|required',
        'last_name' => 'sometimes|required',
        'email' => 'sometimes|required|email',
        'phone' => 'sometimes|required',
        'subject' => 'sometimes|required',
        'message' => 'sometimes|required'
    );
    protected $casts = array (
        'ip_id' => 'integer'
    );
    public function ip()
    {
        return $this->belongsTo('Models\Ip', 'ip_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->Where(function ($q1) use ($params) {
                $q1->where('first_name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhere('last_name', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhere('email', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhere('subject', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhere('message', 'ilike', '%' . $params['q'] . '%');
                $q1->orWhereHas('ip', function ($q) use ($params) {
                    $q->where('ip', 'ilike', '%' . $params['q'] . '%');
                });
            });
        }
    }
}
