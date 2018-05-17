'use strict';
/**
 * @ngdoc service
 * @name ofosApp.userActivation
 * @description
 * # userActivation
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('userActivation', function($resource) {
        return $resource('/api/v1/users/:user_id/activation/:hash', {}, {
            activation: {
                method: 'PUT',
                params: {
                    user_id: '@user_id',
                    hash: '@hash'
                }
            }
        });
    });