'use strict';
/**
 * @ngdoc service
 * @name ofosApp.UserTransactions
 * @description
 * # UserTransactions
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('UserTransactions', function($resource) {
        return $resource('/api/v1/transactions',{filter:'@filter'}, {}, {
            get: {
                method: 'GET'
            }
        });
    });