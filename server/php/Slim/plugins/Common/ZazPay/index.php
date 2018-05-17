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
require_once PLUGIN_PATH. DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . 'ZazPay' . DIRECTORY_SEPARATOR . 'functions.php';
require_once PLUGIN_PATH. DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . 'ZazPay' . DIRECTORY_SEPARATOR . 'sudopay.php';
/**
 * GET Gateways
 * Summary: Get Gateways
 * Notes: oauth
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/get_gateways', function ($request, $response, $args) {
    $sudopay_payment_gateways = Models\SudopayPaymentGateway::get()->toArray();
    $payment_gateway_settings = Models\PaymentGatewaySetting::where('name', 'payment_gateway_all_credentials')->first()->toArray();
    if (!empty($payment_gateway_settings)) {
        $test_mode_value = unserialize($payment_gateway_settings['test_mode_value']);
        $live_mode_value = unserialize($payment_gateway_settings['live_mode_value']);
    }
    if (!empty($sudopay_payment_gateways)) {
        foreach ($sudopay_payment_gateways as $sudopay_payment_gateway) {
            $gateway_id = $sudopay_payment_gateway['sudopay_gateway_id'];
            $sudopay_gateway_details = unserialize($sudopay_payment_gateway['sudopay_gateway_details']);
            $sudopay_live_mode_value = $sudopay_gateway_details;
            $sudopay_test_mode_value = $sudopay_gateway_details;
            if (!empty($sudopay_gateway_details)) {
                $sudopay_payment_gateway_form[$gateway_id]['name'] = $sudopay_gateway_details['name'];
                $sudopay_payment_gateway_form[$gateway_id]['id'] = $sudopay_gateway_details['id'];
                $sudopay_payment_gateway_form[$gateway_id]['display_name'] = $sudopay_gateway_details['display_name'];
            }
            if (!empty($sudopay_gateway_details['merchant_credential_fields'])) {
                if (!empty($payment_gateway_settings)) {
                    $merchant_credentials = $sudopay_gateway_details['merchant_credential_fields'];
                    $sudopay_live_mode_value = $merchant_credentials;
                    $sudopay_test_mode_value = $merchant_credentials;
                    foreach ($merchant_credentials as $field => $values) {
                        if ($live_mode_value[$gateway_id] != null) {
                            if (array_key_exists($field, $live_mode_value[$gateway_id])) {
                                $sudopay_live_mode_value[$field]['value'] = $live_mode_value[$gateway_id][$field];
                            }
                        }
                        if ($test_mode_value[$gateway_id] != null) {
                            if (array_key_exists($field, $test_mode_value[$gateway_id])) {
                                $sudopay_test_mode_value[$field]['value'] = $test_mode_value[$gateway_id][$field];
                            }
                        }
                    }
                } else {
                    $sudopay_test_mode_value = $sudopay_gateway_details['merchant_credential_fields'];
                    $sudopay_live_mode_value = $sudopay_gateway_details['merchant_credential_fields'];
                }
            }
            $sudopay_payment_gateway_form[$gateway_id]['test_mode_value'] = $sudopay_test_mode_value;
            $sudopay_payment_gateway_form[$gateway_id]['live_mode_value'] = $sudopay_live_mode_value;
        }
        $result['data'] = $sudopay_payment_gateway_form;
        return renderWithJson($result);
    } else {
        $response = array(
            'error' => array(
                'code' => 1,
                'message' => 'No Payment Gateways found',
                'fields' => ''
            )
        );
        return renderWithJson($response);
    }
});
/**
 * POST Gateways
 * Summary: Get Gateways
 * Notes: oauth
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/post_gateways', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $result = array();
    $ZazPay_credential_for_live = '';
    $ZazPay_credential_for_test = '';
    if (!empty($body['test_mode_value'])) {
        $ZazPay_credential_for_test = $body['test_mode_value'];
        $uploaded_to_sudopay = $body['test_mode_value'];
    }
    if (!empty($body['live_mode_value'])) {
        $ZazPay_credential_for_live = $body['live_mode_value'];
        $uploaded_to_sudopay = $body['live_mode_value'];
    }
    $payment_gateway_settings = Models\PaymentGatewaySetting::where('name', 'payment_gateway_all_credentials')->update(array(
        "test_mode_value" => serialize($ZazPay_credential_for_test) ,
        "live_mode_value" => serialize($ZazPay_credential_for_live)
    ));
    foreach ($uploaded_to_sudopay as $gateway_id => $data) {
        $s = getSudoPayObject();
        $s->callUpdateGatewayCredentials($gateway_id, $data);
    }
    $response = array(
        'error' => array(
            'code' => 0,
            'message' => 'Payment Gateways Updated',
            'fields' => ''
        )
    );
    return renderWithJson($response);
});
/**
 * GET sudopayPaymentPatewaysGet.
 * Summary: Get  sudo payment gateways.
 * Notes: Get  sudo payment gateways.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/sudopay_payment_gateways', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $sudoPaymentGateways = Models\SudopayPaymentGateway::Filter($queryParams)->paginate()->toArray();
        $data = $sudoPaymentGateways['data'];
        unset($sudoPaymentGateways['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $sudoPaymentGateways
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * GET paymentGatewaysSudopaySynchronizeGet
 * Summary: Get sudopay synchronize details
 * Notes: Get sudopay synchronize details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/payment_gateways/sudopay_synchronize', function ($request, $response, $args) {
    global $capsule;
    $result = array();
    $paymentGateway = new Models\PaymentGateway();
    $sudoPaymentSettings = Models\PaymentGatewaySetting::where('payment_gateway_id', 1)->get();
    foreach ($sudoPaymentSettings as $value) {
        $sudpay_synchronize[$value->name] = $value->test_mode_value;
    }
    $s = new SudoPay_API(array(
        'api_key' => $sudpay_synchronize['sudopay_api_key'],
        'merchant_id' => $sudpay_synchronize['sudopay_merchant_id'],
        'website_id' => $sudpay_synchronize['sudopay_website_id'],
        'secret_string' => $sudpay_synchronize['sudopay_secret_string'],
        'is_test' => true,
        'cache_path' => APP_PATH . '/tmp/cache/'
    ));
    $currentPlan = $s->callPlan();
    $plantype = $s->plantype();
    if (!empty($currentPlan['error']['message'])) {
        return renderWithJson($result, $currentPlan['error']['message'], '', 1);
    } else {
        if ($currentPlan['brand'] == 'Transparent Branding') {
            $plan = $plantype['TransparentBranding'];
        } elseif ($currentPlan['brand'] == 'SudoPay Branding') {
            $plan = $plantype['VisibleBranding'];
        } elseif ($currentPlan['brand'] == 'Any Branding') {
            $plan = $plantype['AnyBranding'];
        }
        $paymentGatewaySetting = new Models\PaymentGatewaySetting();
        if ($plantype['is_test_mode']) {
            $payment_gateway_api = $paymentGatewaySetting->where('name', 'is_payment_via_api')->where('payment_gateway_id', 1)->first();
            $payment_gateway_api->test_mode_value = $plan;
            $payment_gateway_api->save();
            $payment_gateway_plan = $paymentGatewaySetting->where('name', 'sudopay_subscription_plan')->where('payment_gateway_id', 1)->first();
            $payment_gateway_plan->test_mode_value = $currentPlan['name'];
            $payment_gateway_plan->save();
        } else {
            $payment_gateway_api = $paymentGatewaySetting->where('name', 'is_payment_via_api')->where('payment_gateway_id', 1)->first();
            $payment_gateway_api->live_mode_value = $plan;
            $payment_gateway_api->save();
            $payment_gateway_plan = $paymentGatewaySetting->where('name', 'sudopay_subscription_plan')->where('payment_gateway_id', 1)->first();
            $payment_gateway_plan->live_mode_value = $currentPlan['name'];
            $payment_gateway_plan->save();
        }
        $gateway_response = $s->callGateways();
        $capsule::table('sudopay_payment_groups')->delete();
        $capsule::table('sudopay_payment_gateways')->delete();
        if (empty($gateway_response['error']['message'])) {
            foreach ($gateway_response['gateways'] as $gateway_group) {
                $sudo_groups = new Models\SudopayPaymentGroup;
                $sudo_groups->sudopay_group_id = $gateway_group['id'];
                $sudo_groups->name = $gateway_group['name'];
                $sudo_groups->thumb_url = $gateway_group['thumb_url'];
                $sudo_groups->save();
                foreach ($gateway_group['gateways'] as $gateway) {
                    $sudo_payment_gateways = new Models\SudopayPaymentGateway;
                    $supported_actions = $gateway['supported_features'][0]['actions'];
                    $sudo_payment_gateways->is_marketplace_supported = 0;
                    if (in_array('Marketplace-Auth', $supported_actions)) {
                        $sudo_payment_gateways->is_marketplace_supported = 1;
                    }
                    $sudo_payment_gateways->sudopay_gateway_id = $gateway['id'];
                    $sudo_payment_gateways->sudopay_gateway_details = serialize($gateway);
                    $sudo_payment_gateways->sudopay_gateway_name = $gateway['display_name'];
                    $sudo_payment_gateways->sudopay_payment_group_id = $sudo_groups->id;
                    $sudo_payment_gateways->save();
                }
            }
        }
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    }
});
