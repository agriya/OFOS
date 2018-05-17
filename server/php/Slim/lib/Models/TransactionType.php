<?php
/**
 * TransactionType
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

class TransactionType extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transaction_types';
    protected $fillable = array(
        'name',
        'is_credit',
        'is_credit_to_other_user',
        'is_credit_to_admin	',
        'message',
        'message_for_other_user',
        'message_for_admin',
        'transaction_variables'
    );
    protected $casts = array(
        'is_credit' => 'integer',
        'is_credit_to_other_user' => 'integer',
        'is_credit_to_admin' => 'integer'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q->where('name', 'ilike', "%$search%");
                $q-orWhere('message', 'ilike', "%$search%");
                $q-orWhere('message_for_other_user', 'ilike', "%$search%");
                $q-orWhere('message_for_admin', 'ilike', "%$search%");            
            });
        }
    }   
    
}
