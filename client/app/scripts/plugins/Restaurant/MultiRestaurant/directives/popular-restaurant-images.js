'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:popularRestaurantImages
 * @description
 * # popularRestaurantImages
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .directive('popularRestaurantImages', function(restaurants, md5) {
        return {
            templateUrl: 'scripts/plugins/Restaurant/MultiRestaurant/views/default/popular-restaurant-images.html',
            restrict: 'E',
            replace: 'true',          
            controllerAs: 'vm',
            controller: function($scope, $rootScope, getCarts, $cookies, cart, $location, $filter, flash) {
                var vm = this;
                //jshint unused:false
                var params={};
                params.filter = '{"include":{"0":"attachment"},"where":{"is_active":1},"skip":0,"limit":8,"order":"avg_rating DESC"}';
                restaurants.get(params, function(response) {                    
                    if (angular.isDefined(response.data)) {
                        angular.forEach(response.data, function(value) {
                            if (angular.isDefined(value.attachment) && value.attachment !== null) {
                                var hash = md5.createHash('Restaurant' + value.attachment.id + 'png' + 'large_thumb');
                                value.image_name = 'images/large_thumb/Restaurant/' + value.attachment.id + '.' + hash + '.png';
                            } else {
                                value.image_name = 'images/no-image-restaurant-120x120.png';
                            }
                        });
                        vm.restaurants = response.data;                       
                    }
                });
            }
        };
    });