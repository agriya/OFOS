<?php
/**
 * SudopayIpnLog
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

class SudopayIpnLog extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sudopay_ipn_logs';
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
    }   
}
