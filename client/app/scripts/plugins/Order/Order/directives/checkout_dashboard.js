'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:checkoutDashboard
 * @description
 * # checkoutDashboard
 */
angular.module('ofosApp.Order.Order')
    .directive('checkoutDashboard', function() {
        return {
            templateUrl: 'scripts/plugins/Order/Order/views/default/checkout_dashboard.html',
            restrict: 'E',
            replace: 'true',
            scope: {
                deliveryCharge: '@deliveryCharge',
                restSalesTax: '@restSalesTax',
                cookieId: '@cookieId',
                minimum_Order_Booking: '@minimumOrderBooking'
            },
            controllerAs: 'vm',
            controller: function($scope, $rootScope, getCarts, $cookies, cart, $location, $filter, flash) {
                var vm = this;
                vm.carts = [];
                vm.getCart = function() {
                    if (angular.isDefined($scope.cookieId)) {
                        var params = {};
                        params.filter = '{"where":{"cookie_id":"' + $scope.cookieId + '"},"include":{"0":"restaurant_menu","1":"restaurant_menu_price"}}';
                        getCarts.get(params, function(carts) {
                            if (angular.isDefined(carts.cart) && angular.isDefined(carts.cart)) {
                                angular.forEach(carts.cart, function(value) {
                                    var addon_price = 0;
                                    var addons = [];
                                    angular.forEach(value.cart_addon, function(value1) {
                                        addon_price += parseFloat(value1.price);
                                        if (addons.length > 0) {
                                            angular.forEach(addons, function(value2) {
                                                if (value1.restaurant_addon.name !== value2.name) {
                                                    addons.push({
                                                        'name': value1.restaurant_addon.name,
                                                        'item': value1.restaurant_addon_item.name
                                                    });
                                                } else {
                                                    value2.item = value2.item + ', ' + value1.restaurant_addon_item.name;
                                                }
                                            });
                                        } else {
                                            addons.push({
                                                'name': value1.restaurant_addon.name,
                                                'item': value1.restaurant_addon_item.name
                                            });
                                        }
                                    });
                                    value.price = parseFloat(value.price) + parseFloat(addon_price);
                                    value.addons = addons;
                                });
                                vm.carts = carts.cart;
                                vm.calculate();
                            }
                        });
                    }
                };
                vm.update_cart = function(id, quantity) {
                    var params = {};
                    var flashMessage;
                    params.user_id = ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) ? $rootScope.user.id : null;
                    params.cookie_id = $scope.cookieId;
                    params.cart_id = id;
                    params.quantity = quantity;
                    cart.update(params, function(response) {
                        if (response.error.code === 0) {
                            angular.forEach(response.cart, function(value) {
                                var addon_price = 0;
                                var addons = [];
                                angular.forEach(value.cart_addon, function(value1) {
                                    addon_price += parseFloat(value1.price);
                                    if (addons.length > 0) {
                                        angular.forEach(addons, function(value2) {
                                            if (value1.restaurant_addon.name !== value2.name) {
                                                addons.push({
                                                    'name': value1.restaurant_addon.name,
                                                    'item': value1.restaurant_addon_item.name
                                                });
                                            } else {
                                                value2.item = value2.item + ', ' + value1.restaurant_addon_item.name;
                                            }
                                        });
                                    } else {
                                        addons.push({
                                            'name': value1.restaurant_addon.name,
                                            'item': value1.restaurant_addon_item.name
                                        });
                                    }
                                });
                                value.price = parseFloat(value.price) + parseFloat(addon_price);
                                value.addons = addons;
                            });
                            vm.carts = response.cart;
                            vm.calculate();
                        } else {
                            flashMessage = $filter("translate")("Unable to update your cart.");
                            flash.set(flashMessage, 'error', false);
                        }
                    });
                };
                vm.remove_cart = function(id) {
                    var params = {};
                    var flashMessage;
                    params.user_id = ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) ? $rootScope.user.id : null;
                    params.cookie_id = $scope.cookieId;
                    params.cart_id = id;
                    cart.remove(params, function(response) {
                        vm.response = response;
                        if (vm.response.error.code === 0) {
                            angular.forEach(vm.carts, function(value, key) {
                                if (parseInt(value.id) === parseInt(id)) {
                                    vm.carts.splice(key, 1);
                                }
                            });
                            vm.calculate();
                        } else {
                            flashMessage = $filter("translate")("Unable to remove this item from your cart.");
                            flash.set(flashMessage, 'error', false);
                        }
                    });
                };
                vm.calculate = function() {
                    var sub_total = 0;
                    var addon_total = 0;
                    angular.forEach(vm.carts, function(value, key) {
                        //jshint unused:false
                        angular.forEach(value.cart_addon, function(value1, key1) {
                            //jshint unused:false
                            addon_total += parseFloat(value1.price);
                        });
                        sub_total += parseFloat(value.total_price);
                    });
                    vm.sub_total = parseFloat(sub_total);
                    vm.sales_tax = (vm.sub_total * parseFloat($scope.restSalesTax) / 100);
                    vm.total = parseFloat(vm.sub_total) + parseFloat($scope.deliveryCharge) + parseFloat(vm.sales_tax);
                };
                vm.checkout = function() {
                    if ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) {
                        $location.path('/review_order');
                    } else {
                        $cookies.put('redirect_url', '/review_order', {
                            path: '/'
                        });
                        $location.path('/users/login');
                    }
                };
                $rootScope.$on('updateCheckoutDashboard', function(event, args) {
                    vm.getCart();
                });
                vm.getCart();
            }
        };
    });