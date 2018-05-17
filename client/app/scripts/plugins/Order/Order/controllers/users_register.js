'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersRegisterController
 * @description
 * # UsersRegisterController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UsersRegisterController', function ($rootScope, usersRegister, flash, $location, $timeout, vcRecaptchaService, $filter, $cookies, $uibModalStack, $document, $scope, countries) {
        var vm = this;
        var params = {};
        params.filter = '{"fields":{"phone":true,"iso2":true},"limit":"all","skip":0}';
        countries.get(params, function (response) {
            if (angular.isDefined(response.data)) {
                vm.countries = response.data;
            }
        });
        vm.current_cookie = "";
        var cartCookies = $rootScope.getCartCookie("");
        if (angular.isDefined(cartCookies.hash)) {
            vm.current_cookie = cartCookies.hash;
        }
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Register");
        /*jshint -W117 */
        $scope.captcha_site_key = $rootScope.settings['CAPTCHA_SITE_KEY'];
        vm.captcha_enabled = parseInt($rootScope.settings['USER_IS_CAPTCHA_ENABLED_REGISTER']);
        function validatePassword() {
            var pass2 = $document[0].getElementById("password")
                .value;
            var pass1 = $document[0].getElementById("confirm-password")
                .value;
            if (pass2 !== null && pass1 !== null && pass1 !== pass2) {
                $document[0].getElementById("confirm-password")
                    .setCustomValidity("Password Mismatch");
            } else {
                $document[0].getElementById("confirm-password")
                    .setCustomValidity("");
            }
        }

        angular.element($document[0])
            .on('blur change', "#password, #confirm-password", function () {
                validatePassword();
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
            if (vm.userSignup.$valid && vm.user.is_agree_terms_conditions) {
                if (vm.captcha_enabled === 1) {
                    vm.user.captcha_response = vcRecaptchaService.getResponse($scope.widgetId);
                }
                vm.user.is_create_an_account = 1;
                usersRegister.create(vm.user, function (response) {
                    vm.response = response;
                    delete vm.response.scope;
                    if (vm.response.error.code === 0) {
                        vm.redirect = false;
                        if (parseInt($rootScope.settings.USER_IS_AUTO_LOGIN_AFTER_REGISTER)) {
                            vm.redirect = true;
                            $cookies.put('auth', angular.toJson(vm.response), {
                                path: '/'
                            });
                            $cookies.put('token', vm.response.access_token, {
                                path: '/'
                            });
                            $rootScope.user = vm.response;
                            $scope.$emit('updateParent', {
                                isAuth: true
                            });
                            flash.set($filter("translate")("You have successfully registered with our site."), 'success', false);
                        } else if (parseInt($rootScope.settings.USER_IS_EMAIL_VERIFICATION_FOR_REGISTER) && parseInt($rootScope.settings.USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER)) {
                            flash.set($filter("translate")("You have successfully registered with our site you can login after email verification and administrator approval. Your activation mail has been sent to your mail inbox."), 'success', false);
                        } else if (parseInt($rootScope.settings.USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER)) {
                            flash.set($filter("translate")("You have successfully registered with our site. After administrator approval you can login to site."), 'success', false);
                        } else if (parseInt($rootScope.settings.USER_IS_EMAIL_VERIFICATION_FOR_REGISTER)) {
                            flash.set($filter("translate")("You have successfully registered with our site and your activation mail has been sent to your mail inbox."), 'success', false);
                        } else {
                            flash.set($filter("translate")("You have successfully registered with our site."), 'success', false);
                        }
                        if ($cookies.get("redirect_url") !== null && angular.isDefined($cookies.get("redirect_url")) && vm.redirect) {
                            $location.path($cookies.get("redirect_url"));
                            $cookies.remove('redirect_url');
                        } else {
                            $timeout(function () {
                                $location.path('/');
                            }, 1000);
                        }
                        $uibModalStack.dismissAll();
                    } else {
                        if (angular.isDefined(vm.response.error.fields) && angular.isDefined(vm.response.error.fields.unique) && vm.response.error.fields.unique.length !== 0) {
                            flash.set($filter("translate")("Please choose different " + vm.response.error.fields.unique.join()), 'error', false);
                            vm.save_btn = false;
                        } else {
                            flash.set($filter("translate")("User could not be added. Please, try again"), 'error', false);
                            vm.save_btn = false;
                        }
                        if ($rootScope.settings.CAPTCHA_TYPE === 'Google reCAPTCHA') {
                            vcRecaptchaService.reload(vm.widgetId);
                        }
                    }
                }, function (error) {
                    if (angular.isDefined(error.data.error.fields) && angular.isDefined(error.data.error.fields.unique) && error.data.error.fields.unique.length !== 0) {
                        flash.set($filter("translate")("Please choose different " + error.data.error.fields.unique.join()), 'error', false);
                    } else {
                        flash.set($filter("translate")("User could not be added. Please, try again"), 'error', false);
                    }
                    if ($rootScope.settings.CAPTCHA_TYPE === 'Google reCAPTCHA') {
                        vcRecaptchaService.reload(vm.widgetId);
                    }
                });
            }
            else{
                if (vm.user.is_agree_terms_conditions) {
                    vm.is_Agree='1'; 
                }
                else{
                    vm.is_Agree='0';                                       
                }
                            
            }
        };
        vm.IsAgree=function(){ 
            vm.is_Agree='1';             
        }
    });