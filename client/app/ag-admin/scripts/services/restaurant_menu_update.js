'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantMenuUpdate
 * @description
 * # restaurantMenuUpdate
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantMenuUpdate', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_menus/:id', {}, {
            update: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            },
            delete: {
                method: 'DELETE',
                params: {
                    id: '@id'
                }
            }
        });
}]);