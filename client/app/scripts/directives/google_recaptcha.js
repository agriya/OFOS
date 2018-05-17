'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:googleRecaptcha
 * @description
 * # googleRecaptcha
 */
angular.module('ofosApp')
    .directive('googleRecaptcha', function() {
        return {
            restrict: 'C',
            scope: '=',
            template: '<div vc-recaptcha theme="\'light\'" key="model.key" on-create="setWidgetId(widgetId)" on-success="setResponse(response)" on-expire="cbExpiration()"></div>',
            controller: function($rootScope, $scope, vcRecaptchaService) {
                $scope.model = {
                    key: $rootScope.settings.CAPTCHA_SITE_KEY
                };
                $scope.setResponse = function(response) {
                    $scope.response = response;
                };
                $scope.setWidgetId = function(widgetId) {
                    $scope.widgetId = widgetId;
                };
                $scope.cbExpiration = function() {
                    vcRecaptchaService.reload($scope.widgetId);
                    $scope.response = null;
                };
            },
        };
    });