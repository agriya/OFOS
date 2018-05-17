'use strict';
/**
 * @ngdoc service
 * @name ofosApp.restaurantReviews
 * @description
 * # restaurantReviews
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .factory('restaurantReviews', function($resource) {
        return $resource('/api/v1/restaurant_reviews',{filter:'@filter'}, {}, {
            get: {
                method: 'GET'
            }
        });
    });