<?php
/**
 * API Endpoints
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
require_once PLUGIN_PATH. DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . 'Paypal' . DIRECTORY_SEPARATOR . 'functions.php';
use PayPal\Api\CreditCard;
use PayPal\Rest\ApiContext;
$app->GET('/api/v1/paypal/process_payment', function ($request, $response, $args) {
    global $authUser, $queryParams, $_server_domain_url;
    $queryParams = $request->getQueryParams();
    $results = array();
    if ($queryParams['paymentId'] && $queryParams['PayerID'] && $queryParams['token'] && $queryParams['id'] && $queryParams['model']) {
        $results = executePayment($queryParams['paymentId'], $queryParams['PayerID'], $queryParams['token'], $queryParams['id'], $queryParams['model']);
        header("Location: " . $results['data']['returnUrl']);
        die;
    } else {
        return renderWithJson($results, $message = 'Invalid Request', $fields = '', $isError = 1);
    }
    header("Location: " . $_server_domain_url);
    die;
});