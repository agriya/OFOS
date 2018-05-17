'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantTiming
 * @description
 * # restaurantTiming
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantTiming', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_timings', {}, {
            get: {
                method: 'GET'
            }
        });
}]);