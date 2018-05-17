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
 * GET RestaurantReviewsGet
 * Summary: Get  restaurant reviews
 * Notes: Get  Filter  restaurant reviews
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_reviews', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $restaurantReviews = Models\RestaurantReview::Filter($queryParams)->paginate()->toArray();
        $data = $restaurantReviews['data'];
        unset($restaurantReviews['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $restaurantReviews
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), $fields = $e->getMessage(), 1);
    }
});
/**
 * POST RestaurantReviewsPost.
 * Summary: Create New restaurant reviews.
 * Notes: Create New restaurant reviews.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/restaurant_reviews', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantReview = new Models\RestaurantReview($args);
    $validationErrorFields = $restaurantReview->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $restaurantReview->fill($args);
            $restaurantReview->save();
            $order = Models\Order::where('id', $restaurantReview->order_id)->first();
            $order->order_status_id = \Constants\OrderStatus::REVIEWED;
            $order->update();
            $result = array(
                'data' => 'Success'
            );
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant review could not be added. Please, try again', $validationErrorFields, 1);
    }    
})->add(new Acl\ACL('canCreateRestaurantReview'));
/**
 * GET restaurantReviewsrestaurantReviewIdGet
 * Summary: Get  particular restaurant review.
 * Notes: Get  particular restaurant review.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_reviews/{restaurantReviewId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantReview = Models\RestaurantReview::Filter($queryParams)->find($request->getAttribute('restaurantReviewId'));
        if(!empty($restaurantReview)){
            $result['data'] = $restaurantReview->toArray();        
            return renderWithJson($result);
        }else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
});
/**
 * PUT RestaurantReviewPUT
 * Summary: Update RestaurantReview by admin
 * Notes: Update RestaurantReview by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurant_reviews/{restaurantReviewId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurant_review = Models\RestaurantReview::find($request->getAttribute('restaurantReviewId'));
    $validationErrorFields = $restaurant_review->validate($args);
    if (empty($validationErrorFields)) {
        $restaurant_review->fill($args);
        try {
            $restaurant_review->save();
            $result['data'] = $restaurant_review->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant review could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateRestaurantReview'));
/**
 * DELETE RestaurantReviewsrestaurantReviewIdDelete
 * Summary: DELETE particular restaurant review
 * Notes: DELETE particular restaurant review
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/restaurant_reviews/{restaurantReviewId}', function ($request, $response, $args) {
    $result = array();
    $restaurantReview = Models\RestaurantReview::find($request->getAttribute('restaurantReviewId'));
    try {
        if ($restaurantReview) {
            $restaurantReview->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Restaurant review could not be deleted. Please, try again.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canDeleteRestaurantReview'));
