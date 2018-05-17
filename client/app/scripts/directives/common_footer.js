'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:yourDetails
 * @description
 * # yourDetails
 */
angular.module('ofosApp')
    .directive('footer', function () {
        return {
            restrict: 'A',
            templateUrl: 'views/default/footer.html'
        };
    });