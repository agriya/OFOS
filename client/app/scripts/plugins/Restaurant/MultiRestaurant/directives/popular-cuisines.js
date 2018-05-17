'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:popularCuisines
 * @description
 * # popularCuisines
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .directive('popularCuisines', function(cuisines) {
        return {
            templateUrl: 'scripts/plugins/Restaurant/MultiRestaurant/views/default/popular-cuisines.html',
            restrict: 'E',
            replace: 'true',
            link: function postLink(scope, element, attrs) {
                //jshint unused:false
                var params = {};
                params.filter = '{"where":{"is_active":1},"skip":0,"limit":8}';
                cuisines.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        scope.footer_cuisines = response.data;
                    }
                });
            }
        };
    });