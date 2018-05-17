<?php
/**
 * Sample cron file
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
require_once __DIR__ . '/../../config.inc.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../lib/database.php';
updateIsRestaurantClosed();
function updateIsRestaurantClosed()
{
    $current_time = date('H:i:s');
    $current_day = date('l');
    $restaurant_timings = Models\RestaurantTiming::where('start_time', '<=', $current_time)->where('end_time', '>=', $current_time)->where('day', $current_day)->get()->toArray();
    foreach ($restaurant_timings as $restaurant_timing) {
        $restaurant_ids[] = $restaurant_timing['restaurant_id'];
    }
    if (!empty($restaurant_ids)) {
        $restaurant = Models\Restaurant::whereIn('id', $restaurant_ids)->update(['is_closed' => 0]);
        $restaurant = Models\Restaurant::whereNotIn('id', $restaurant_ids)->update(['is_closed' => 1]);
    }
}
