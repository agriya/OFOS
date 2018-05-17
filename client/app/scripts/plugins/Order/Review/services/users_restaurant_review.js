'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersRestaurantReview
 * @description
 * # usersRestaurantReview
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Review')
    .factory('usersRestaurantReview', function($resource) {
        return $resource('/api/v1/restaurant_reviews', {}, {
            create: {
                method: 'POST',
                params: {
                    user_id: '@user_id'
                }
            }
        });
    });