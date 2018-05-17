'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersAddressesController
 * @description
 * # UsersAddressesController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UsersAddressesController', function($rootScope, usersAddresses, usersAddress, flash, $filter) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Users Addresses");
        vm.index = function() {
            vm.loader = true;
            var params = {};           
            params.filter = '{"where":{"user_id":'+ $rootScope.user.id +'},"skip":0,"limit":"all"}'
            usersAddresses.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.addresses = response.data;
                }
                vm.loader = false;
            });
        };
        vm.deleteAddress = function(id, index) {
            vm.addresses.splice(index, 1);
            var params = {};
            params.user_address_id = id;                         
            usersAddress.remove(params, function(response) {                
                vm.response = response;                
                if (vm.response.error.code === 0) {
                    flash.set($filter("translate")("User address deleted successfully."), 'success', false);
                } else {
                    flash.set($filter("translate")("User address could not be deleted."), 'error', false);
                }
            });
        };
        vm.index();
    });