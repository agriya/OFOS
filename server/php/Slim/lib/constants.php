<?php
/**
 * Constant configurations
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
namespace Constants;

class ConstUserTypes
{
    const ADMIN = 1;
    const USER = 2;
    const RESTAURANT = 3;
    const SUPERVISOR = 4;
    const DELIVERYPERSON = 5;
}
class OrderStatus
{
    const PAYMENTPENDING = 1;
    const PAYMENTFAILED = 2;
    const PENDING = 3;
    const REJECTED = 4;
    const PROCESSING = 5;
    const DELIVERYPERSONASSIGNED = 6;
    const DELIVERED = 7;
    const REVIEWED = 8;
    const AWAITINGCODVALIDATION = 9;
    const CANCEL = 10;
    const OUTFORDELIVERY = 11;    
}
class UserCashWithdrawStatus
{
    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;
}
class PaymentGateways
{
    const SUDOPAY = 1;
    const WALLET = 2;
    const COD = 3;
    const PAYPAL = 4;
}
class TransactionKeys
{
    const ORDER = 'Order';
    const WALLET = 'Wallet';
}
class MenuPriceTypes
{
    const FIXED = 1;
    const SIZE = 2;
    const SLICE = 3;
}
class SocialLogins
{
    const FACEBOOK = 1;
    const TWITTER = 2;
    const GOOGLEPLUS = 3;
}
class ConstTransactionTypes
{
    const ADDEDTOWALLET = 1;
    const ORDERPLACED = 2;
    const REFUNDFORREJECTEDORDER = 3;
    const PAIDAMOUNTTORESTAURANT = 4;
}
class JWT
{
    const JWTTOKENEXPTIME = 6000;
}
