'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersSettingsController
 * @description
 * # UsersSettingsController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UsersSettingsController', function ($rootScope, userSettings, flash, $filter, countries) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Users Settings");
        vm.save_btn = false;
        var params = {};
        params.filter = '{"fields":{"phone":true,"iso2":true,"id":true},"limit":"all","skip":0}';
        countries.get(params, function (response) {
            if (angular.isDefined(response.data)) {
                vm.countries = response.data;
            }
        });
        vm.save = function () {
            if (vm.userSettings.$valid && !vm.save_btn) {
                vm.save_btn = true;
                vm.usersSettings.id = $rootScope.user.id;
                userSettings.update(vm.usersSettings, function (response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        flash.set($filter("translate")("User Profile has been updated."), 'success', false);
                    } else {
                        flash.set($filter("translate")("User Profile could not be updated. Please try again."), 'error', false);
                    }
                    vm.save_btn = false;
                }, function (error) {
                    vm.save_btn = false;
                    var errorResponse = error.data.error;
                    if (errorResponse.fields.mobile) {
                        flash.set($filter("translate")(errorResponse.fields.mobile), 'error', false);
                    }
                });
            }
        };
        vm.index = function () {
             vm.loader = true;
            var params = {};
            params.id = $rootScope.user.id;
            params.filter = '{"fields":{"first_name":true,"last_name":true,"mobile_code":true,"mobile":true}}';
            userSettings.get(params, function (response) {
                vm.usersSettings = response.data;
             vm.loader = false;
          });
        };
        vm.index();
    });