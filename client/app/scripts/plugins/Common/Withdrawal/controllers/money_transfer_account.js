'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:MoneyTransferAccountController
 * @description
 * # MoneyTransferAccountController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Common.Withdrawal')
    .controller('MoneyTransferAccountController', function($rootScope, moneyTransferAccounts, moneyTransferAccount, flash, $filter, $state) {
        var vm = this;
        vm.loader = true;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Money Transfer Accounts");
        vm.index = function() {
            vm.loader = true;
            var params = {};
            params.filter = {"where":{"user_id":$rootScope.user.id}};
            moneyTransferAccounts.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.moneyTransferAccLists = response.data;
                }
                vm.loader = false;
            });
        };
        vm.MoneyTransferAccSubmit = function($valid) {
            if ($valid) {
                var params = {};
                params.account = vm.account;
                params.is_primary = true;
                moneyTransferAccounts.save(params, function(response) {
                    vm.response = response;
                    $state.reload();
                    flash.set($filter("translate")("Account Added successfully"), 'success', true);
                }, function() {
                    flash.set($filter("translate")("Account could not be added"), 'error', false);
                });
            }
        };
        vm.MoneyTransferAccDelete = function(id) {
            var param = {};
            param.money_transfer_account_id = id;
            moneyTransferAccount.delete(param, function(response) {
                vm.response = response;
                if (vm.response.error.code === 0) {
                    $state.reload();
                    flash.set($filter("translate")("Account deleted successfully."), 'success', false);
                } else {
                    flash.set($filter("translate")("Account could not be deleted."), 'error', false);
                }
            });
        };
        vm.index();
    });