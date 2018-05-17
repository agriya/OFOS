'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersRegisterController
 * @description
 * # UsersRegisterController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('GuestRegisterController', function ($rootScope, usersRegister, flash, $location, $timeout, countries, $filter, $cookies, $uibModalStack, $document, $scope, $state, vcRecaptchaService) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Guest Register");
        /*jshint -W117 */
        $scope.captcha_site_key = $rootScope.settings['CAPTCHA_SITE_KEY'];
        vm.captcha_enabled = parseInt($rootScope.settings['USER_IS_CAPTCHA_ENABLED_REGISTER']);
        var params = {};
        params.filter = '{"fields":{"phone":true,"iso2":true},"limit":300,"skip":0}';
        countries.get(params, function (response) {
            if (angular.isDefined(response.data)) {
                vm.countries = response.data;
            }
        });
        $scope.setWidgetId = function (widgetId) {
            $scope.widgetId = widgetId;
        };
        vm.save_btn = false;
        vm.save = function () {
            if (vm.captcha_enabled === 1) {
                vm.captchaErr = '';
                var response = vcRecaptchaService.getResponse($scope.widgetId);
                if (response.length === 0) {
                    vm.captchaErr = $filter("translate")("Please resolve the captcha and submit");
                } else {
                    vm.captchaErr = '';
                }
            }
            if (vm.guestSignup.$valid) {
                if (vm.captcha_enabled === 1) {
                    vm.user.captcha_response = vcRecaptchaService.getResponse($scope.widgetId);
                }
                vm.user.is_create_an_account = 0;
                vm.user.is_agree_terms_conditions = 1;
                usersRegister.create(vm.user, function (response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        if (angular.isDefined(vm.response.id)) {
                            var login_details = { 'email': vm.response.email, 'username': vm.response.username, 'mobile_code': vm.response.mobile_code, 'mobile': vm.response.mobile, 'id': vm.response.id };
                            $cookies.put('guest_user', angular.toJson(login_details), {
                                path: '/'
                            });
                            $state.go('review_order');
                        } else {
                            flash.set($filter("translate")("Sorry, login failed. Either your email or mobile are incorrect."), 'error', false);
                        }
                    } else {
                        flash.set($filter("translate")("Sorry, login failed. Either your email or mobile are incorrect."), 'error', false);
                    }
                }, function (error) {
                    flash.set($filter("translate")("User could not be added. Please, try again"), 'error', false);
                });
            }
        };
    });