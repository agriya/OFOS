'use strict';
angular.module('ofosApp.Restaurant.SingleRestaurant').factory('restaurants', function($resource) {
        return $resource('/api/v1/restaurants',{ filter: '@filter'}, {}, {
            get: {
                method: 'GET' 
            }
        });
    });