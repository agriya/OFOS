'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersAddressAddController
 * @description
 * # UsersAddressAddController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UsersAddressAddController', function($rootScope, flash, $timeout, $location, $filter, usersAddresses) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Users Addresses Add");
        vm.save_btn = false;
        vm.address = {};
        vm.address.city = {};
        vm.address.state = {};
        vm.address.country = {};
        vm.place = null;
        vm.autocompleteOptions = {
            types: ['cities']
        };
        vm.location = function() {
            var k = 0;
            angular.forEach(vm.place.address_components, function(value, key) {
                //jshint unused:false
                if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                    if (k === 0) {
                        vm.address.city.name = value.long_name;
                        vm.disable_city = true;
                    }
                    if (value.types[0] === 'locality') {
                        k = 1;
                    }
                }
                if (value.types[0] === 'administrative_area_level_1') {
                    vm.address.state.name = value.long_name;
                    vm.disable_state = true;
                }
                if (value.types[0] === 'country') {
                    vm.address.country.iso2 = value.short_name;
                    vm.disable_country = true;
                }
                if (value.types[0] === 'postal_code') {
                    vm.address.zip_code = parseInt(value.long_name);
                    vm.disable_zip = true;
                }
                vm.address.latitude = vm.place.geometry.location.lat();
                vm.disable_latitude = true;
                vm.address.longitude = vm.place.geometry.location.lng();
                vm.disable_longitude = true;
            });
        };
        vm.save = function() {
            if (vm.userAddress.$valid && !vm.save_btn) {
                vm.save_btn = true;
                vm.address.user_id = $rootScope.user.id;
                if (vm.place !== null) {
                    vm.address.latitude = vm.place.geometry.location.lat();
                    vm.address.longitude = vm.place.geometry.location.lng();
                }
                usersAddresses.create(vm.address, function(response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        flash.set($filter("translate")("User address added successfully."), 'success', false);
                        $timeout(function() {
                            $location.path('/users/addresses');
                        }, 1000);
                    } else {
                        flash.set($filter("translate")("User address could not be added."), 'error', false);
                        vm.save_btn = false;
                    }
                });
            }
        };
    });