'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:pages
 * @description
 * # pages
 */
angular.module('ofosApp')
    .directive('simpleCaptcha', function() {
        return {
            restrict: 'E',
            scope: {
                valid: '=',
                from: '='
            },
            template: '<input ng-model="a.value" ng-show="a.input" style="width:2em; text-align: center;" name="normalcapcha1" ng-required="true"><span ng-hide="a.input">{{a.value}}</span>&nbsp;{{operation}}&nbsp;<input ng-model="b.value" ng-show="b.input" style="width:2em; height:28px; text-align: center;" name="normalcapcha2" ng-required="true"><span ng-hide="b.input">{{b.value}}</span><span class="text-20">&nbsp;=&nbsp;{{result}}</span>',
            controller: function($scope, $rootScope) {
                var show = Math.random() > 0.5;
                var value = function(max) {
                    return Math.floor(max * Math.random());
                };
                var int = function(str) {
                    return parseInt(str, 10);
                };
                $scope.a = {
                    value: show ? undefined : 1 + value(4),
                    input: show
                };
                $scope.b = {
                    value: !show ? undefined : 1 + value(4),
                    input: !show
                };
                $scope.operation = '+';
                $scope.result = 5 + value(5);
                var a = $scope.a;
                var b = $scope.b;
                var result = $scope.result;
                var checkValidity = function() {
                    if (a.value && b.value) {
                        var calc = int(a.value) + int(b.value);
                        $scope.valid = calc === result;
                        if ($scope.valid === true) {
                            $rootScope.captchaFailed = true;
                        }
                    } else {
                        $scope.valid = false;
                        $rootScope.captchaFailed = false;
                    }
                    //     $scope.$apply(); // needed to solve 2 cycle delay problem;
                };
                $scope.$watch('a.value', function() {
                    checkValidity();
                });
                $scope.$watch('b.value', function() {
                    checkValidity();
                });
            }
        };
    });