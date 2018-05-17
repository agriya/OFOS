'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:googleAnalytics
 * @description
 * # googleAnalytics
 */
angular.module('ofosApp')
    .directive('googleAnalytics', function() {
        return {
            restrict: 'AE',
            replace: true,
            template: '<div ng-bind-html="googleAnalyticsCode | unsafe"></div>',
            controller: function($rootScope, $scope) {
                //jshint unused:false
                 $scope.googleAnalyticsCode = $rootScope.settings.SITE_TRACKING_SCRIPT;
            },
            scope: {}
        };
    });