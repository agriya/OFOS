'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersRegisterController
 * @description
 * # UsersRegisterController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UsersBecomeRestaurantController', function ($rootScope, usersRegister, flash, $location, $timeout, vcRecaptchaService, $filter, $cookies, $uibModalStack, $document, countries, $scope) {
        var vm = this;
        vm.user = {};
        vm.user.restaurants = {}; 
        vm.index = function() {        
            countries.get({limit: 'all'}, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.countries = response.data;                
                }
            });  
        };
        $scope.$on('g-places-autocomplete:select', function (event, fulladdress) {                         
            if (typeof(fulladdress) !== 'string' && fulladdress !== null) {                    
                    vm.user.restaurants.latitude = fulladdress.geometry.location.lat();             
                    vm.user.restaurants.longitude = fulladdress.geometry.location.lng();
                    vm.user.restaurants.address = fulladdress.formatted_address; 
                    vm.user.restaurants.city = {}; 
                    vm.user.restaurants.state = {}; 
                    vm.user.restaurants.country = {};                                        
                    angular.forEach(fulladdress.address_components, function(address) {                       
                        if (address.types[0] === 'locality' || address.types[0] === 'administrative_area_level_2') {
                            vm.user.restaurants.city.name= address.long_name;                           
                        }
                        if (address.types[0] === 'administrative_area_level_1') {
                            vm.user.restaurants.state.name = address.long_name;                            
                        }
                        if (address.types[0] === 'country') {
                            vm.user.restaurants.country.iso2 = address.short_name;                            
                        }                       
                    });
                }
        });
        vm.save = function () {                     
            if (vm.userSignup.$valid && vm.user.is_agree_terms_conditions) { 
                vm.user.is_create_an_account = 1;
                vm.user.is_become_restaurant = 1;
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
        vm.index();
    });