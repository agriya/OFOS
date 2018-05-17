'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersLoginController
 * @description
 * # UsersLoginController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UsersLoginController', function($rootScope, usersLogin, providers, $auth, flash, $window, $location, $filter, $cookies, $state, $uibModalStack, $log) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Login");
        if ($cookies.get('auth') !== null && angular.isDefined($cookies.get('auth'))) {
            $rootScope.$emit('updateParent', {
                isAuth: true
            });
            $rootScope.header = $rootScope.settings.SITE_NAME + ' | Home';
            $location.path('/');
        }
        vm.current_cookie = "";
        var cartCookies = $rootScope.getCartCookie("");
        if (angular.isDefined(cartCookies.hash)) {
            vm.current_cookie =cartCookies.hash;
        }
        vm.save_btn = false;
        vm.save = function() {
            if (vm.userLogin.$valid && !vm.save_btn) {
                vm.save_btn = true;
                if ($rootScope.settings.USER_USING_TO_LOGIN === 'email') {
                    vm.user.email = vm.user.username;
                    delete vm.user.username;
                }
                usersLogin.login(vm.user, function(response) {
                    vm.response = response;
                    delete vm.response.scope;
                    if (vm.response.error.code === 0) {
                        $cookies.put('auth', angular.toJson(vm.response), {
                            path: '/'
                        });
                        $cookies.put('token', vm.response.token, {
                            path: '/'
                        });
                        $rootScope.user = vm.response;
                        $rootScope.$broadcast('updateParent', {
                            isAuth: true
                        });
                        if ($cookies.get("redirect_url") !== null && angular.isDefined($cookies.get("redirect_url"))) {
                            $uibModalStack.dismissAll();
                            $location.path($cookies.get("redirect_url"));
                            $cookies.remove("redirect_url", {
                                path: "/"
                            });
                        } else {
                            $uibModalStack.dismissAll();
                            $location.path('/');
                        }
                    } else {
                        flash.set($filter("translate")("Sorry, login failed. Either your username or password are incorrect or admin deactivated your account."), 'error', false);
                        vm.save_btn = false;
                    }
                }, function(error) {
                    if (angular.isDefined(error.data.error.fields) && angular.isDefined(error.data.error.fields.unique) && error.data.error.fields.unique.length !== 0) {
                        flash.set($filter("translate")("Please enter valid " + error.data.error.fields.unique.join()), 'error', false);
                    } else {
                        flash.set($filter("translate")("Sorry, login failed. Either your username or password are incorrect."), 'error', false);
                    }
                    vm.save_btn = false;
                });
            }
        };
        vm.authenticate = function(provider) {
            $auth.authenticate(provider)
                .then(function(response) {
                    vm.response = response.data;
                    delete vm.response.scope;
                    //Twitter login
                    if (vm.response.error.code === 0 && vm.response.thrid_party_profile) {
                        $window.localStorage.setItem("twitter_auth", angular.toJson(vm.response));
                        $state.go('get_email');
                    } else if (vm.response.token) {
                        $cookies.put('auth', angular.toJson(vm.response), {
                            path: '/'
                        });
                        $cookies.put('token', vm.response.token, {
                            path: '/'
                        });
                        $rootScope.user = vm.response;
                        $rootScope.$emit('updateParent', {
                            isAuth: true
                        });
                        if ($cookies.get("redirect_url") !== null && angular.isDefined($cookies.get("redirect_url"))) {
                            $location.path($cookies.get("redirect_url"));
                            $cookies.remove('redirect_url');
                        } else {
                            $location.path('/');
                        }
                    }
                    $uibModalStack.dismissAll();
                })
                .catch(function(error) {
                    $log.log("error in login", error);
                });
        };
        var params = {};
        params.filter = '{"where":{"is_active":1}}';
        providers.get(params, function(response) {
            vm.providers = response.data;
        });
    })
    .controller('TwitterLoginController', function($rootScope, twitterLogin, providers, $auth, flash, $window, $location, $state, $cookies) {
        var vm = this;
        if ($window.localStorage.getItem("twitter_auth") !== null) {
            vm.user = angular.fromJson($window.localStorage.getItem("twitter_auth"));
            vm.loginNow = function($valid) {
                if ($valid) {
                    $window.localStorage.removeItem("twitter_auth");
                    twitterLogin.login(vm.user, function(response) {
                        vm.response = response;
                        if (vm.response.token) {
                            $cookies.put('auth', angular.toJson(vm.response), {
                                path: '/'
                            });
                            $cookies.put('token', vm.response.token, {
                                path: '/'
                            });
                            $rootScope.user = vm.response.user;
                            $rootScope.$emit('updateParent', {
                                isAuth: true
                            });
                            $state.go('home');
                        }
                    });
                }
            };
        } else {
            $location.path('/users/login');
        }
    });