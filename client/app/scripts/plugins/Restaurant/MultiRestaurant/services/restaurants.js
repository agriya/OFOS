'use strict';
/**
 * @ngdoc service
 * @name ofosApp.restaurants
 * @description
 * # restaurants
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .factory('restaurants', function($resource) {
        return $resource('/api/v1/restaurants',{ filter: '@filter'}, {}, {
            get: {
                method: 'GET'
            }
        });
    });