'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantCategories
 * @description
 * # restaurantCategories
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantSupervisors', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_supervisors', {}, {
            get: {
                method: 'GET'
            }
        });
}]);