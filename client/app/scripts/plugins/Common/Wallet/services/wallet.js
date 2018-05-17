'use strict';
/**
 * @ngdoc service
 * @name ofosApp.wallet
 * @description
 * # wallet
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Common.Wallet')
    .factory('wallet', function($resource) {
        return $resource('/api/v1/wallets', {}, {
            create: {
                method: 'POST'
            }
        });
    });