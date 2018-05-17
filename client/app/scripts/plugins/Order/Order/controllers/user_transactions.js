'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UserTransactionsController
 * @description
 * # UserTransactionsController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UserTransactionsController', function($rootScope, UserTransactions, flash, $filter) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Transactions");
        vm.index = function() {
            vm.maxSize = 5; 
            vm.currentPage = 1;                
            vm.getTransaction();
        };
        vm.getTransaction = function() {           
            vm.loader = true;
            vm.limit = 10;              
            vm.skip = (vm.currentPage - 1) * vm.limit;
            var params = {};
            params.filter = '{"where":{"user_id":'+$rootScope.user.id+'},"include":{"0":"user","1":"transaction_type","2":"restaurant","3":"other_user","4":"order"},"skip":'+vm.skip+',"limit":'+vm.limit+'}';        
                UserTransactions.get(params, function(response) {
                    if (angular.isDefined(response._metadata)) {
                        vm.currentPage = response._metadata.current_page;
                        vm.totalItems = response._metadata.total;
                        vm.itemsPerPage = response._metadata.per_page;
                        vm.noOfPages = response._metadata.last_page;
                    }
                    if (angular.isDefined(response.data)) {
                        vm.transactionsList = response.data;
                    }
                    vm.loader = false;
                });
            };
            vm.paginate = function() {            
                vm.getTransaction();                                             
            };
        vm.index();
    });
    