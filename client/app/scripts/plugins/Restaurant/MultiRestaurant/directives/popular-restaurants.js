'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:popularRestaurants
 * @description
 * # popularRestaurants
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .directive('popularRestaurants', function(restaurants) {
        return {
            templateUrl: 'scripts/plugins/Restaurant/MultiRestaurant/views/default/popular-restaurants.html',
            restrict: 'E',
            replace: 'true',
            controllerAs: 'vmf',
            controller: function($scope, $rootScope, $filter, flash) {
                var vmf = this;                
                //jshint unused:false
                var params={};
                params.filter = '{"where":{"is_active":1},"order":"id desc","skip":0,"limit":5}';
                restaurants.get(params, function(response) {                                      
                    if (angular.isDefined(response.data)) {                        
                        vmf.footer_restaurants = response.data;                                                    
                    }
                });
            }
        };
    });