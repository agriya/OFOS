'use strict';
/**
 * @ngdoc service
 * @name ofosApp.cashWithdrawals
 * @description
 * # cashWithdrawals
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Common.Withdrawal')
    .factory('cashWithdrawals', function($resource) {
        return $resource('/api/v1/user_cash_withdrawals',{filter:'@filter'}, {}, {
            get: {
                method: 'GET'
            },
            save: {
                method: 'POST'
            }
        });
    });