'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:pages
 * @description
 * # pages
 */
angular.module('ofosApp')
    .directive('pages', function(pages) {
        return {
            templateUrl: 'views/pages.html',
            restrict: 'E',
            replace: 'true',            
            controllerAs: 'vm',
            controller: function($scope, $rootScope, getCarts, $cookies, cart, $location, $filter, flash) {
                var vm = this;
                //jshint unused:false                
                var params={};
                params.filter = '{"where":{"is_active":1},"skip":0,"limit":20}';
                pages.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        vm.pages = response.data;                                              
                    }
                });
            }
        };
    });