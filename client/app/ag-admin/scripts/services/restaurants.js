'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurants
 * @description
 * # restaurants
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurants', ['$resource', function($resource) {
        return $resource('/api/v1/restaurants', {}, {
            get: {
                method: 'GET'
            }
        });
  }]);