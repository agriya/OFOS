'use strict';
/**
 * @ngdoc service
 * @name ofosApp.restaurantMenu
 * @description
 * # restaurantMenu
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .factory('restaurantMenu', function($resource) {
        return $resource('/api/v1/restaurant_menus',{ filter: '@filter'}, {}, {
            get: {
                method: 'GET'
            }
        });
    });