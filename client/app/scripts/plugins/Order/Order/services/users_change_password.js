'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersChangePassword
 * @description
 * # usersChangePassword
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('usersChangePassword', function($resource) {
        return $resource('/api/v1/users/change_password', {}, {
            changePassword: {
                method: 'PUT'
            }
        });
    });