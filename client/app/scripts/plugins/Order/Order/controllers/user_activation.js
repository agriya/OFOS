'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UserActivationController
 * @description
 * # UserActivationController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UserActivationController', function($rootScope, $location, flash, userActivation, $stateParams, $filter, $cookies) {
        var element = {};
        element.user_id = $stateParams.user_id;
        element.hash = $stateParams.hash;
        userActivation.activation(element, function(response) {
            var esponse = response;
            if (esponse.error.code === 0) {
                delete esponse.scope;
                if (parseInt($rootScope.settings.USER_IS_AUTO_LOGIN_AFTER_REGISTER)) {
                    $cookies.put('auth', angular.toJson(esponse), {
                        path: '/'
                    });
                    $cookies.put('token', esponse.access_token, {
                        path: '/'
                    });
                    $rootScope.user = esponse;
                    $rootScope.$emit('updateParent', {
                        isAuth: true
                    });
                    flash.set($filter("translate")("You have successfully activated and logged in to your account."), 'success', false);
                } else if (parseInt($rootScope.settings.USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER)) {
                    flash.set($filter("translate")("You have successfully activated your account. But you can login after admin activate your account."), 'success', false);
                } else {
                    flash.set($filter("translate")("You have successfully activated your account. Now you can login."), 'success', false);
                }
                $location.path('/users/login');
            } else {
                flash.set($filter("translate")("Invalid activation request."), 'error', false);
            }
        });
    });