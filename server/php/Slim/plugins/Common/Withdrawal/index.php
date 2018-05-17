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

/**
 * GET user cash withdrawals GET.
 * Summary: Get  user cash withdrawals.
 * Notes: Filter user cash withdrawals.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_cash_withdrawals', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $userCashWithdrawals = Models\UserCashWithdrawal::Filter($queryParams)->paginate()->toArray();
        $data = $userCashWithdrawals['data'];
        unset($userCashWithdrawals['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $userCashWithdrawals
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canListUserCashWithdrawals'));

/**
 * POST userUserIdUserCashWithdrawals.
 * Summary: Create user cash withdrawals.
 * Notes: Create user cash withdrawals.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/user_cash_withdrawals', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $userCashWithdrawal = new Models\UserCashWithdrawal;
    $validationErrorFields = $userCashWithdrawal->validate($args);
    if (empty($validationErrorFields)) {
        $userCashWithdrawal->fill($args);
        $userCashWithdrawal->user_id = $authUser->id;
        try {
            $userCashWithdrawal->save();
            $result = $userCashWithdrawal->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'User cash withdrawals could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUserCreateUserCashWithdrawals'));
/**
 * GET useruserIdUserCashWithdrawalsUserCashWithdrawalsIdGet
 * Summary: Get paticular user cash withdrawals
 * Notes:  Get paticular user cash withdrawals
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_cash_withdrawals/{userCashWithdrawalsId}', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    $userCashWithdrawal = Models\UserCashWithdrawal::Filter($queryParams)->find($request->getAttribute('userCashWithdrawalsId'));
    if (empty($userCashWithdrawal) || ($authUser->role_id != \Constants\ConstUserTypes::ADMIN)) {
        return renderWithJson($result, 'Invalid user. Please, try again.', '', 1);
    } else {
        $result['data'] = $userCashWithdrawal->toArray();
        return renderWithJson($result);
    }
})->add(new Acl\ACL('canViewUserCashWithdrawals'));
/**
 * PUT usersUserIdUserCashWithdrawalsUserCashWithdrawalsIdPut
 * Summary: Update  user cash withdrawals.
 * Notes: Update user cash withdrawals.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/user_cash_withdrawals/{userCashWithdrawalsId}', function ($request, $response, $args) {
    global $authUser;
    $body = $request->getParsedBody();
    $result = array();
    $userCashWithdrawal = Models\UserCashWithdrawal::with('restaurant', 'user')->where('id', $request->getAttribute('userCashWithdrawalsId'))->first();
    if (empty($userCashWithdrawal) || ($authUser->role_id != \Constants\ConstUserTypes::ADMIN)) {
        return renderWithJson($result, 'Invalid user. Please, try again.', '', 1);
    } else {
        if (empty($validationErrorFields)) {
            if (!empty($userCashWithdrawal)) {
                $userCashWithdrawal->fill($body);
                $userCashWithdrawal->save();
                $emailFindReplace = array(
                    '##USERNAME##' => $userCashWithdrawal['user']['username'],
                    '##RESTAURANT_NAME##' => $userCashWithdrawal['restaurant']['name']
                );
                sendMail('adminpaidyourwithdrawalrequest', $emailFindReplace, $userCashWithdrawal['user']['email']);
                $result['data'] = $userCashWithdrawal->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'User cash withdrawals could not be updated. Please, try again.', '', 1);
            }
        } else {
            return renderWithJson($result, 'User cash withdrawals could not be updated. Please, try again.', $validationErrorFields, 1);
        }
    }
})->add(new Acl\ACL('canUpdateUserCashWithdrawals'));
/**
 * GET MoneyTransferAccountsGet
 * Summary: Get money transfer accounts lists
 * Notes: Get money transfer accounts lists
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/money_transfer_accounts', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $moneyTransferAccounts = Models\MoneyTransferAccount::Filter($queryParams)->paginate()->toArray();
        $data = $moneyTransferAccounts['data'];
        unset($moneyTransferAccounts['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $moneyTransferAccounts
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canListMoneyTransferAccount'));
/**
 * POST moneyTransferAccountPost
 * Summary: Create New money transfer account
 * Notes: Create money transfer account.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/money_transfer_accounts', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $moneyTransferAccount = new Models\MoneyTransferAccount;
    $validationErrorFields = $moneyTransferAccount->validate($args);
    if (empty($validationErrorFields)) {
        $moneyTransferAccount->fill($args);
        if(!isset($args['user_id'])){
            $moneyTransferAccount->user_id = $authUser->id;
        }
        try {
            $moneyTransferAccount->save();
            $result = $moneyTransferAccount->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Account could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateMoneyTransferAccount'));
/**
 * GET MoneyTransferAccountsMoneyTransferAccountIdGet
 * Summary: Get particular money transfer accounts
 * Notes: Get particular money transfer accounts
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/money_transfer_accounts/{moneyTransferAccountId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();    
    $result = array();
    $moneyTransferAccount = Models\MoneyTransferAccount::Filter($queryParams)->find($request->getAttribute('moneyTransferAccountId'));
    if (!empty($moneyTransferAccount)) {
        $result['data'] = $moneyTransferAccount->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewMoneyTransferAccount'));
/**
 * PUT moneyTransferAccountMoneyTransferAccountIdPut
 * Summary: Update money transfer account by its id
 * Notes: Update money transfer account.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/money_transfer_accounts/{MoneyTransferAccountId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $moneyTransferAccount = Models\MoneyTransferAccount::find($request->getAttribute('MoneyTransferAccountId'));
    $validationErrorFields = $moneyTransferAccount->validate($args);
    if (empty($validationErrorFields)) {
        $moneyTransferAccount->fill($args);
        try {
            $moneyTransferAccount->save();
            $result['data'] = $moneyTransferAccount->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Account could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateMoneyTransferAccount'));
/**
 * DELETE MoneyTransferAccountsMoneyTransferAccountIdDelete
 * Summary: Delete money transfer account
 * Notes: Delete money transfer account
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/money_transfer_accounts/{moneyTransferAccountId}', function ($request, $response, $args) {
    $result = array();
    $moneyTransferAccount = Models\MoneyTransferAccount::where('id', $request->getAttribute('moneyTransferAccountId'))->first();
    try {
        $moneyTransferAccount->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteMoneyTransferAccount'));
