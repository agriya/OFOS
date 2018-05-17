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
 * DELETE couponsCouponIdDelete
 * Summary: Delete coupon
 * Notes: Deletes a single coupon based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/coupons/{couponId}', function($request, $response, $args) {
	$coupon = Models\Coupon::find($request->getAttribute('couponId'));
	$result = array();
	try {
		if (!empty($coupon)) {
			$coupon->delete();
			$result = array(
				'status' => 'success',
			);
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'No record found', '', 1);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'Coupon could not be deleted. Please, try again.', '', 1);
	}
})->add(new Acl\ACL('canDeleteCoupon'));


/**
 * GET couponsCouponIdGet
 * Summary: Fetch coupon
 * Notes: Returns a coupon based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/coupons/{couponId}', function($request, $response, $args) {
	$result = array();
    $queryParams = $request->getQueryParams();
    try {
        $coupon = Models\Coupon::Filter($queryParams)->find($request->getAttribute('couponId'));
        if (!empty($coupon)) {
            $result['data'] = $coupon;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch(Exception $e) {
		return renderWithJson($result, 'No record found', '', 1, 422);
	}
});


/**
 * PUT couponsCouponIdPut
 * Summary: Update coupon by its id
 * Notes: Update coupon by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/coupons/{couponId}', function($request, $response, $args) {
    global $authUser;
	$args = $request->getParsedBody();
	$coupon = Models\Coupon::find($request->getAttribute('couponId'));
	$result = array();
	try {
        if (!empty($coupon)) {
            $coupon->fill($args);
            $validationErrorFields = $coupon->validate($args);
            if (empty($validationErrorFields)) {
                $coupon->save();
                $result = $coupon->toArray();
                return renderWithJson($result);                
            } else {
                return renderWithJson($result, 'Coupon could not be updated. Please, try again.', $validationErrorFields, 1, 422);
            }
        } else {
            return renderWithJson($result, 'Coupon could not be updated. Please, try again.', '', 1, 404);
        }
	} catch(Exception $e) {
		return renderWithJson($result, 'Coupon could not be updated. Please, try again.', '', 1, 422);
	}
})->add(new Acl\ACL('canUpdateCoupon'));


/**
 * GET couponsGet
 * Summary: Fetch all coupons
 * Notes: Returns all coupons from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/coupons', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$coupons = Models\Coupon::Filter($queryParams)->paginate()->toArray();
		$data = $coupons['data'];
		unset($coupons['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $coupons
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
	}
});


/**
 * POST couponsPost
 * Summary: Creates a new coupon
 * Notes: Creates a new coupon
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/coupons', function($request, $response, $args) {
	$args = $request->getParsedBody();
	$coupon = new Models\Coupon($args);
	$result = array();
	try {
		$validationErrorFields = $coupon->validate($args);
		if (empty($validationErrorFields)) {
			$coupon->save();
			$result = $coupon->toArray();
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'Coupon could not be added. Please, try again.', $validationErrorFields, 1);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'Coupon could not be added. Please, try again.', '', 1, 422);
	}
})->add(new Acl\ACL('canCreateCoupon'));

/**
 * GET couponsGetStatusCouponCodeGet
 * Summary: Fetch coupon
 * Notes: Returns a coupon based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/coupons/get_status/{couponCode}', function ($request, $response, $args) {    
    $result = array();
    $queryParams = $request->getQueryParams();
    $coupon = Models\Coupon::verifyAndCouponCode($request->getAttribute('couponCode'));
    return renderWithJson($coupon);    
});