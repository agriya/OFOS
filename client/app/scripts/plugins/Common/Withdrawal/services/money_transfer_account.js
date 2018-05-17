'use strict';
/**
 * @ngdoc service
 * @name ofosApp.moneyTransferAccount
 * @description
 * # moneyTransferAccount
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Common.Withdrawal')
    .factory('moneyTransferAccount', function($resource) {
        return $resource('/api/v1/money_transfer_accounts/:money_transfer_account_id', {}, {
            get: {
                method: 'GET',
                params: {
                    money_transfer_account_id: '@money_transfer_account_id'
                }
            },
            delete: {
                method: 'DELETE',
                params: {
                    money_transfer_account_id: '@money_transfer_account_id'
                }
            }
        });
    });