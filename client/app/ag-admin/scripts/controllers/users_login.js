'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersLoginCtrl
 * @description
 * # UsersLoginCtrl
 * Controller of the ofosApp
 */
angular.module('ofos')
    .controller('UsersLoginCtrl', function($rootScope, $scope, $location, $http, $window, $timeout, progression, notification, $cookies) {
        $scope.save_btn = false;
        $scope.user_using_to_login = $rootScope.settings.USER_USING_TO_LOGIN;
        $scope.loginUser = function() {
            if ($scope.userLogin.$valid && !$scope.save_btn) {
                $scope.save_btn = true;
                if ($rootScope.settings.USER_USING_TO_LOGIN === 'email') {
                    $scope.user.email = $scope.user.username;
                    delete $scope.user.username;
                }
                $http({
                        method: 'POST',
                        url: '/api/v1/users/login',
                        data: $scope.user
                    })
                    .success(function(response) {
                        $scope.response = response;
                        delete $scope.response.scope;
                        if ($scope.response.error.code === 0) {
                            $cookies.put('auth', JSON.stringify($scope.response), {
                                path: '/'
                            });
                            $cookies.put('token', $scope.response.access_token, {
                                path: '/'
                            });
                            $timeout(function() {
                                $window.location.reload();
                            });
                        } else {
                            progression.done();
                            notification.log('Sorry, login failed. Your username or password are incorrect.', {
                                addnCls: 'humane-flatty-error'
                            });
                            $scope.user = {};
                            $scope.save_btn = false;
                        }
                    });
            }
        };
    });