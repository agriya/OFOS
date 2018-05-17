'use strict';
/**
 * @ngdoc service
 * @name ofosApp.UserTransactions
 * @description
 * # UserTransactions
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('countries', function($resource) {
        return $resource('/api/v1/countries', {}, {
            get: {
                method: 'GET'
            }
        });
    });