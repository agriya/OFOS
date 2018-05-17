<?php
/**
 * OrderStatus
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

class OrderStatus extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_statuses';
    protected $fillable = array(
        'name'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $q1->where('name', 'ilike', '%' . $params['q'] . '%');
            });
        }
    }  
    public function order()
    {
        return $this->hasMany('Models\Order', 'order_status_id', 'id');
    }        
}
