'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersRegister
 * @description
 * # usersRegister
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('usersRegister', function($resource) {
        return $resource('/api/v1/users/register', {}, {
            create: {
                method: 'POST'
            }
        });
    });