'use strict';
/**
 * @ngdoc service
 * @name ofosApp.moneyTransferAccount
 * @description
 * # moneyTransferAccount
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Common.Withdrawal')
    .factory('moneyTransferAccounts', function($resource) {
        return $resource('/api/v1/money_transfer_accounts', {}, {
            get: {
                method: 'GET'
            },
            save: {
                method: 'POST'
            }
        });
    });