'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:popularCities
 * @description
 * # popularCities
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .directive('popularCities', function(cities) {
        return {
            templateUrl: 'scripts/plugins/Restaurant/MultiRestaurant/views/default/popular-cities.html',
            restrict: 'E',
            replace: 'true',
            link: function postLink(scope, element, attrs) {
                //jshint unused:false
                var params = {
                    limit: 20,
                    is_active: true
                };
                cities.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        scope.footer_cities = response.data;
                    }
                });
            }
        };
    });