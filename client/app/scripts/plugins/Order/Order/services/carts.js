'use strict';
/**
 * @ngdoc service
 * @name ofosApp.cart
 * @description
 * # cart
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('carts', function($resource) {
        return $resource('/api/v1/carts', {}, {
            create: {
                method: 'POST'
            }
        });
    });