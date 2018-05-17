'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersChangePasswordController
 * @description
 * # UsersChangePasswordController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UsersChangePasswordController', function($rootScope, $location, flash, usersChangePassword, $filter, $cookies, $scope) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Change Password");
        vm.loader = true;
        vm.save_btn = false;
        vm.save = function() {
            if (vm.userChangePassword.$valid && !vm.save_btn) {
                vm.save_btn = true;
                vm.changePassword.id = $rootScope.user.id;
                delete vm.changePassword.repeat_password;
                usersChangePassword.changePassword(vm.changePassword, function(response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        if (parseInt($rootScope.settings.USER_IS_LOGOUT_AFTER_CHANGE_PASSWORD)) {
                            delete $rootScope.user;
                            $cookies.remove('auth', {
                                path: '/'
                            });
                            $cookies.remove('token', {
                                path: '/'
                            });
                            $scope.$emit('updateParent', {
                                isAuth: false
                            });
                            flash.set($filter("translate")("Your password has been changed successfully. Please login now"), 'success', false);
                            $location.path('/users/login');
                        } else {
                            vm.changePassword = {};
                            vm.save_btn = false;
                            flash.set($filter("translate")("Your password has been changed successfully."), 'success', false);
                        }
                    } else {
                        flash.set($filter("translate")("Your old password is incorrect, please try again."), 'error', false);
                        vm.save_btn = false;
                    }
                });
            }
        };
        vm.loader = false;
    });