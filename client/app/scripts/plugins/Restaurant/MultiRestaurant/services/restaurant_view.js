'use strict';
/**
 * @ngdoc service
 * @name ofosApp.restaurantView
 * @description
 * # restaurantView
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .factory('restaurantView', function($resource) {
        return $resource('/api/v1/restaurants/:id',{filter:'@filter'},{}, {
            get: {
                method: 'GET',
                params: {
                    id: '@id'
                }
            }
        });
    });