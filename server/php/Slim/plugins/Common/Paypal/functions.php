<?php
$app_path = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR;
require_once $app_path . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.php';
use PayPal\Api\CreditCard;
use PayPal\Rest\ApiContext;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\Transaction;
use PayPal\Api\Address;
use PayPal\Api\ShippingAddress;
use PayPal\Api\RedirectUrls;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Refund;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;

/**
 * Helper method for getting an APIContext for all calls
 * @param string $clientId Client ID
 * @param string $clientSecret Client Secret
 * @return PayPal\Rest\ApiContext
 */
function getApiContext()
{
    $paymentGatewaySettings = Models\PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::PAYPAL)->get();
    foreach ($paymentGatewaySettings as $value) {
        $payPal['sandbox'][$value->name] = $value->test_mode_value;
        $payPal['live'][$value->name] = $value->live_mode_value;
    }
    $sanbox_mode = 'live';
    $payment_gateways = Models\PaymentGateway::select('is_test_mode')->where('id', \Constants\PaymentGateways::PAYPAL)->first();
    if (!empty($payment_gateways->is_test_mode)) {
        $sanbox_mode = 'sandbox';
    }
    if ($sanbox_mode == 'sandbox') {
        $payPal = $payPal['sandbox'];
    } else {
        $payPal = $payPal['live'];
    }
    $apiContext = new ApiContext(new OAuthTokenCredential($payPal['paypal_client_id'], $payPal['paypal_client_Secret']));
    $apiContext->setConfig(array(
        'mode' => $sanbox_mode,
        'log.LogEnabled' => false,
        'log.FileName' => APP_PATH . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'logs/PayPal.log',
        'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
        'cache.enabled' => false,
        'cache.FileName' => APP_PATH . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'cache/auth.cache'
    ));
    return $apiContext;
}
function createPayment($id, $body)
{
    global $authUser;
    try {
        $apiContext = getApiContext();
        $payer = new Payer();
        if (empty($body['vault_id']) && !empty($body['credit_card_type'])) {
            $card = new PaymentCard();
            $card->setType($body['credit_card_type'])->setNumber($body['credit_card_number'])->setExpireMonth($body['expire_month'])->setExpireYear($body['expire_year'])->setCvv2($body['cvv2'])->setFirstName($body['first_name'])->setBillingCountry($body['buyer_country_iso2'])->setLastName($body['last_name']);
            $fi = new FundingInstrument();
            $fi->setPaymentCard($card);
            $payer->setPaymentMethod("credit_card")->setFundingInstruments(array(
                $fi
            ));
        } elseif (!empty($body['vault_id'])) {
            $creditCardToken = new CreditCardToken();
            $vaults = Models\Vault::where('id', $body['vault_id'])->where('user_id', $body['user_id'])->first();
            $creditCardToken->setCreditCardId($vaults['vault_key']);
            $fi = new FundingInstrument();
            $fi->setCreditCardToken($creditCardToken);
            $payer->setPaymentMethod("credit_card")->setFundingInstruments(array(
                $fi
            ));
        } else {
            $payer->setPaymentMethod('paypal');
        }
        $price = $body['amount'];
        $item1 = new Item();
        $item1->setName(substr($body['name'], 0, 100))->setDescription(substr($body['description'], 0, 100))->setCurrency(CURRENCY_CODE)->setQuantity(1)->setPrice($price);
        $itemLists[] = $item1;
        $itemList = new ItemList();
        $itemList->setItems($itemLists);
        $amount = new Amount();
        $body['amount'] = !empty($body['amount']) ? (double)$body['amount'] : 0;
        $amount->setCurrency(CURRENCY_CODE)->setTotal((double)$body['amount']);
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($itemList)->setDescription(substr($body['description'], 0, 100))->setInvoiceNumber(uniqid());
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($body['success_url'])->setCancelUrl($body['cancel_url']);
        $payment = new Payment();
        $payment->setIntent("sale")->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions(array(
            $transaction
        ));
        $payment->create($apiContext);
        $payment->message = '';
        return $payment;
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
        $data = json_decode($ex->getData());
        $result['data'] = $data;
        $result['message'] = 'Payment could not be created';
        return $result;
    } catch (Exception $ex) {
        $result['data'] = $data;
        $result['message'] = 'Payment could not be created' . $ex->getMessage();
        return $result;
    }
}
function executePayment($payID, $payerID, $token, $id, $model)
{
    global $authUser, $_server_domain_url;
    $results = array();
    $model_name = 'Models' . '\\' . $model;
    $data_response = $model_name::find($id);
    if (!empty($data_response)) {
        try {
            $returnUrls = getReturnURL($model, $data_response);
            $apiContext = getApiContext();
            $data = array();
            $payment = Payment::get($payID, $apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerID);
            $payment->execute($execution, $apiContext);
            if ($payment->getIntent() == 'sale' && $payment->getState() == 'approved') {
                $transactions = $payment->getTransactions();
                $relatedResources = $transactions[0]->getRelatedResources();
                $sale = $relatedResources[0]->getSale();
                $payment_response = array(
                    'status' => 'Captured',
                    'paykey' => $payment->getId()
                );
                $model_name::processCaptured($payment_response, $id);
                $results['data']['returnUrl'] = $returnUrls['success_url'];
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {            
            $results['data']['returnUrl'] = $returnUrls['success_url'] . '&error_message=Payment could not be created';
        } catch (Exception $ex) {            
            $results['data']['returnUrl'] = $returnUrls['success_url'] . '&error_message=Payment could not be created';
        }
    } else {
        $results['data']['returnUrl'] = $_server_domain_url . '/' . strtolower($model) . '?error_code=512&error_message=Invalid Request';
    }
    return $results;
}
