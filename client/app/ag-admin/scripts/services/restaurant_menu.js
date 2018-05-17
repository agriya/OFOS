'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantMenu
 * @description
 * # restaurantMenu
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantMenu', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_menus', {}, {
            get: {
                method: 'GET'
            }
        });
}]);