<?php
/**
 * Process IPN
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
require_once '../lib/bootstrap.php';
$request_uri = $_SERVER['REQUEST_URI'];
if (strpos($request_uri, "payments/") !== false || strpos($request_uri, "receiver_accounts/") !== false || strpos($request_uri, "paypal_subscribe/") !== false) {
    $s = getSudoPayObject();
    $print_raw_response = !empty($_GET['print_raw_response']) ? true : false;
    $response = $s->callIndirectRegisteredWebsiteHits($request_uri, $_GET, $_POST, $print_raw_response);
    if (!empty($response['success_url'])) {
        header('location: ' . $response['success_url']);
        exit;
    }
    if (!empty($response['cancel_url'])) {
        header('location: ' . $response['cancel_url']);
        exit;
    }
} else {
    $ipn_data = $_POST;
    $ipn_data['post_variable'] = serialize($_POST);
    $ipn_data['ip_id'] = (!empty(saveIp())) ? saveIp() : null;
    $payment = new Models\Payment;
    if ($_GET['hash'] == md5(SECURITY_SALT . $_GET['id'] . SITE_NAME)) {
         $sudoPaymentSettings = Models\PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::SUDOPAY)->get();
        foreach ($sudoPaymentSettings as $value) {
            $zazpay[$value->name] = $value->test_mode_value;
        }
        $modelName = 'Models' . '\\' . $_GET['type'];
        $modelResponse = $modelName::where('id', $_GET['id'])->with('user')->first();       
        if (!empty($modelResponse)) {
            if (!empty($ipn_data['status']) && $ipn_data['status'] == 'Captured' && $ipn_data['error_code'] == 0) {
                $post['amount'] = $ipn_data['amount'];
                $post['paykey'] = $ipn_data['paykey'];
                $post['merchant_id'] = $zazpay['sudopay_merchant_id'];
                $post['payment_id'] = $ipn_data['id'];
                $post['gateway_id'] = $ipn_data['gateway_id'];
                $post['status'] = 'Captured';
                $post['payment_type'] = 'Capture';
                $post['buyer_id'] = $modelResponse['user_id'];
                $post['buyer_email'] = $modelResponse['user']['email'];
                $dispatcher = $modelName::getEventDispatcher();
                $modelName::unsetEventDispatcher();
                $response = $modelName::processCaptured($post, $_GET['id']);
                $modelName::setEventDispatcher($dispatcher);
            } elseif (!empty($ipn_data['status']) && $ipn_data['status'] == 'Pending' && $ipn_data['error_code'] != 0) {
                $response = $modelName::processInitiated($ipn_data);
            } else {
                $response = $modelName::processPending($ipn_data);
            }
        }
    }
}
