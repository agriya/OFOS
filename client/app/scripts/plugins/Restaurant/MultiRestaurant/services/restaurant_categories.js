'use strict';
/**
 * @ngdoc service
 * @name ofosApp.restaurantCategories
 * @description
 * # restaurantCategories
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .factory('restaurantCategories', function($resource) {
        return $resource('/api/v1/restaurant_categories',{ filter: '@filter'}, {}, {
            get: {
                method: 'GET'
            }
        });
    });