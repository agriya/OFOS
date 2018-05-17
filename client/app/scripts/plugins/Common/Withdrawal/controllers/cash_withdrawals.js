'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:CashWithdrawalsController
 * @description
 * # CashWithdrawalsController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Common.Withdrawal')
    .controller('CashWithdrawalsController', function($rootScope, cashWithdrawals, moneyTransferAccount, flash, $filter, $state) {
        var vm = this;
         vm.loader = true;
        /*jshint -W117 */
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Cash Withdrawals");
        vm.minimum_withdraw_amount = $rootScope.settings.USER_MINIMUM_WITHDRAW_AMOUNT;
        vm.maximum_withdraw_amount = $rootScope.settings.USER_MAXIMUM_WITHDRAW_AMOUNT;
        vm.user_available_balance = $rootScope.user.available_wallet_amount;
        vm.account_error = false; 
        vm.index = function() {
            vm.maxSize = 5; 
            vm.currentPage = 1;    
            vm.getCashWithdrawal();
        };       
        vm.getCashWithdrawal = function() {   
            vm.loader = true;
            vm.limit = 10    
            vm.skip = (vm.currentPage - 1) * vm.limit; 
            var params = {}; 
             params.filter =  '{"include":{"0":"money_transfer_account"},"where":{"user_id":'+$rootScope.user.id+'},"skip":'+vm.skip+',"limit":'+vm.limit+'}';          
            cashWithdrawals.get(params, function(response) {
                if (angular.isDefined(response._metadata)) {
                    vm.currentPage = response._metadata.current_page;
                    vm.totalItems = response._metadata.total;
                    vm.itemsPerPage = response._metadata.per_page;
                    vm.noOfPages = response._metadata.last_page;
                }
                if (angular.isDefined(response.data)) {
                    vm.cashWithdrawalsList = response.data;
                }
            });
            params.filter = '{where:{"user_id":'+$rootScope.user.id+'},"skip":'+vm.skip+',"limit":'+vm.limit+'}';
            moneyTransferAccount.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.moneyTransferList = response.data;
                }
            });
             vm.loader = false;
        };
        vm.selectedAcc = function(id) {
            vm.account_id = id;
            vm.account_error = false;
        };
        vm.userCashWithdrawSubmit = function($valid) {
            if (angular.isUndefined(vm.account_id)) {
                vm.account_error = true;
            } else {
                vm.account_error = false;
            }
            if ($valid && vm.account_error === false) {
                vm.amount = parseFloat(angular.element('#amount')
                    .val());
                if (parseFloat(vm.user_available_balance) > parseFloat(vm.amount)) {
                    params.amount = vm.amount;
                    params.money_transfer_account_id = vm.account_id;
                    params.remark = "";
                    cashWithdrawals.save(params, function(response) {
                        if (response.error.code === 0) {
                            $state.reload();
                            flash.set($filter("translate")("Your request submitted successfully."), 'success', true);
                        }
                    }, function() {
                        flash.set($filter("translate")("Withdraw request could not be added"), 'error', false);
                    });
                } else {
                    flash.set("You Dont have sufficient amount in your wallet.", "error", false);
                }
            }
        };
        vm.paginate = function() {	           
            vm.getCashWithdrawal();                                       
        };
        vm.index();
    });