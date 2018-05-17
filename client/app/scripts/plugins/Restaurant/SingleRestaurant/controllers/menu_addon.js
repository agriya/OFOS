angular.module('ofosApp.Restaurant.SingleRestaurant')
    .controller('singleMenuAddonController', function($rootScope, $uibModalInstance, menu, flash, $filter, singlerestaurantMenu, $cookies, md5, carts, $scope) {
        var vm = this;
        var params = {};
        /*jshint -W117 */
        vm.show_checkout_btn = false;
        vm.current_cookie_id = "";
        vm.carts = [];
        vm.total = 0;
        vm.index = function() {
            vm.loader = true;
            params.id = menu.restaurant_id;
            var res_id = menu.restaurant_id;
            var cartCookies = $rootScope.getCartCookie(res_id);
            if (angular.isDefined(cartCookies.hash)) {
            vm.current_cookie_id=cartCookies.hash;
            }
            params = {};
            params.filter = '{"where":{"restaurant_id" : ' + menu.restaurant_id + ' },"order":"restaurant_category_id asc","skip":0,"limit":500,"include":{"0":"restaurant_menu_price","1":"restaurant_categories","2":"restaurant","3":"cuisine","4":"restaurant_addon","5":"restaurant_addon.restaurant_addon_item","6":"restaurant_addon.restaurant_addon_item.restaurant_menu_addon_price"}}';
            singlerestaurantMenu.get(params, function(menus) {
                if (angular.isDefined(menus.data)) {
                    vm.menus = menus.data;
                    angular.forEach(vm.menus, function(value) {
                        if (angular.isDefined(value.restaurant_addon) && value.restaurant_addon !== null && value.id === menu.menu_id) {
                            /*To DO*/
                            vm.addons = value.restaurant_addon;
                            angular.forEach(value.restaurant_addon, function(value1) {
                                angular.forEach(value1.restaurant_addon_item, function(value2) {
                                    if (value2.restaurant_menu_addon_price.length > 0) {
                                        value1.is_show = true;
                                    }
                                });
                            });
                        }
                    });
                }
            });
        };
        vm.restaurant_menu_addon_price = [];
        vm.addonItems = function(price_id, addon_id, type) {
            if (type === false) {
                vm.selected_id = addon_id;
            }
            if (vm.restaurant_menu_addon_price.length > 0) {
                angular.forEach(vm.restaurant_menu_addon_price, function(value, key) {
                    if (parseInt(value.id) === parseInt(price_id)) {
                        vm.restaurant_menu_addon_price.splice(key, 1);
                    }
                    if (parseInt(value.id) !== parseInt(price_id) && key === 0) {
                        vm.restaurant_menu_addon_price.push({
                            'id': price_id
                        });
                    }
                });
            } else {
                vm.restaurant_menu_addon_price.push({
                    'id': price_id
                });
            }
        };
        vm.addCart = function() {
            vm.show_checkout_btn = true;
            var flashMessage;
            params.cookie_id = vm.current_cookie_id;
            params.user_id = ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) ? $rootScope.user.id : null;
            params.restaurant_id = menu.restaurant_id;
            params.restaurant_menu_id = menu.menu_id;
            params.restaurant_menu_price_id = menu.restaurant_menu_price_id;
            params.quantity = 1;
            params.price = menu.price;
            params.total_price = 0;
            params.restaurant_menu_addon_price = vm.restaurant_menu_addon_price;
            carts.create(params, function(response) {
                vm.response = response;
                if (vm.response.error.code === 0) {
                    $rootScope.updateCardCount("add", response.cart.length);
                    var index;
                    var result;
                    if (vm.carts.length !== 0) {
                        result = $.grep(vm.carts, function(e, i) {
                            if (e.id === vm.response.cart[0].id) {
                                index = i;
                                return true;
                            }
                        });
                    }
                    if (vm.carts.length === 0 || result.length === 0) {
                        vm.carts.push(vm.response.cart[0]);
                    } else {
                        vm.carts[index] = vm.response.cart[0];
                    }
                    $scope.$emit('updateCheckoutDashboard', {});
                    $uibModalInstance.dismiss('cancel');
                    flashMessage = $filter("translate")("Added this item to your view cart.");
                    flash.set(flashMessage, 'success', false);
                } else {
                    flashMessage = $filter("translate")("Unable to add this item to your cart.");
                    flash.set(flashMessage, 'error', false);
                }
            });
        };
        vm.cancel = function() {
            $uibModalInstance.dismiss('cancel');
        };
        vm.index();
    });