'use strict';
/**
 * @ngdoc service
 * @name ofosApp.checkout
 * @description
 * # checkout
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('checkout', function($resource) {
        return $resource('/api/v1/checkout?cookie_id=:cookie_id&user_id=:user_id', {}, {
            create: {
                method: 'POST',
                params: {
                    cookie_id: '@cookie_id',
                    user_id: '@user_id'
                }
            }
        });
    });