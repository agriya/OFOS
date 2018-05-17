'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersRestaurantsReviews
 * @description
 * # usersRestaurantsReviews
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Review')
    .factory('usersRestaurantsReviews', function($resource) {
        return $resource('/api/v1/restaurant_reviews',{filter:'@filter'}, {}, {
            get: {
                method: 'GET'
            }
        });
    });