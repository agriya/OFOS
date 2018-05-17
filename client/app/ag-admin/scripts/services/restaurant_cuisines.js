'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantCategories
 * @description
 * # restaurantCategories
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantCuisines', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_cuisines', {}, {
            get: {
                method: 'GET'
            }
        });
}]);