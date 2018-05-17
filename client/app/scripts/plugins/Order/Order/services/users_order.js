'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersOrder
 * @description
 * # usersOrder
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('usersOrder', function($resource) {
        return $resource('/api/v1/orders/:order_id', {}, {
            update: {
                method: 'PUT',
                params: {
                    order_id: '@order_id'
                }
            }
        });
    });