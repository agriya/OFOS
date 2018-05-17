'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:ReviewOrderController
 * @description
 * # ReviewOrderController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('ReviewOrderController', function ($rootScope, getCarts, cart, checkout, $location, flash, $window, md5, $filter, $state, $cookies) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Review Order");
        vm.disable_pre_book = parseInt($rootScope.settings.DISABLE_PRE_BOOK);
        vm.is_allow_users_to_door_delivery_order = 1;
        vm.comment = "";
        vm.current_cookie_id = "";
        vm.date_list = [];
        vm.maxRatings = [];
        vm.maxRating = 5;
        for (var j = 0; j < vm.maxRating; j++) {
            vm.maxRatings.push(j);
        }

        if ($cookies.get('cartCookie') && (($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) || $cookies.get("guest_user") !== null && angular.isDefined($cookies.get("guest_user")))) {
             var all_cookies = $cookies.getObject('cartCookie');
             vm.current_cookie_id=all_cookies.hash;
        } else {
            $state.go('users_login');
        }
        function addDays(theDate, days) {
            return new Date(theDate.getTime() + days * 24 * 60 * 60 * 1000);
        }
        for (var i = 0; i < 5; i++) {
            vm.date_list.push({
                'display_date': $filter('date')(addDays(new Date(), i), 'yyyy-MM-dd'),
                'value_date': addDays(new Date(), i)
            });
        }
        vm.index = function () {
            var params = {};
            params.filter = '{"where":{"cookie_id":"' + vm.current_cookie_id + '"},"include":{"0":"restaurant_menu","1":"restaurant_menu_price","2":"restaurant.attachment"}}';
            if (angular.isDefined(vm.current_cookie_id)) {
                getCarts.get(params, function (carts) {
                    if (angular.isDefined(carts.cart)) {
                        vm.carts = carts.cart;
                        angular.forEach(carts.cart, function (value) {
                            var addon_price = 0;
                            var addons = [];
                            angular.forEach(value.cart_addon, function (value1) {
                                addon_price += parseFloat(value1.price);
                                if (addons.length > 0) {
                                    angular.forEach(addons, function (value2) {
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
                        vm.restaurant = carts.restaurant;
                        if (!vm.restaurant.is_allow_users_to_door_delivery_order) {
                            vm.is_allow_users_to_door_delivery_order = 0;
                        }
                        if (vm.restaurant.is_closed === 1 && vm.restaurant.is_allow_users_to_preorder === 1) {
                            vm.asap_later = 0;
                        } else {
                            vm.asap_later = 1;
                        }
                        if (angular.isDefined(vm.restaurant.attachment) && vm.restaurant.attachment !== null) {
                            var hash = md5.createHash('Restaurant' + vm.restaurant.attachment.id + 'png' + 'medium_thumb');
                            vm.restaurant.image_name = 'images/medium_thumb/Restaurant/' + vm.restaurant.attachment.id + '.' + hash + '.png';
                        } else {
                            vm.restaurant.image_name = 'images/no-image-restaurant-100x100.png';
                        }
                        if (angular.isDefined(vm.restaurant.restaurant_timing)) {
                            var d = new Date();
                            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                            var n = weekday[d.getDay()];
                            angular.forEach(vm.restaurant.restaurant_timing, function (value, key) {
                                //jshint unused:false
                                if (value.day === n && value.period_type === 1 && value.start_time !== "00:00:00") {
                                    vm.restaurant.open = value.start_time.substring(0, value.start_time.length - 3);
                                }
                            });
                        }
                        if (angular.isDefined(vm.restaurant.restaurant_review_count) && vm.restaurant.restaurant_review_count.length !== 0) {
                            vm.restaurant.rating_round = Math.round(vm.restaurant.restaurant_review_count[0].total_ratings / vm.restaurant.restaurant_review_count[0].total_user_rating_count);
                            vm.restaurant.rating_point = vm.restaurant.restaurant_review_count[0].total_ratings / vm.restaurant.restaurant_review_count[0].total_user_rating_count;
                        }
                        vm.calculate();
                    }
                });
            }
        };
        vm.getTime = function () {
            vm.time_list = [];
            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var selected_day = new Date(vm.later_date)
                .getDay();
            if (angular.isDefined(vm.restaurant.restaurant_timing)) {
                angular.forEach(vm.restaurant.restaurant_timing, function (value) {
                    if (value.day === weekday[selected_day] && value.period_type === 1 && value.start_time !== "00:00:00") {
                        prepare_time_list(value);
                    }
                    if (value.day === weekday[selected_day] && value.period_type === 2 && value.start_time !== "00:00:00") {
                        prepare_time_list(value);
                    }
                    if (value.day === weekday[selected_day] && value.period_type === 3 && value.start_time !== "00:00:00") {
                        prepare_time_list(value);
                    }
                });
            }
            var new_date = new Date()
                .getDay();
            var new_time = new Date(new Date().setHours(new Date().getHours() + 1));
            var new_selected_date_list = [];
            if (new_date === selected_day) {
                angular.forEach(vm.time_list, function (value) {
                    var split_tile_list = value.split(':');
                    if (split_tile_list[0] > new_time.getHours()) {
                        new_selected_date_list.push(value);
                    }
                });
                vm.time_list = new_selected_date_list;
            }

            function prepare_time_list(value) {
                var added_time, start_date, end_date = '';
                var breakfast_start_time = value.start_time.substring(0, value.start_time.length - 3);
                var breakfast_end_time = value.end_time.substring(0, value.start_time.length - 3);
                do {
                    vm.time_list.push(breakfast_start_time);
                    var split_up_start_time = breakfast_start_time.split(':');
                    start_date = new Date();
                    start_date.setHours(split_up_start_time[0]);
                    start_date.setMinutes(split_up_start_time[1]);
                    var split_up_end_time = breakfast_end_time.split(':');
                    end_date = new Date();
                    end_date.setHours(split_up_end_time[0]);
                    end_date.setMinutes(split_up_end_time[1]);
                    added_time = start_date.getTime() + 1800000;
                    var added_hours = new Date(start_date.getTime() + 1800000)
                        .getHours();
                    if (added_hours <= 9) {
                        added_hours = '0' + added_hours;
                    }
                    var added_minutes = new Date(start_date.getTime() + 1800000)
                        .getMinutes();
                    if (added_minutes <= 9) {
                        added_minutes = '0' + added_minutes;
                    }
                    breakfast_start_time = added_hours + ':' + added_minutes;
                } while (added_time < end_date.getTime());
                vm.time_list.push(breakfast_start_time);
                return true;
            }
        };
        vm.index();
        vm.remove_cart = function (id, index) {
            vm.carts.splice(index, 1);
            var params = {};
            var flashMessage;
            params.user_id = ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) ? $rootScope.user.id : 0;
            params.cookie_id = vm.current_cookie_id;
            params.cart_id = id;
            cart.remove(params, function (response) {
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
        vm.update_cart = function (id, quantity) {
            var params = {};
            var flashMessage;
            params.user_id = ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) ? $rootScope.user.id : 0;
            params.cookie_id = vm.current_cookie_id;
            params.cart_id = id;
            params.quantity = quantity;
            cart.update(params, function (response) {
                if (response.error.code === 0) {
                    angular.forEach(response.cart, function (value) {
                        var addon_price = 0;
                        var addons = [];
                        angular.forEach(value.cart_addon, function (value1) {
                            addon_price += parseFloat(value1.price);
                            if (addons.length > 0) {
                                angular.forEach(addons, function (value2) {
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
        vm.calculate = function () {
            var sub_total = 0;
            var addon_total = 0;
            angular.forEach(vm.carts, function (value, key) {
                //jshint unused:false
                angular.forEach(value.cart_addon, function (value1, key1) {
                    //jshint unused:false
                    addon_total += parseFloat(value1.price);
                });
                sub_total += parseFloat(value.total_price);
            });
            vm.sub_total = parseFloat(sub_total);
            vm.sales_tax = (vm.sub_total * parseFloat(vm.restaurant.sales_tax) / 100);
            vm.total = parseFloat(vm.sub_total) + parseFloat(vm.restaurant.delivery_charge) + parseFloat(vm.sales_tax);
        };
        vm.redirectToSearchPage = function () {
            $state.go('search_restaurant', {
                lat: $rootScope.lat,
                lang: $rootScope.lang
            });
        };
        vm.checkout = function () {
            if (parseInt(vm.asap_later) === 0) {
                var selected_date = new Date(vm.later_date)
                    .getDate();
                var selected_year = new Date(vm.later_date)
                    .getFullYear();
                var selected_month = new Date(vm.later_date)
                    .getMonth();
                var selected_time = vm.later_time.split(':');
                var new_date = new Date(selected_year, selected_month, selected_date);
                vm.later_delivery_date = new Date(new_date.setHours(selected_time[0]));
                vm.later_delivery_date = new Date(vm.later_delivery_date.setMinutes(selected_time[1]));
                $window.localStorage.setItem('later_delivery_date', vm.later_delivery_date);
            }
            $window.localStorage.setItem('comment', vm.comment);
            $window.localStorage.setItem('is_allow_users_to_door_delivery_order', vm.is_allow_users_to_door_delivery_order);
            $location.path('/checkout');
        };
    });