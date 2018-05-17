'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantCategoryPositionUpdate
 * @description
 * # restaurantCategoryPositionUpdate
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantCategoryPositionUpdate', ['$resource', function($resource) {
        return $resource('/api/v1/restaurants/:id/category_update_position', {}, {
            updatePosition: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            }
        });
}]);