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
 * GET restaurantDeliveryPersonsGet
 * Summary: Get Delivery Persons details
 * Notes: Get user Delivery Persons details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_delivery_persons', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantDeliveryPersons = Models\DeliveryPerson::Filter($queryParams);
        if (!empty($request->getAttribute('restaurantId'))) {
            $restaurantDeliveryPersons->where('restaurant_id', $request->getAttribute('restaurantId'));
            if (isset($branch_id) && isset($supervisor_id)) {
                $restaurantDeliveryPersons->where('restaurant_branch_id', $request->getAttribute('restaurantBranchId'))->where('restaurant_supervisor_id', $request->getAttribute('restaurantSupervisorId'));
            } elseif (isset($branch_id)) {
                if ($authUser['role_id'] == \Constants\ConstUserTypes::SUPERVISOR) {
                    $restaurantSupervisor = Models\Supervisor::select('id')->where('user_id', $authUser['id'])->first();
                    $restaurantDeliveryPersons->where('restaurant_supervisor_id', $restaurantSupervisor['id']);
                } else {
                    $restaurantDeliveryPersons->where('restaurant_branch_id', $request->getAttribute('restaurantBranchId'));
                }
            } else {
                if ($authUser['role_id'] == \Constants\ConstUserTypes::SUPERVISOR) {
                    $restaurantSupervisor = Models\Supervisor::select('id')->where('user_id', $authUser['id'])->first();
                    $restaurantDeliveryPersons->where('restaurant_supervisor_id', $restaurantSupervisor['id']);
                }
            }
        }
        $restaurantDeliveryPersons = $restaurantDeliveryPersons->leftJoin('restaurants', 'restaurant_delivery_persons.restaurant_id', '=', 'restaurants.id');
        $restaurantDeliveryPersons = $restaurantDeliveryPersons->leftJoin('restaurants as restaurant_branches', 'restaurant_delivery_persons.restaurant_branch_id', '=', 'restaurant_branches.id');
        $restaurantDeliveryPersons = $restaurantDeliveryPersons->leftJoin('users', 'restaurant_delivery_persons.user_id', '=', 'users.id');
        $restaurantDeliveryPersons = $restaurantDeliveryPersons->select('restaurant_delivery_persons.*', 'restaurants.name as restaurant_name', 'restaurant_branches.name as restaurant_branch_name', 'users.username as user_username');
        $restaurantDeliveryPersons = $restaurantDeliveryPersons->paginate()->toArray();
        $data = $restaurantDeliveryPersons['data'];
        unset($restaurantDeliveryPersons['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $restaurantDeliveryPersons
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
    
})->add(new Acl\ACL('canListRestaurantDeliveryPerson'));
/**
 * POST restaurantDeliveryPersonsPost.
 * Summary: Create new delivery person.
 * Notes: Create new delivery person.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/restaurant_delivery_persons', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    //TODO
    if ($authUser->role_id == \Constants\ConstUserTypes::ADMIN) {
        if(isPluginEnabled('Order/OutsourcedDelivery') && empty($args['restaurant_id'])) {
            unset($args['restaurant_id']);
        }
    }
    if (empty($args['restaurant_branch_id'])) {
        unset($args['restaurant_branch_id']);
    }
    $user = new Models\User($args);
    $restaurantDeliveryPerson = new Models\DeliveryPerson;
    $validationErrorFields = $restaurantDeliveryPerson->validate($args);
    $validationErrorFields['unique'] = array();
    if (checkAlreadyUsernameExists($args['username'])) {
        array_push($validationErrorFields['unique'], 'username');
    }
    if (checkAlreadyEmailExists($args['email'])) {
        array_push($validationErrorFields['unique'], 'email');
    }
    if (checkAlreadyMobileExists($args['mobile'])) {
        array_push($validationErrorFields['unique'], 'mobile number');
    }
    if (empty($validationErrorFields['unique'])) {
        unset($validationErrorFields['unique']);
    }
    if (empty($validationErrorFields)) {
        $password = substr(md5($args['username']), 6, 6);
        $user->password = getCryptHash($password);
        $user->role_id = \Constants\ConstUserTypes::DELIVERYPERSON;
        $user->is_email_confirmed = 1;
        $user->is_active = 1;
        if ($authUser['role_id'] == \Constants\ConstUserTypes::SUPERVISOR) {
            $restaurantSupervisor = Models\Supervisor::select('restaurant_id', 'restaurant_branch_id', 'id')->where('user_id', $authUser['id'])->first();
            $args['restaurant_branch_id'] = $restaurantSupervisor['restaurant_branch_id'];
            $args['restaurant_supervisor_id'] = $restaurantSupervisor['id'];
            $args['restaurant_id'] = $restaurantSupervisor['restaurant_id'];
        }
        /** TODO Quick Fix For Admin End **/
        if ($authUser['role_id'] == \Constants\ConstUserTypes::ADMIN && empty($args['restaurant_id'])) {
            $restaurantSupervisor = Models\Supervisor::select('id')->where('user_id', $args['restaurant_supervisor_id'])->first();
            $args['restaurant_supervisor_id'] = !empty($restaurantSupervisor) ? $restaurantSupervisor->id : null;
        }
        unset($user->restaurant_id);
        unset($user->restaurant_supervisor_id);
        unset($user->restaurant_branch_id);
        try {
            $user->save();
            $restaurantDeliveryPerson->restaurant_id = !empty($args['restaurant_id']) ? $args['restaurant_id'] : null;
            $restaurantName = Models\Restaurant::select('name')->where('id', $restaurantDeliveryPerson->restaurant_id)->first();
            $restaurantDeliveryPerson->restaurant_branch_id = !empty($args['restaurant_branch_id']) ? $args['restaurant_branch_id'] : null;
            $restaurantDeliveryPerson->restaurant_supervisor_id = !empty($args['restaurant_supervisor_id']) ? $args['restaurant_supervisor_id'] : null;
            $restaurantDeliveryPerson->user_id = $user->id;
            $restaurantDeliveryPerson->is_active = 1;
            $restaurantDeliveryPerson->save();
            //$result = $restaurantDeliveryPerson;
            $result['data'] = $restaurantDeliveryPerson;
            $emailFindReplace = array(
                '##RESTAURANT_NAME##' => $restaurantName['name'],
                '##USERNAME##' => $user->username,
                '##PASSWORD##' => $password
            );
            //Send Deliveryperson Welcome Mail
            sendMail('deliverypersonwelcomemail', $emailFindReplace, $user->email);
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant delivery person could not be added. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateRestaurantDeliveryPerson'));
/**
 * Get restaurantDeliveryPersonsrestaurantDeliveryPersonIdGet
 * Summary: Get restaurant delivery person details by id
 * Notes:Get restaurant delivery person details
 * Output-Formats: [application/json]
 */
// _viewRestaurantDeliveryPerson 
$app->GET('/api/v1/restaurant_delivery_persons/{restaurantDeliveryPersonId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantDeliveryPerson = Models\DeliveryPerson::Filter($queryParams)->find($request->getAttribute('restaurantDeliveryPersonId'));
        unset($restaurantDeliveryPerson['data']);
        $result = array(
            'data' => $restaurantDeliveryPerson
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canViewRestaurantDeliveryPerson'));
/**
 * PUT restaurantDeliveryPersonsRestaurantDeliveryPersonIdPut
 * Summary: Updated Delivery Persons information
 * Notes: Updated Delivery Persons information
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurant_delivery_persons/{restaurantDeliveryPersonId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantDeliveryPerson = Models\DeliveryPerson::with('user')->where('id', $request->getAttribute('restaurantDeliveryPersonId'))->first();
    if (isset($args['restaurant_branch_id']) && is_null($args['restaurant_branch_id'])) {
        unset($args['restaurant_branch_id']);
    }
    $validationErrorFields = $restaurantDeliveryPerson->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $restaurantDeliveryPerson->fill($args);            
            $restaurantDeliveryPerson->save();
            $result['data'] = $restaurantDeliveryPerson->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant delivery person could not be updated. Please, try again.', $validationErrorFields, 1);
    }    
})->add(new Acl\ACL('canUpdateRestaurantDeliveryPerson'));
/**
 * DELETE restaurantDeliveryPersonsrestaurantDeliveryPersonIdDelete
 * Summary: DELETE delivery person
 * Notes: DELETE delivery person
 * Output-Formats: [application/json]
 */
// _deleteRestaurantDeliveryPerson 
$app->DELETE('/api/v1/restaurant_delivery_persons/{restaurantDeliveryPersonId}', function ($request, $response, $args) {
    $result = array();
    $delivery_person = Models\DeliveryPerson::find($request->getAttribute('restaurantDeliveryPersonId'));
    try {
        if ($delivery_person) {
            Models\User::where('id', $delivery_person->user_id)->update(array(
                "role_id" => \Constants\ConstUserTypes::USER
            ));
            $delivery_person->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Restaurant delivery person could not be deleted. Please, try again.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteRestaurantDeliveryPerson'));
/**
 * GET  RestaurantDeliveryPersonOrdersGet
 * Summary: Get delivery persons orders
 * Notes: Get delivery persons orders
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_delivery_person_orders', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantDeliveryPersonsOrders = Models\RestaurantDeliveryPersonOrder::Filter($queryParams)->paginate()->toArray();
        $data = $restaurantDeliveryPersonsOrders['data'];
        unset($restaurantDeliveryPersonsOrders['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $restaurantDeliveryPersonsOrders
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canListRestaurantDeliveryPesonOrder'));
/**
 * POST RestaurantDeliveryPersonsOrdersPost
 * Summary: Assign order to delivery person
 * Notes: Assign order to delivery person
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/restaurant_delivery_person_orders', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantDeliveryPersonOrder = new Models\RestaurantDeliveryPersonOrder;
    $validationErrorFields = $restaurantDeliveryPersonOrder->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $restaurantDeliveryPersonOrder->fill($args);
            $restaurantDeliveryPersonOrder->save();
            $result = $restaurantDeliveryPersonOrder->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Order could not be assigned to delivery person. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateRestaurantDeliveryPesonOrder'));
/**
 * GET  RestaurantDeliveryPersonOrdersRestaurantDeliveryPersonOrdersIdGet
 * Summary: Get delivery persons orders
 * Notes: Get delivery persons orders
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_delivery_person_orders/{restaurantDeliveryPersonOrderId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $restaurantDeliveryPersonOrder = Models\RestaurantDeliveryPersonOrder::Filter($queryParams)->find($request->getAttribute('providerId'));
    if (!empty($restaurantDeliveryPersonOrder)) {
        $result['data'] = $restaurantDeliveryPersonOrder->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
})->add(new Acl\ACL('canViewRestaurantDeliveryPesonOrder'));
/**
 * PUT   RestaurantDeliveryPersonOrdersRestaurantDeliveryPersonOrdersIdPut
 * Summary: Update delivery persons orders
 * Notes: Update delivery persons orders
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurant_delivery_person_orders/{restaurantDeliveryPersonOrderId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantDeliveryPersonOrder = Models\RestaurantDeliveryPersonOrder::find($request->getAttribute('restaurantDeliveryPersonOrderId'));
    $validationErrorFields = $restaurantDeliveryPersonOrder->validate($args);
    if (empty($validationErrorFields)) {
        $restaurantDeliveryPersonOrder->fill($args);
        //Update to order status when is_delivered is 1
        if (!empty($args['is_delivered']) && $args['is_delivered'] == 1) {
            $order = Models\Order::where('id', $args['order_id'])->first();
            $order->order_status_id = \Constants\OrderStatus::DELIVERED;
            $order->update();
        }
        try {
            $restaurantDeliveryPersonOrder->save();
            $result['data'] = $restaurantDeliveryPersonOrder->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Order status could not be updated. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateRestaurantDeliveryPesonOrder'));