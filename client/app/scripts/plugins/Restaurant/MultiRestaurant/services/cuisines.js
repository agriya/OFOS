'use strict';
/**
 * @ngdoc service
 * @name ofosApp.cuisines
 * @description
 * # cuisines
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .factory('cuisines', function($resource) {
        return $resource('/api/v1/cuisines',{ filter: '@filter'},{}, {
            get: {
                method: 'GET'
            }
        });
    });