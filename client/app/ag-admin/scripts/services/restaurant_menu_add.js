'use strict';
/**
 * @ngdoc service
 * @name ofos.restaurantMenuAdd
 * @description
 * # restaurantMenuAdd
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('restaurantMenuAdd', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_menus', {}, {
            add: {
                method: 'POST'
            }
        });
}]);