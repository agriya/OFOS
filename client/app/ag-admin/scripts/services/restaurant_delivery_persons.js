'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantDeliveryPersons
 * @description
 * # restaurantDeliveryPersons
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantDeliveryPersons', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_delivery_persons', {}, {
            get: {
                method: 'GET'
            }
        });
}]);