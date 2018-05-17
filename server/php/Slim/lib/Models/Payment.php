<?php
/**
 * Payment
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

class Payment extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = '';
    public function processPayment($id, $body, $type)
    {
        $modelName = 'Models' . '\\' . $type;
        global $_server_domain_url;
        $payment_response = array();
        if ($body['payment_gateway_id'] == \Constants\PaymentGateways::SUDOPAY) {
            $settings = PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::SUDOPAY);
            $settings = $settings->get();
            foreach ($settings as $value) {
                $zazpay[$value->name] = $value->test_mode_value;
            }
            $s = new \SudoPay_API(array(
                'api_key' => $zazpay['sudopay_api_key'],
                'merchant_id' => $zazpay['sudopay_merchant_id'],
                'website_id' => $zazpay['sudopay_website_id'],
                'secret_string' => $zazpay['sudopay_secret_string'],
                'is_test' => true,
                'cache_path' => ''
            ));
            $post['gateway_id'] = $body['gateway_id'];
            $post['website_id'] = $zazpay['sudopay_website_id'];
            $post['currency_code'] = CURRENCY_CODE;
            $post['amount'] = $body['amount'];
            $post['item_name'] = $body['name'];
            $post['item_description'] = substr($body['description'], 0, 50);
            $post['buyer_email'] = $body['email'];
            $post['buyer_phone'] = $body['phone'];
            $post['buyer_address'] = $body['address'];
            $post['buyer_city'] = $body['city'];
            $post['buyer_state'] = $body['state'];
            $post['buyer_country'] = $body['country'];
            $post['buyer_zip_code'] = $body['zip_code'];
            if (!empty($body['credit_card_number'])) {
                $post['credit_card_number'] = $body['credit_card_number'];
                $post['credit_card_expire'] = $body['credit_card_expire'];
                $post['credit_card_name_on_card'] = $body['credit_card_name_on_card'];
                $post['credit_card_code'] = $body['credit_card_code'];
            } elseif (!empty($body['payment_note'])) {
                $post['payment_note'] = $body['payment_note'];
            }
            $post['notify_url'] = $body['notify_url'];
            $post['success_url'] = $body['success_url'];
            $post['cancel_url'] = $body['cancel_url'];                                   
            $payment_response = $s->callCapture($post);
            $data_response = $modelName::find($body['id']);
            if (!empty($payment_response['status']) && $payment_response['status'] == 'Captured' && $payment_response['error']['code'] == 0) {
                $post['paykey'] = $payment_response['paykey'];
                $post['status'] = 'Captured';
                $post['payment_type'] = 'Capture';
                $post['amount'] = $body['amount'];
                $post['payment_id'] = $payment_response['id'];
                $post['merchant_id'] = $zazpay['sudopay_merchant_id'];
                $post['buyer_id'] = $body['user_id'];
                $modelName::processCaptured($payment_response, $id);
                $response = array(
                    'data' => $data_response,
                    'payment_response' => $payment_response,
                    'error' => array(
                        'code' => 0,
                        'message' => 'order successfully completed'
                    )
                );
            } elseif (!empty($payment_response['status']) && $payment_response['status'] == 'Initiated' && $payment_response['error']['code'] <= 0) { //Offline payment
                $modelName::processInitiated($payment_response);
                if (!empty($payment_response['gateway_callback_url'])) {
                    $response = array(
                        'data' => $data_response,
                        'redirect_url' => $payment_response['gateway_callback_url'],
                        'payment_response' => $payment_response,
                        'error' => array(
                            'code' => 0,
                            'message' => 'redirect to payment url',
                            'fields' => ''
                        )
                    );
                } else {
                    $response = array(
                        'data' => $data_response,
                        'payment_response' => $payment_response,
                        'error' => array(
                            'code' => 0,
                            'message' => 'Initiated Payment without error code',
                            'fields' => ''
                        )
                    );
                }
            } elseif (!empty($payment_response['status']) && $payment_response['status'] == 'Pending' && $payment_response['error']['code'] == '-8') {
                $modelName::processPending($payment_response);
                $response = array(
                    'data' => $data_response,
                    'payment_response' => $payment_response,
                    'error' => array(
                        'code' => 0,
                        'message' => 'You order has been completed and payment is progress.'
                    )
                );
            } else {
                $response = array(
                    'data' => '',
                    'payment_response' => $payment_response,
                    'error' => array(
                        'code' => 1,
                        'message' => 'Payment could not be completed.Please try again...',
                        'fields' => ''
                    )
                );
            }
        } elseif ($body['payment_gateway_id'] == \Constants\PaymentGateways::WALLET) {
            $user = User::find($body['user_id']);
            $available_wallet_amount = $user['available_wallet_amount'];
            if ($available_wallet_amount >= $body['amount']) {
                $post = array();
                $post['amount'] = $body['amount'];
                $payment_response = array(
                    'status' => 'Captured'
                );               
                $response = $modelName::processCaptured($payment_response, $id);
                Payment::updateUserWalletAmount($body['user_id'], $body['amount']);
                $data_response = $modelName::find($body['id']);
                $response = array(
                    'data' => $data_response,
                    'payment_response' => $payment_response,
                    'error' => array(
                        'code' => 0,
                        'message' => 'Order successfully completed'
                    )
                );
            } else {
                $response = array(
                    'data' => '',
                    'error' => array(
                        'code' => 1,
                        'message' => 'Insufficient balance. Please add amount to wallet.',
                        'fields' => ''
                    )
                );
            }
        } elseif ($body['payment_gateway_id'] == \Constants\PaymentGateways::COD) {           
            $payment_response = array(
                    'status' => 'Captured'
                );               
            $response = $modelName::processCaptured($payment_response, $id, \Constants\OrderStatus::AWAITINGCODVALIDATION);
			$response['payment_response'] = $payment_response;
        } elseif ($body['payment_gateway_id'] == \Constants\PaymentGateways::PAYPAL) {
            require_once PLUGIN_PATH. DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . 'Paypal' . DIRECTORY_SEPARATOR . 'functions.php';
            $paymentGatewaySettings = PaymentGatewaySetting::where('payment_gateway_id', \Constants\PaymentGateways::PAYPAL)->get();
            foreach ($paymentGatewaySettings as $value) {
                $paypal[$value->name] = $value->test_mode_value;
            }
            $apiContext = getApiContext();
            if (!empty($body['cardType'])) {
                $body['credit_card_type'] = $body['cardType'];    
                $body['cvv2'] = $body['credit_card_code'];
            }
            if (!empty($body['credit_card_expired'])) {
                $body['expire_month'] = $body['credit_card_expired']['month'];    
                $body['expire_year'] = $body['credit_card_expired']['year'];
            }
            $body['success_url'] = $_server_domain_url . '/api/v1/paypal/process_payment?id=' . $id . '&model=' . $type;
            $payment = createPayment($id, $body);
            $data_response = $modelName::find($body['id']);
            if (!empty($payment) && empty($payment->message)) {
                if ($payment->getState() == 'created') {
                    $payment->status = 'Initiated';
                    $data_response->paypal_pay_key = $payment->getId();
                    $data_response->update();
                    if (!empty($payment->getApprovalLink())) {
                        $response = array(
                            'data' => $data_response,
                            'redirect_url' => $payment->getApprovalLink() ,
                            'payment_response' => $payment->toArray() ,
                            'error' => array(
                                'code' => 0,
                                'message' => 'redirect to payment url',
                                'fields' => ''
                            )
                        );
                    } else {
                        $response = array(
                            'data' => $data_response,
                            'payment_response' => $payment->toArray() ,
                            'error' => array(
                                'code' => 0,
                                'message' => 'Initiated Payment without error code',
                                'fields' => ''
                            )
                        );
                    }
                } elseif ($payment->getState() == 'approved') {
                    $transactions = $payment->getTransactions();
                    $relatedResources = $transactions[0]->getRelatedResources();
                    $sale = $relatedResources[0]->getSale();
                    $payment_response = array(
                        'status' => 'Captured',
                        'paykey' => $payment->getId()
                    );
                    $payment->status = 'Captured';
                    $modelName::processCaptured($payment_response, $id);
                    $data_response = $modelName::find($body['id']);
                    $response = array(
                        'data' => $data_response,
                        'payment_response' => $payment->toArray() ,
                        'error' => array(
                            'code' => 0,
                            'message' => 'order successfully completed'
                        )
                    );
                } else {
                    $response = array(
                        'data' => '',
                        'payment_response' => $payment_response,
                        'error' => array(
                            'code' => 1,
                            'message' => 'Payment could not be completed.Please try again...',
                            'fields' => ''
                        )
                    );
                }
            } else {
                $response = array(
                    'data' => $payment->data,
                    'payment_response' => $payment->message,
                    'error' => array(
                        'code' => 1,
                        'message' => 'Payment could not be completed.Please try again...',
                        'fields' => ''
                    )
                );
            }
        } else {
            $response = array(
                'data' => '',
                'payment_response' => $payment_response,
                'error' => array(
                    'code' => 1,
                    'message' => 'Payment could not be completed.Please try again...',
                    'fields' => ''
                )
            );
        }
        return $response;
    }
    public function updateUserWalletAmount($user_id, $amount)
    {
        $user = User::where('id', $user_id)->first();
        $user->makeVisible(['available_wallet_amount']);
        $user->available_wallet_amount = $user->available_wallet_amount - $amount;
        $user->save();
    }
    public function addTransactions($order, $type)
    {
        if ($type == 'Order') {
            $transaction = new Transaction;
            if ($order->order_status_id == \Constants\OrderStatus::REJECTED) {
                $transaction->user_id = \Constants\ConstUserTypes::ADMIN;
                $transaction->other_user_id = $order['user_id'];
                $transaction->transaction_type_id = \Constants\ConstTransactionTypes::REFUNDFORREJECTEDORDER;
            } elseif ($order->order_status_id == \Constants\OrderStatus::PENDING) {
                $transaction->user_id = $order['user_id'];
                $transaction->other_user_id = \Constants\ConstUserTypes::ADMIN;
                $transaction->transaction_type_id = \Constants\ConstTransactionTypes::ORDERPLACED;
            } elseif ($order->order_status_id == \Constants\OrderStatus::PROCESSING) {
                $transaction->user_id = \Constants\ConstUserTypes::ADMIN;
                $transaction->other_user_id = $order['restaurant']['user_id'];
                $transaction->transaction_type_id = \Constants\ConstTransactionTypes::PAIDAMOUNTTORESTAURANT;
            }
            $transaction->restaurant_id = $order['restaurant']['id'];
            $transaction->amount = $order['total_price'];
            $transaction->foreign_id = $order['id'];
            $transaction->class = \Constants\TransactionKeys::ORDER;
            $transaction->payment_gateway_id = !empty($order['payment_gateway_id']) ? $order['payment_gateway_id'] : '';
            $transaction->gateway_fees = !empty($order['payment_gateway']['gateway_fees']) ? $order['payment_gateway']['gateway_fees'] : '0.00';
            $transaction->save();
        }
        if ($type == 'Wallet') {
            $transaction = new Transaction;
            $transaction->user_id = $order['user_id'];
            $transaction->amount = $order['amount'];
            $transaction->foreign_id = $order['id'];
            $transaction->class = \Constants\TransactionKeys::WALLET;
            $transaction->transaction_type_id = \Constants\ConstTransactionTypes::ADDEDTOWALLET;
            $transaction->payment_gateway_id = !empty($order['payment_gateway_id']) ? $order['payment_gateway_id'] : '';
            $transaction->gateway_fees = !empty($order['payment_gateway']['gateway_fees']) ? $order['payment_gateway']['gateway_fees'] : '0.00';
            $transaction->save();
        }
        return true;
    }
}
