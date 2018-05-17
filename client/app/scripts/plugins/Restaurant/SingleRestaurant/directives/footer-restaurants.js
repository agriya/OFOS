'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:popularRestaurants
 * @description
 * # popularRestaurants
 */
angular.module('ofosApp.Restaurant.SingleRestaurant')
    .directive('footerRestaurants', function(restaurants) {
        return {
            templateUrl: 'scripts/plugins/Restaurant/SingleRestaurant/views/default/footer-restaurants.html',
            restrict: 'E',
            replace: 'true',
            controllerAs: 'vmf',
            controller: function($scope, $rootScope, $filter, flash, $state) {
                var vmf = this;                
                //jshint unused:false
                var params={};                
                params.filter = '{"limit":"all","skip":"0","order":"parent_id DESC","fields":{"id":true,"name":true}}';
                restaurants.get(params, function(response) {                                              
                    if (angular.isDefined(response.data)) {     
                        vmf.footer_restaurant_branch = response.data;                 
                    }
                });
                vmf.selectRestaurant = function(index) {                    
                    var current_state = $state.current.name;
                    if (current_state === 'home') {
                        $rootScope.selectedBranch(index);
                    } else {                    
                        $state.go('home', {
                            'res_index': index
                        });
                    }
                };
            }
        };
    });   