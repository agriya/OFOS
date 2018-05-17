'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantBranches
 * @description
 * # restaurantBranches
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantBranches', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_branches', {}, {
            get: {
                method: 'GET'
            }
        });
}]);