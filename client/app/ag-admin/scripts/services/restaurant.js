'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurant
 * @description
 * # restaurant
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurant', ['$resource', function($resource) {
        return $resource('/api/v1/restaurants/:id', {}, {
            update: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            }
        });
}]);