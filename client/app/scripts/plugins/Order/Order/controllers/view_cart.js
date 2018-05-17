angular.module('ofosApp.Order.Order')
    .controller('viewcartController', function($rootScope, $window, countries, states, cities, usersAddresses, checkout, flash, $location, $filter, $state, getCarts, paymentGateways, $cookies, userSettings, $stateParams, getCoupons, $scope, $uibModalInstance, cart) {
        var vm = this;
        var payment_gateways = [];
        var params = {};
        vm.current_cookie_id = "";
        vm.index = function(){
             vm.loader = true;
            $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("View Cart");
            var cartCookies = $rootScope.getCartCookie("");
            if (angular.isDefined(cartCookies.hash)) {
            vm.current_cookie_id=cartCookies.hash;
            }
        if (vm.current_cookie_id !=="") {
            params.filter = '{"where":{"cookie_id":"' + vm.current_cookie_id + '"},"include":{"0":"restaurant_menu","1":"restaurant_menu_price"}}';
            getCarts.get(params, function(carts) {
                if (angular.isDefined(carts.cart)) {
                    vm.carts = carts.cart;
                    vm.restaurant = carts.restaurant;
                    vm.minimum_order_for_booking = vm.restaurant.minimum_order_for_booking;
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
                    vm.calculate();
                }
            });
        }
         vm.loader = false;
        }
       
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
            vm.sales_tax = (vm.sub_total * parseFloat(vm.restaurant.sales_tax) / 100);
            vm.total = parseFloat(vm.sub_total) + parseFloat(vm.restaurant.delivery_charge) + parseFloat(vm.sales_tax)
        }
        vm.remove_cart = function(id, index) {
            vm.carts.splice(index, 1);
            var flashMessage;
            params.user_id = ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) ? $rootScope.user.id : null;
            params.cookie_id = vm.current_cookie_id;
            params.cart_id = id;
            cart.remove(params, function(response) {
                vm.response = response;
                if (vm.response.error.code === 0) {
                    vm.calculate();
                    $rootScope.updateCardCount("delete", 1);
                    flashMessage = $filter("translate")("Removed this item from your cart.");
                    flash.set(flashMessage, 'success', false);
                } else {
                    flashMessage = $filter("translate")("Unable to remove this item from your cart.");
                    flash.set(flashMessage, 'error', false);
                }
            });
        };
        vm.cancel = function() {
            $uibModalInstance.dismiss('cancel');
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
            $uibModalInstance.dismiss('cancel');
        };
        vm.index();
    });