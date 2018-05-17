'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersLogutController
 * @description
 * # UsersLogutController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('UsersLogoutController', function($rootScope, usersLogout, $location, $window, $filter, $cookies, $scope) {
        var vm = this;
        usersLogout.logout('', function(response) {
            vm.response = response;
            //if (vm.response.error.code === 0) {
                delete $rootScope.user;
                $cookies.remove("auth", {
                    path: "/"
                });
                $cookies.remove("token", {
                    path: "/"
                });
                $rootScope.removeCartCookie(); 
                $scope.$emit('updateParent', {
                    isAuth: false
                });
                $location.path('/');
            //}
        });
    });