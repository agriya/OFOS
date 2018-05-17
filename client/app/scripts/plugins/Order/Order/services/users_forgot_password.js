'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersForgotPassword
 * @description
 * # usersForgotPassword
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('usersForgotPassword', function($resource) {
        return $resource('/api/v1/users/forgot_password', {}, {
            forgetPassword: {
                method: 'POST'
            }
        });
    });