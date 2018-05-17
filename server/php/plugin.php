<?php
/**
 * To create minify plugin cache
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://agriya.com/ Agriya Licence
 * @link       http://agriya.com/
 */
require_once './Slim/lib/bootstrap.php';
if (!file_exists(SCRIPT_PATH . DS . $_GET['file'])) {
    $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
    $concat = '';
    foreach ($enabled_plugins as $plugin) {
        $pluginPath = str_replace('/', '.', $plugin);
        $plugin_name = explode('/', $plugin);
        if (file_exists(SCRIPT_PATH . DS . 'plugins' . DS . $plugin . DS . 'default.cache.js')) {
            $concat .= file_get_contents(SCRIPT_PATH . DS . 'plugins' . DS . $plugin . DS . 'default.cache.js');
        }
        $concat .= "angular.module('ofosApp').requires.push('ofosApp." . $pluginPath . "');";
    }
    file_put_contents(SCRIPT_PATH . DS . $_GET['file'], $concat);
    header('Location:' . $_SERVER['REQUEST_URI'] . '?chrome-3xx-fix');
} else {
    echo file_get_contents(SCRIPT_PATH . DS . $_GET['file']);
}