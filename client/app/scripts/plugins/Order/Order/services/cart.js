'use strict';
/**
 * @ngdoc service
 * @name ofosApp.deleteCart
 * @description
 * # deleteCart
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('cart', function($resource) {
        return $resource('/api/v1/carts/:cart_id?cookie_id=:cookie_id', {}, {
            remove: {
                method: 'DELETE',
                params: {
                    cart_id: '@cart_id',
                    cookie_id: '@cookie_id'
                }
            },
            update: {
                method: 'PUT',
                params: {
                    cart_id: '@cart_id',
                    cookie_id: '@cookie_id'
                }
            }
        });
    });