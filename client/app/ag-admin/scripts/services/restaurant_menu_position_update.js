'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantMenuPositionUpdate
 * @description
 * # restaurantMenuPositionUpdate
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantMenuPositionUpdate', ['$resource', function($resource) {
        return $resource('/api/v1/restaurants/:id/update_position', {}, {
            updatePosition: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            }
        });
}]);