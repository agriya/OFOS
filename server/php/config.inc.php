<?php
/**
 * Core configurations
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
define('R_DEBUG', false);
ini_set('display_errors', R_DEBUG);
define('R_API_VERSION', 1);
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(dirname(dirname(__FILE__))));
define('MEDIA_PATH', APP_PATH . DS . 'media');
define('TMP_PATH', APP_PATH . DS . 'tmp');
if (file_exists(APP_PATH . DS . 'client' . DS . 'scripts')) {
    define('SCRIPT_PATH', APP_PATH . DS . 'client' . DS . 'scripts');
    define('IMAGES_PATH', APP_PATH . DS . 'client' . DS . 'images');
} else {
    define('SCRIPT_PATH', APP_PATH . DS . 'client' . DS . 'app' . DS . 'scripts');
    define('IMAGES_PATH', APP_PATH . DS . 'client' . DS . 'app' . DS . 'images');
}
define('PLUGIN_PATH', APP_PATH . DS . 'server' . DS . 'php'. DS . 'Slim' . DS . 'plugins');
$default_timezone = 'Europe/Berlin';
if (ini_get('date.timezone')) {
    $default_timezone = ini_get('date.timezone');
}
date_default_timezone_set($default_timezone);
define('R_DB_DRIVER', 'pgsql');
define('R_DB_HOST', 'localhost');
define('R_DB_NAME', 'ofos');
define('R_DB_USER', 'postgres');
define('R_DB_PASSWORD', 'postgres');
define('R_DB_PORT', 5432);
define('SECURITY_SALT', 'e9a556134534545ab47c6c81c14f06c0b8sdfsdf');
define('OAUTH_CLIENT_ID', '2212711849319225');
define('OAUTH_CLIENT_SECRET', '14uumnygq6xyorsry8l382o3myr852hb');
define('PAGE_LIMIT', 20);
define('MAX_UPLOAD_SIZE', 8000);
$_server_protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
if (!defined('STDIN')) {
    $_server_domain_url = $_server_protocol . '://' . $_SERVER['HTTP_HOST']; // http://localhost
    
}
if (!defined('STDIN') && !file_exists(APP_PATH . '/tmp/cache/site_url_for_shell.php') && !empty($_server_domain_url)) {
    $fh = fopen(APP_PATH . '/tmp/cache/site_url_for_shell.php', 'a');
    fwrite($fh, '<?php' . "\n");
    fwrite($fh, '$_server_domain_url = \'' . $_server_domain_url . '\';');
    fclose($fh);
}
const THUMB_SIZES = array(
    'Restaurant' => array(
        'micro_thumb' => '42x42',
        'small_thumb' => '64x64',
        'medium_thumb' => '100x100',
        'large_thumb' => '120x120',
        'original' => '120x120'
    ) ,
    'RestaurantPhoto' => array(
        'micro_thumb' => '42x42',
        'large_thumb' => '525x295',
        'original' => '120x120'
    ) ,
    'RestaurantMenu' => array(
        'micro_thumb' => '42x42',
        'small_thumb' => '64x64',
        'original' => '120x120'
    )
);