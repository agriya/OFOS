'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersAddresses
 * @description
 * # usersAddresses
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('usersAddresses', function($resource) {
        return $resource('/api/v1/user_addresses', {}, {
            create: {
                method: 'POST'
            },
            get: {
                method: 'GET'
            }
        });
    })