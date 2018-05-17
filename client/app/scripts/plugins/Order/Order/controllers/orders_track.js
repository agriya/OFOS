'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:OrdersController
 * @description
 * # OrdersController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('OrdersTrackController', function ($rootScope, orderGet, $stateParams, flash, md5, $filter, $uibModal, $location, userSettings, $scope, orders, ORDER_STATUS, $state) {
        var vm = this;
        vm.orderStatus = ORDER_STATUS;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Users Orders");
        var flashMessage;
        if (parseInt($stateParams.error_code) === 512) {
            flashMessage = $filter("translate")("Payment Failed. Please, try again.");
            flash.set(flashMessage, 'error', false);
        } else if (parseInt($stateParams.error_code) === 0) {
            flashMessage = $filter("translate")("Payment successfully completed.");
            flash.set(flashMessage, 'success', false);
        }
        vm.index = function () {
            vm.loader = true;
            var params = {};
            params.filter = '{"include":{"0":"city","1":"country","2":"order_items","3":"order_status","4":"restaurant.attachment","5":"state","6":"user"}}';
            params.orderId = $stateParams.order_id;
            orderGet.get(params, function (response) {
                if (response.error.code === 0) {
                    vm.order = response.data;
                    var sub_tot = 0;
                    angular.forEach(vm.order.order_items, function (value) {
                        if (value.restaurant_menu_price !== null) {
                            sub_tot += parseFloat(value.restaurant_menu_price.price) * parseFloat(value.quantity);
                        }
                    });
                    vm.order.sub_total = sub_tot;
                    vm.order.sal_tax = (sub_tot * parseFloat(vm.order.sales_tax) / 100);
                    vm.order.created_at = new Date(vm.order.created_at);
                    if (angular.isDefined(vm.order.restaurant) && vm.order.restaurant !== null) {
                        if (angular.isDefined(vm.order.restaurant.attachment) && vm.order.restaurant.attachment !== null) {
                            var hash = md5.createHash('Restaurant' + vm.order.restaurant.attachment.id + 'png' + 'small_thumb');
                            vm.order.image_name = 'images/small_thumb/Restaurant/' + vm.order.restaurant.attachment.id + '.' + hash + '.png';
                        } else {
                            vm.order.image_name = 'images/no-image-restaurant-64x64.png';
                        }
                    }
                }
                vm.loader = false;
            });
        };
        vm.cancelOrder = function (OrderStatusId, OrderId) {
            swal({//jshint ignore:line
                title: $filter("translate")("Are you sure you want to cancel?"),
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                animation: false,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    var params = {};
                    params.orderId = OrderId;
                    params.order_status_id = vm.orderStatus.Cancel;
                    orders.put(params, function (response) {
                        if (response.error.code === 0) {
                            vm.order.order_status_id = vm.orderStatus.Cancel;
                            flashMessage = $filter("translate")("Order has been canceled.");
                            flash.set(flashMessage, 'success', false);
                            $state.reload();
                        }
                    });

                }
            });
        };
        vm.index();
    });