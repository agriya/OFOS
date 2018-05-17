'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersLoginCtrl
 * @description
 * # UsersLoginCtrl
 * Controller of the ofosApp
 */
angular.module('ofos')
    .controller('UsersLogoutCtrl', function($scope, $location, $http, $window, adminTokenService, $q, $cookies) {
        $http({
                method: 'GET',
                url: '/api/v1/users/logout'
            })
            .success(function(response) {
                $scope.response = response;
                if ($scope.response.error.code === 0) {
                    $cookies.remove('auth', {
                        path: '/'
                    });
                    $cookies.remove('token', {
                        path: '/'
                    });
                    var promise = adminTokenService.promise;
                    var promiseSettings = adminTokenService.promiseSettings;
                    $q.all([
                           promiseSettings,
                           promise
                        ])
                        .then(function(value) {
                            $location.path('/users/login');
                        });
                }
            });
    });