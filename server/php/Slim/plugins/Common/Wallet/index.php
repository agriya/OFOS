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

$app->GET('/api/v1/wallets', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $wallets = Models\Wallet::Filter($queryParams)->paginate()->toArray();
        $data = $wallets['data'];
        unset($wallets['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $wallets
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canListWallet'));
/**
 * POST walletsPost
 * Summary: Creates a new wallet
 * Notes: Creates a new wallet
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/wallets', function ($request, $response, $args) {
    global $authUser, $_server_domain_url;
    $result = array();
    $args = $request->getParsedBody();
    $amount = $args['amount'];
    if ($amount > 0) {
        $wallet = new Models\Wallet;
        $wallet->user_id = $authUser->id;
        $wallet->amount = $amount;
        $wallet->is_payment_completed = 0;
        $wallet->payment_gateway_id = $args['payment_gateway_id'];
        $wallet->gateway_id = !empty($args['gateway_id']) ? $args['gateway_id'] : 0;
        $wallet->success_url = !empty($args['success_url']) ? $args['success_url'] : '';
        $wallet->cancel_url = !empty($args['cancel_url']) ? $args['cancel_url'] : '';
        $wallet->save();
        $payment = new Models\Payment;
        $args['first_name'] = $authUser->first_name;
        $args['last_name'] = $authUser->last_name;
        //TODO quick fix
        if (!empty($authUser->mobile_code)) {
            $country = Models\Country::select('iso2', 'id')->where('phone', $authUser->mobile_code)->first();
            if (!empty($country)) {
                $args['buyer_country_iso2'] = $country['iso2'];
            }
        } else {
           $args['buyer_country_iso2'] = 'IN'; 
        }
        $args['name'] = 'Added to wallet';
        $args['description'] = 'Amount added to wallet';
        $args['amount'] = $wallet->amount;
        $args['id'] = $wallet->id;
        $args['notify_url'] = $_server_domain_url . '/ipn/process_ipn/' . $wallet->id . '/hash/' . md5(SECURITY_SALT . $wallet->id . SITE_NAME).'/Wallet';
        $args['success_url'] = $wallet->success_url;
        $args['cancel_url'] = $wallet->cancel_url;
        $result = $payment->processPayment($wallet->id, $args, 'Wallet');
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'Amount should be greater than 0.', '', 1);
    }
})->add(new Acl\ACL('canCreateWallet'));
