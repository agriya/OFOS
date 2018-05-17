<?php
function getSudoPayObject()
{
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
        'cache_path' => ''
    ));
    return $s;
}
function register_website_account()
{
    $setting_account = Models\Setting::where('name', "SITE_IS_WEBSITE_CREATED")->first();
    if (SITE_IS_ENABLE_SUDOPAY_PLUGIN == 0 && $setting_account['value'] == 0) {
        $settings = Models\Setting::where('name', "SITE_DOMAIN_SECRET_HASH")->first();
        $domain_hash_value = gen_uuid();
        $settings->value = $domain_hash_value;
        $settings->save();
        $paymentGateway = Models\PaymentGateway::where('name', "Sudopay")->first();
        $s = getSudoPayObject();
        $postdata['domain_name'] = $_SERVER['HTTP_HOST'];
        $postdata['domain_secret_hash'] = $domain_hash_value;
        $credentials = $s->callRegisterWebsiteAccount($postdata);
        if (isset($credentials['error']['code']) && $credentials['error']['code'] == 0) {
            $value_field_name = 'live_mode_value';
            if ($paymentGateway['is_test_mode']) {
                $value_field_name = 'test_mode_value';
            }
            $paymentGatewaySettingNameToBeUpdate = array(
                'sudopay_merchant_id' => $credentials['merchant_id'],
                'sudopay_website_id' => $credentials['website_id'],
                'sudopay_secret_string' => $credentials['secret'],
                'sudopay_api_key' => $credentials['api_key']
            );
            foreach ($paymentGatewaySettingNameToBeUpdate as $tableFieldName => $ZazPayReturnValue) {
                $payment_gateway_settings = Models\PaymentGatewaySetting::where('name', $tableFieldName)->update(array(
                    $value_field_name => $ZazPayReturnValue
                ));
            }
            $s1 = getSudoPayObject();
            $currentPlan = $s1->callPlan();
            $plantype = $s1->plantype();
            if (!empty($currentPlan['error']['message'])) {
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
                $gateway_response = $s1->callGateways();
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
                //is_website_account_created settings update
                $settings = Models\Setting::where('name', "SITE_IS_WEBSITE_CREATED")->first();
                $settings->value = 1;
                $settings->id = $settings['id'];
                $settings->save();
                $result = array(
                    'status' => 'success',
                    'message' => 'Website account created'
                );
            }
        } else {
            $result = array(
                'error' => array(
                    'code' => 0,
                    'message' => 'Website account already Created'
                )
            );
        }
        return $result;
    }
}