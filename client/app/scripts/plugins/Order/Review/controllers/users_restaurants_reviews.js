'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersRestaurantsReviewsController
 * @description
 * # UsersRestaurantsReviewsController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Review')
    .controller('UsersRestaurantsReviewsController', function($rootScope, usersRestaurantsReviews, $filter, $stateParams, $location, md5, $scope) {
        var vm = this;         
         $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("User Restaurant Reviews")
         vm.index = function() {
            vm.maxSize = 5; 
            vm.currentPage = (angular.isDefined($stateParams.page)) ? parseInt($stateParams.page) : 1;     
            vm.getReview();
        };
        vm.getReview = function() {
            vm.loader = true;
            vm.limit = 10    
            vm.skip = (vm.currentPage - 1) * vm.limit;            
            var params = {};           
            params.filter='{"where":{"user_id":'+$rootScope.user.id+'},"include":{"0":"restaurant.attachment"},"skip":'+vm.skip+',"limit":'+vm.limit+'}';
            usersRestaurantsReviews.get(params, function(response) {                
                if (angular.isDefined(response._metadata)) {
                    vm.currentPage = response._metadata.current_page;
                    vm.totalItems = response._metadata.total;
                    vm.itemsPerPage = response._metadata.per_page;
                    vm.noOfPages = response._metadata.last_page;
                }
                angular.forEach(response.data, function(value) {
                    if (angular.isDefined(value.restaurant) && value.restaurant !== null) {
                        if (angular.isDefined(value.restaurant.attachment) && value.restaurant.attachment !== null) {
                            var hash = md5.createHash('Restaurant' + value.restaurant.attachment.id + 'png' + 'medium_thumb');
                            value.restaurant.image_name = 'images/medium_thumb/Restaurant/' + value.restaurant.attachment.id + '.' + hash + '.png';
                        } else {
                            value.restaurant.image_name = 'images/no-image-restaurant-100x100.png';
                        }
                    }
                });
                if (angular.isDefined(response.data)) {
                    vm.reviews = response.data;
                }
                vm.loader = false;
            });
        
        };

        /*var unregisterLocationChangeSuccess = $scope.$on('locationChangeSuccess', function() {
            console.log("changes");
            vm.currentPage = (angular.isDefined($stateParams.page)) ? parseInt($stateParams.page) : 1;
            vm.index();
            
        });
        $scope.$on("$destroy", function() {
            unregisterLocationChangeSuccess();
        });*/ 
        vm.paginate = function() {           
            vm.getReview();                                        
        };      
        vm.index();
    });