'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantCategories
 * @description
 * # restaurantCategories
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantCategories', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_categories', {}, {
            get: {
                method: 'GET'
            }
        });
}]);