'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersAddress
 * @description
 * # usersAddress
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('usersAddress', function($resource) {
        return $resource('/api/v1/user_addresses/:user_address_id', {}, {
            remove: {
                method: 'DELETE',
                params: {
                    address_id: '@user_address_id'
                }
            }
        });
    });