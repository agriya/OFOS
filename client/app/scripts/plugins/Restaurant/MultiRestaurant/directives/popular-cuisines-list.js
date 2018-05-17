'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:popularCuisinesList
 * @description
 * # popularCuisinesList
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .directive('popularCuisinesList', function(cuisines) {
        return {
            templateUrl: 'scripts/plugins/Restaurant/MultiRestaurant/views/default/popular-cuisines-list.html',
            restrict: 'E',
            replace: 'true',
            link: function postLink(scope, element, attrs) {
                //jshint unused:false
                var params = {};
                params.filter = '{"where":{"is_active":"1"},"skip":0,"limit":30}';
                cuisines.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        scope.cuisines = response.data;
                        response.data.first = [];
                        response.data.second = [];
                        response.data.third = [];
                        response.data.fourth = [];
                        response.data.fifth = [];
                        response.data.sixth = [];
                        angular.forEach(response.data, function(value, key) {
                            if (key % 6 === 0) {
                                response.data.first.push(value);
                            } else if (key % 6 === 1) {
                                response.data.second.push(value);
                            } else if (key % 6 === 2) {
                                response.data.third.push(value);
                            } else if (key % 6 === 3) {
                                response.data.fourth.push(value);
                            } else if (key % 6 === 4) {
                                response.data.fifth.push(value);
                            } else if (key % 6 === 5) {
                                response.data.sixth.push(value);
                            }
                        });
                    }
                });
            }
        };
    });