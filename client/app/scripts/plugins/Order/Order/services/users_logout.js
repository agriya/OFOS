'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersLogout
 * @description
 * # usersLogout
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('usersLogout', function($resource) {
        return $resource('/api/v1/users/logout', {}, {
            logout: {
                method: 'GET'
            }
        });
    });