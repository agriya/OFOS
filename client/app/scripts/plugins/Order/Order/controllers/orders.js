'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:OrdersController
 * @description
 * # OrdersController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('OrdersController', function($rootScope, orders, $stateParams, flash, md5, $filter, $uibModal, $location, userSettings, $scope, ORDER_STATUS, $state) {
        var vm = this;
        vm.orderStatus = ORDER_STATUS;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Users Orders");
        vm.review = {};
        var flashMessage;
        if (parseInt($stateParams.error_code) === 512) {
            flashMessage = $filter("translate")("Payment Failed. Please, try again.");
            flash.set(flashMessage, 'error', false);
            $location.search({});
        } else if (parseInt($stateParams.error_code) === 0) {
            flashMessage = $filter("translate")("Payment successfully completed.");
            flash.set(flashMessage, 'success', false);
            $location.search({});
        }
        vm.index = function() {
            vm.maxSize = 5; 
            vm.currentPage = (angular.isDefined($stateParams.page)) ? parseInt($stateParams.page) : 1;     
             vm.getOrder();
        };
        vm.getOrder = function() {
            vm.loader = true;            
            vm.limit = 10    
            vm.skip = (vm.currentPage - 1) * vm.limit;
            vm.id = $rootScope.user.id;
            var params = {};
            params.filter = '{"where":{"user_id":'+$rootScope.user.id+'},"skip":'+vm.skip+',"limit":'+vm.limit+',"include":{"0":"city","1":"country","2":"order_items","3":"order_status","4":"restaurant.attachment","5":"state","6":"user","7":"delivery_person.user"},"order":"id desc"}';
            orders.get(params, function(response) {                
                angular.forEach(response.data, function(values) {
                    var sub_tot = 0;
                    angular.forEach(values.order_items, function(value) {
                        if (value.restaurant_menu_price !== null) {
                            sub_tot += parseFloat(value.restaurant_menu_price.price) * parseFloat(value.quantity);
                        }
                    });
                    values.sub_total = sub_tot;
                    values.sal_tax = (sub_tot * parseFloat(values.sales_tax) / 100);
                });
                if (angular.isDefined(response._metadata)) {
                    vm.currentPage = response._metadata.current_page;
                    vm.totalItems = response._metadata.total;
                    vm.itemsPerPage = response._metadata.per_page;
                    vm.noOfPages = response._metadata.last_page;
                }
                if (angular.isDefined(response.data)) {
                    vm.orders = response.data;
                    angular.forEach(vm.orders, function(value, key) {
                        value.created_at = new Date(value.created_at);
                        if (angular.isDefined(value.restaurant) && value.restaurant !== null) {
                            if (angular.isDefined(value.restaurant.attachment) && value.restaurant.attachment !== null) {
                                var hash = md5.createHash('Restaurant' + value.restaurant.attachment.id + 'png' + 'small_thumb');
                                vm.orders[key].image_name = 'images/small_thumb/Restaurant/' + value.restaurant.attachment.id + '.' + hash + '.png';
                            } else {
                                vm.orders[key].image_name = 'images/no-image-restaurant-64x64.png';
                            }
                        }
                    });
                }
                vm.loader = false;
            });
        };
        vm.open = function(restaurant_id, order_id) {
            //jshint unused:false
            vm.review.restaurant_id = restaurant_id;
            vm.review.order_id = order_id;
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'views/reviews.html',
                controller: 'UsersRestaurantReviewController as vm',
                resolve: {
                    review: function() {
                        return vm.review;
                    }
                }
            });
        };
        var unregisterLocationChangeSuccess = $scope.$on('locationChangeSuccess', function() {
            vm.currentPage = (angular.isDefined($stateParams.page)) ? parseInt($stateParams.page) : 1;
            vm.index();
        });
        vm.paginate = function() {           
            vm.getOrder(); 
        };
        vm.index();
        $scope.$on("$destroy", function() {
            unregisterLocationChangeSuccess();
        });
        // Cancelling order
        vm.cancelOrder=function(OrderStatusId,OrderId){
             swal({//jshint ignore:line
                        title: $filter("translate")("Are you sure you want to cancel?"),
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel",
                        animation:false,
                    }).then(function (isConfirm) {
                        if (isConfirm) {
                            var params = {};
                            params.orderId = OrderId;
                            params.order_status_id= vm.orderStatus.Cancel;
                            orders.put(params, function(response){
                                if(response.error.code === 0){
                                    flashMessage = $filter("translate")("Order has been canceled.");
                                    flash.set(flashMessage, 'success', false);
                                    $state.reload();
                                }
                            });
                            
                        }
                    });
        };
    });