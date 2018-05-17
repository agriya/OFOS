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
 * GET restaurantSupervisorsGet
 * Summary: Get supervisors list
 * Notes:  Get supervisors list
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_supervisors', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantSupervisors = Models\Supervisor::Filter($queryParams)->paginate()->toArray();
        $data = $restaurantSupervisors['data'];
        unset($restaurantSupervisors['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $restaurantSupervisors
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canListRestaurantSupervisor'));
/**
 * POST restaurantSupervisorsPost
 * Summary: Create supervisors based on corresponding restaurant
 * Notes: Create supervisors based on corresponding restaurant.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/restaurant_supervisors', function ($request, $response, $args) {
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
    $restaurantSupervisor = new Models\Supervisor;
    $validationErrorFields = $restaurantSupervisor->validate($args);
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
        $user->role_id = \Constants\ConstUserTypes::SUPERVISOR;
        $user->is_email_confirmed = 1;
        $user->is_active = 1;
        $password = substr(md5($args['username']), 6, 6);
        $user->password = getCryptHash($password);
        unset($user->restaurant_branch_id);
        unset($user->restaurant_id);
        unset($user->city);
        unset($user->state);
        unset($user->country);
        try {
            $user->save();
            if (!empty($args['restaurant_id'])) {
                $restaurantSupervisor->restaurant_id = $args['restaurant_id'];
            }
            $restaurantName = Models\Restaurant::select('name')->where('id', $restaurantSupervisor->restaurant_id)->first();
            $restaurantSupervisor->restaurant_branch_id = !empty($args['restaurant_branch_id']) ? $args['restaurant_branch_id'] : NULL;
            $restaurantSupervisor->user_id = $user->id;
            $restaurantSupervisor->is_active = 1;
            $restaurantSupervisor->save();
            $result['data'] = $restaurantSupervisor;
            $emailFindReplace = array(
                '##RESTAURANT_NAME##' => $restaurantName['name'],
                '##USERNAME##' => $user->username,
                '##PASSWORD##' => $password,
            );
            //Send Supervisor Welcome Mail
            sendMail('supervisorwelcomemail', $emailFindReplace, $user->email);
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), $e->getMessage(), 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant supervisor could not be added. Please, try again', $validationErrorFields, 1);
    }    
})->add(new Acl\ACL('canCreateRestaurantSupervisor'));
/**
 * GET restaurantSupervisorsRestaurantIdSupervisorsIdGet
 * Summary: Get user supervisors details
 * Notes: Get user supervisors details. \n
 * Output-Formats: [application/json]
 */
// _viewRestaurantSupervisor 
$app->GET('/api/v1/restaurant_supervisors/{restaurantSupervisorId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantSupervisor = Models\Supervisor::Filter($queryParams)->find($request->getAttribute('restaurantSupervisorId'));
        if (!empty($restaurantSupervisor)) {
            unset($restaurantSupervisor['data']);
            $result = array(
                'data' => $restaurantSupervisor
            );
            return renderWithJson($result);
        }else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canViewRestaurantSupervisor'));
/**
 * PUT restaurantSupervisorsRestaurantSupervisorsIdPut
 * Summary: Updated supervisors information
 * Notes: Updated supervisors information\n
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurant_supervisors/{restaurantSupervisorId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantSupervisor = Models\Supervisor::with('user')->where('id', $request->getAttribute('restaurantSupervisorId'));
    $validationErrorFields = $restaurantSupervisor->validate($args);
    $supervisor = $restaurantSupervisor->toArray();
    if (empty($validationErrorFields)) {
        try {
            $supervisorCount = Models\DeliveryPerson::where('restaurant_supervisor_id', $request->getAttribute('restaurantSupervisorId'))->count();
            if ((!empty($supervisorCount) && !empty($args['restaurant_branch_id']) && ($supervisor['restaurant_branch_id'] == $args['restaurant_branch_id'])) || $supervisorCount == 0) {
                    $restaurantSupervisor->fill($args);
                    $restaurantSupervisor->save();
                    $result['data'] = $restaurantSupervisor->toArray();
                    return renderWithJson($result);
            }  else {
                return renderWithJson($result, 'Restaurant supervisor could not be updated.Restaurant supervisor have delivery persons.', '', 1);
            }
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant supervisor not be updated. Please, try again.', $validationErrorFields, 1);
    }    
})->add(new Acl\ACL('canUpdateRestaurantSupervisor'));
/**
 * DELETE restaurantSupervisorsRestaurantSupervisorIdDELETE
 * Summary: Delete Supervisor
 * Notes: Delete Supervisor
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/restaurant_supervisors/{restaurantSupervisorId}', function ($request, $response, $args) {
    $result = array();
    $supervisor = Models\Supervisor::find($request->getAttribute('restaurantSupervisorId'));    
    try {
        if ($supervisor) {
            Models\User::where('id', $supervisor->user_id)->update(array(
                "role_id" => \Constants\ConstUserTypes::USER
            ));
            $supervisor->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Restaurant Supervisor could not be deleted. Please, try again.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canDeleteRestaurantSupervisor'));