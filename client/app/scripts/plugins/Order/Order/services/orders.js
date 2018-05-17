'use strict';
/**
 * @ngdoc service
 * @name ofosApp.orders
 * @description
 * # orders
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('orders', function ($resource) {
        return $resource('/api/v1/orders', { filter: '@filter' }, {}, {
            get: {
                method: 'GET'
            }
        });
    })
    .factory('orders', function ($resource) {
        return $resource('/api/v1/orders/:orderId', {}, {
            put: {
                method: 'PUT',
                params: {
                    orderId: '@orderId'
                }
            }
        });
    }).factory('orderGet', function ($resource) {
        return $resource('/api/v1/orders/:orderId/track', {filter: '@filter'}, {
            get: {
                method: 'GET',
                params: {
                    orderId: '@orderId'
                }
            }
        });
    });