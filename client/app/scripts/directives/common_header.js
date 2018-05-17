'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:yourDetails
 * @description
 * # yourDetails
 */
angular.module('ofosApp')
    .directive('header', function () {
        return {
            restrict: 'A',
            templateUrl: 'views/default/header.html'
        };
    });