'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:CheckoutController
 * @description
 * # CheckoutController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Order')
    .controller('CheckoutController', function($rootScope, $window, countries, states, cities, usersAddresses, checkout, flash, $location, $filter, $state, getCarts, paymentGateways, $cookies, userSettings, $stateParams, getCoupons, $scope, PAYMENT_GATEWAYS) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Checkout");
        var flashMessage;
        if (parseInt($stateParams.error_code) === 512) {
            flashMessage = $filter("translate")("Payment Failed. Please, try again.");
            flash.set(flashMessage, 'error', false);
        } else if (parseInt($stateParams.error_code) === 0) {
            flashMessage = $filter("translate")("Payment successfully completed.");
            flash.set(flashMessage, 'success', false);
        }
        vm.buyer = {};
        vm.coupon_code = false;
        vm.coupon = {};
        vm.paynow_is_disabled = false;
        vm.show_paypal_credit_form = true;
        vm.payment_note_enabled = false;
        vm.payer_form_enabled = true;
        vm.existing_new_address = 1;
        vm.user_address_id = "";
        vm.user_address_add = {};
        vm.save_btn = false;
        vm.show_time = false;
        vm.current_cookie_id = "";
        vm.first_gateway_id = "";
        vm.guest_login = false;
        
        if (angular.isDefined($cookies.get('cartCookie')) && (($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) || $cookies.get("guest_user") !== null && angular.isDefined($cookies.get("guest_user")))) {
                var cartCookies = $rootScope.getCartCookie("");
                vm.current_cookie_id=cartCookies.hash;
                 vm.current_cookie_restaurant_id=cartCookies.id;
        } else {
            $state.go('users_login');
        }
        /*Checking Guest login*/
        if (($cookies.get("auth") === null || $cookies.get("auth") === undefined) && $cookies.get("guest_user") !== null && angular.isDefined($cookies.get("guest_user"))) {
            vm.guest_login_details = angular.fromJson($cookies.get("guest_user"));
            vm.guest_login = true;
        }
        if (vm.guest_login === false) {
            var params = {};
            params.id = $rootScope.user.id;
            userSettings.get(params, function(response) {
                vm.user_available_balance = response.data.available_wallet_amount;
            });
        }
        if (angular.isDefined($window.localStorage.getItem('is_allow_users_to_door_delivery_order')) && $window.localStorage.getItem('is_allow_users_to_door_delivery_order') !== null) {
            vm.is_pickup_or_delivery = $window.localStorage.getItem('is_allow_users_to_door_delivery_order');
        }
        if ($window.localStorage.getItem('later_delivery_date') !== null) {
            vm.later_delivery_date = new Date($window.localStorage.getItem('later_delivery_date'));
        } else {
            vm.show_time = true;
        }
        vm.index = function() {
            var payment_gateways = [];
            var params = {};
            params.filter = '{"where":{"cookie_id":"' + vm.current_cookie_id + '"},"include":{"0":"restaurant_menu","1":"restaurant_menu_price"}}';
            if (angular.isDefined(vm.current_cookie_id)) {
                getCarts.get(params, function(carts) {
                    if (angular.isDefined(carts.cart)) {
                        vm.carts = carts.cart;
                        vm.restaurant = carts.restaurant;
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
                    if (vm.guest_login === false) {
                        var param = {};
                        param.user_id = $rootScope.user.id;
                        param.restaurant_id = vm.current_cookie_restaurant_id;
                        param.filter = '{"skip":0,"limit":"all"}';
                        usersAddresses.get(param, function(response) {
                            if (angular.isDefined(response.data)) {
                                vm.user_address = response.data;
                                if (response.data.length > 0) {
                                    vm.address_form = true;
                                }
                                vm.near_address_id = "";
                                angular.forEach(response.data, function(value) {
                                    if (value.is_outside_delivery_area === true) {
                                        value.disable_address = false;
                                        if (vm.near_address_id === '') {
                                            vm.near_address_id = value.id;
                                            vm.addressType(0);
                                            vm.pickAddress(value.id);
                                        }
                                    } else {
                                        value.disable_address = true;
                                    }
                                });
                            }
                        });
                    }
                    paymentGateways.get({}, function(payment_response) {
                        vm.group_gateway_id = "";
                        if (payment_response.error.code === 0) {
                            if (payment_response.wallet) {
                                vm.wallet_enabled = payment_response.wallet.enabled;
                            }
                            if (payment_response.cod && vm.guest_login == false) {
                                vm.cod_enabled = payment_response.cod.enabled;
                            }
                            if (payment_response.sudopay) {
                                vm.zazpay_enabled = payment_response.sudopay.enabled;
                            }
                            if (payment_response.Paypal) {
                                vm.paypal_gateways = {
                                    'credit_card': {
                                        'pane_enabled': true,
                                        'thumb_url': ''
                                    },
                                    'paypal': {
                                        'pane_enabled': false,
                                        'thumb_url': ''
                                    }
                                };
                                vm.paypal_enabled = payment_response.Paypal.enabled;;
                            }
                            angular.forEach(payment_response.sudopay.gateways, function(gateway_group_value, gateway_group_key) {
                                if (gateway_group_key === 0) {
                                    vm.group_gateway_id = gateway_group_value.id;
                                    vm.first_gateway_id = gateway_group_value.id;
                                }
                                //jshint unused:false
                                angular.forEach(gateway_group_value.gateways, function(payment_geteway_value, payment_geteway_key) {
                                    var payment_gateway = {};
                                    var suffix = 'sp_';
                                    if (gateway_group_key === 0) {
                                        vm.sel_payment_gateway = 'sp_' + payment_geteway_value.id;
                                    }
                                    suffix += payment_geteway_value.id;
                                    payment_gateway.id = payment_geteway_value.id;
                                    payment_gateway.payment_id = suffix;
                                    payment_gateway.group_id = gateway_group_value.id;
                                    payment_gateway.display_name = payment_geteway_value.display_name;
                                    payment_gateway.thumb_url = payment_geteway_value.thumb_url;
                                    payment_gateway.suffix = payment_geteway_value._form_fields._extends_tpl.join();
                                    payment_gateway.form_fields = payment_geteway_value._form_fields._extends_tpl.join();
                                    payment_gateway.instruction_for_manual = payment_geteway_value.instruction_for_manual;
                                    payment_gateways.push(payment_gateway);
                                    if (vm.paypal_enabled == true) {
                                        if (parseInt(payment_geteway_value.id) == 3140) {
                                            vm.paypal_gateways.credit_card.thumb_url = gateway_group_value.thumb_url;
                                        }
                                        if (parseInt(payment_geteway_value.id) == 1) {
                                            vm.paypal_gateways.paypal.thumb_url = gateway_group_value.thumb_url;
                                        }
                                    }
                                });
                            });
                            vm.gateway_groups = payment_response.sudopay.gateways;
                            vm.payment_gateways = payment_gateways;
                            vm.form_fields_tpls = payment_response.sudopay._form_fields_tpls;
                            vm.show_form = [];
                            vm.form_fields = [];
                            angular.forEach(vm.form_fields_tpls, function(key, value) {
                                if (value === 'buyer') {
                                    vm.form_fields[value] = 'scripts/plugins/Common/ZazPay/views/default/buyer.html';
                                }
                                if (value === 'credit_card') {
                                    vm.form_fields[value] = 'scripts/plugins/Common/ZazPay/views/default/credit_card.html';
                                }
                                if (value === 'manual') {
                                    vm.form_fields[value] = 'scripts/plugins/Common/ZazPay/views/default/manual.html';
                                }
                                vm.show_form[value] = true;
                            });
                            vm.gateway_id = 1;
                        }
                    });
                });
            }
        };
        vm.paneChanged = function(pane) {
            vm.show_paypal_credit_form = true;
            if (pane === 'Manual / Offline') {
                vm.payment_note_enabled = true;
            }
            var keepGoing = true;
            vm.buyer = {};
            vm.PaymentForm.$setPristine();
            vm.PaymentForm.$setUntouched();
            angular.forEach(vm.form_fields_tpls, function(key, value) {
                vm.show_form[value] = false;
            });
            vm.gateway_id = 1;
            angular.forEach(vm.gateway_groups, function(res) {
                if (res.display_name === pane && pane !== 'Wallet') {
                    var selPayment = '';
                    angular.forEach(vm.payment_gateways, function(response) {
                        if (keepGoing) {
                            if (response.group_id === res.id) {
                                selPayment = response;
                                keepGoing = false;
                                vm.rdoclick(selPayment.id, selPayment.form_fields);
                            }
                        }
                    });
                    vm.sel_payment_gateway = "sp_" + selPayment.id;
                    vm.group_gateway_id = selPayment.group_id;
                }
            });
            if (pane === 'Wallet') {
                vm.gateway_id = PAYMENT_GATEWAYS.Wallet;
            }
            if (pane === 'cod') {
                vm.gateway_id = PAYMENT_GATEWAYS.Cod;
            }
            if (pane === 'Paypal') {
                vm.paypal_gateways.credit_card.pane_enabled = true;
                vm.paypalPaneChanged('creditcard');
                vm.gateway_id = PAYMENT_GATEWAYS.PayPal;
            }
        };
        vm.paypalPaneChanged = function(pane) {
            vm.buyer = {};
            vm.PaymentForm.$setPristine();
            vm.PaymentForm.$setUntouched();
            vm.show_paypal_credit_form = false;
            if (pane === 'paypal') {
                vm.paypal_gateways.credit_card.pane_enabled = false;
                vm.show_paypal_credit_form = true;
            }
        }
        vm.rdoclick = function(res, res1) {
            vm.paynow_is_disabled = false;
            vm.sel_payment_gateway = "sp_" + res;
            vm.array = res1.split(',');
            angular.forEach(vm.array, function(value) {
                vm.show_form[value] = true;
            });
        };
        vm.addressType = function(address_type) {
            vm.existing_new_address = address_type;
        };
        vm.pickAddress = function(address_id) {
            vm.user_address_id = address_id;
        };
        /*if ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) {
            if ($rootScope.user.address !== null && $rootScope.user.city_id !== 0 && $rootScope.user.state_id !== 0 && $rootScope.user.country_id !== 0 && $rootScope.user.zip_code !== null) {
                vm.buyer.address = $rootScope.user.address;
                vm.buyer.city = $rootScope.city;
                vm.buyer.state = $rootScope.state;
                vm.buyer.country = $rootScope.country;
                vm.buyer.zip_code = $rootScope.zip_code;
            } else {
                if (parseInt(vm.is_pickup_or_delivery) === 0) {
                    vm.payer_form_enabled = true;
                }
            }
        } else {
            vm.payer_form_enabled = true;
        }*/
        vm.PaymentSubmit = function(form) {
            var payment_id = '';
            if (vm.sel_payment_gateway && vm.gateway_id === 1) {
                payment_id = vm.sel_payment_gateway.split('_')[1];
            }
            vm.buyer.user_id = (vm.guest_login == true) ? vm.guest_login_details.id : $rootScope.user.id;
            vm.buyer.cookie_id = vm.current_cookie_id;
            vm.buyer.is_allow_users_to_door_delivery_order = vm.is_pickup_or_delivery;
            vm.buyer.later_delivery_date = vm.later_delivery_date;
            if (angular.isDefined($window.localStorage.getItem('comment')) && $window.localStorage.getItem('comment') !== null) {
                vm.buyer.comment = $window.localStorage.getItem('comment');
            }
            if (parseInt(vm.existing_new_address) === 1 && parseInt(vm.is_pickup_or_delivery) === 1 && vm.gateway_id !== 4) {
                vm.user_address_add.city = {};
                vm.user_address_add.state = {};
                vm.user_address_add.country = {};
                vm.user_address_add.city.name = $rootScope.city;
                vm.user_address_add.state.name = $rootScope.state;
                vm.user_address_add.country.iso2 = $rootScope.country;
                vm.user_address_add.zip_code = $rootScope.zip_code;
                vm.user_address_add.latitude = $rootScope.lat;
                vm.user_address_add.longitude = $rootScope.lang;
                // vm.buyer.user_address = vm.user_address_add;
                // vm.buyer.address = vm.user_address_add.building_address;
                // vm.buyer.city = $rootScope.city;
                // vm.buyer.state = $rootScope.state;
                // vm.buyer.country = $rootScope.country;
                // vm.buyer.zip_code = $rootScope.zip_code;
            } else {
                vm.buyer.address_id = vm.user_address_id;
            }
            vm.buyer.payment_gateway_id = vm.gateway_id;
            if (vm.gateway_id === 1) {
                vm.buyer.gateway_id = payment_id;
            } else {
                vm.buyer.gateway_id = vm.gateway_id;
            }
            if (vm.coupon_code !== false) {
                vm.buyer.coupon_code = vm.coupon_code;
            }
            if (angular.isDefined(vm.buyer.cancel_url) && angular.isDefined(vm.buyer.success_url)) {
                delete vm.buyer.success_url;
                delete vm.buyer.cancel_url;
            }
            if ($location.absUrl().indexOf('app') !== -1) {
                vm.buyer.cancel_url = $window.location.protocol + '//' + $window.location.host + '/app/checkout?error_code=512';
                vm.buyer.success_url = $window.location.protocol + '//' + $window.location.host + '/app/orders?error_code=0';
            } else {
                vm.buyer.cancel_url = $window.location.protocol + '//' + $window.location.host + '/checkout?error_code=512';
                vm.buyer.success_url = $window.location.protocol + '//' + $window.location.host + '/orders?error_code=0';
            }
            if (vm.guest_login == true) {
                vm.buyer.success_url = $window.location.protocol + '//' + $window.location.host + '/orders/##TRACKID##/track?error_code=0';
            }
            if (angular.isDefined(vm.buyer.credit_card_expired) && (vm.buyer.credit_card_expired.month || vm.buyer.credit_card_expired.year)) {
                vm.buyer.credit_card_expire = vm.buyer.credit_card_expired.month + "/" + vm.buyer.credit_card_expired.year;
            }
            if (form.$valid) {
                vm.paynow_is_disabled = true;
                var flashMessage;
                if (vm.total > vm.user_available_balance && vm.gateway_id === 2) {
                    flashMessage = $filter("translate")("Your wallet has insufficient money.");
                    flash.set(flashMessage, 'error', false);
                    vm.paynow_is_disabled = false;
                    return true;
                }
                var params = {};
                params.user_id = (vm.guest_login == true) ? vm.guest_login_details.id : $rootScope.user.id;
                params.cookie_id = vm.current_cookie_id;
                checkout.create(params, vm.buyer, function(response) {
                    if (response.error.code === 0) {
                        $rootScope.removeCartCookie(); 
                        if (angular.isDefined(response.redirect_url)) {
                            $window.location.href = response.redirect_url;
                            return;
                        } else if (response.payment_response.status === 'Pending') {
                            flashMessage = $filter("translate")("Your payment is in pending.");
                            flash.set(flashMessage, 'error', false);
                        } else if (response.payment_response.status === 'Captured') {
                            flashMessage = $filter("translate")("Your order placed successfully.");
                            flash.set(flashMessage, 'success', false);
                        } else if (response.payment_response.error.code === 0) {
                            flashMessage = $filter("translate")("Payment successfully completed.");
                            flash.set(flashMessage, 'success', false);
                        } else if (response.payment_response.error.code === 512) {
                            flashMessage = $filter("translate")("Payment Failed. Please, try again.");
                            flash.set(flashMessage, 'error', false);
                        }
                        if (vm.guest_login == true) {
                            if (angular.isDefined(response.data.track_id)) {
                                $state.go('order_track', {
                                    'order_id': response.data.track_id
                                });
                            }
                        } else {
                            $state.go('orders');
                        }
                    } else {
                        flashMessage = $filter("translate")("We are unable to place your order. Please try again.");
                        flash.set(flashMessage, 'error', false);
                    }
                    vm.paynow_is_disabled = false;
                }, function(error) {
                    if (angular.isDefined(error.data.error.message) || error.data.error.message !== null) {
                        flash.set($filter("translate")(error.data.error.message), 'error', false);
                    }
                    vm.paynow_is_disabled = false;
                });
            }
        };
        vm.calculate = function() {
            var sub_total = 0;
            var addon_total = 0;
            var discount = 0;
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
            vm.finaltotal = parseFloat(vm.sub_total) + parseFloat(vm.restaurant.delivery_charge) + parseFloat(vm.sales_tax)
            if (vm.coupon_code) {
                if (vm.coupon.is_flat_discount_in_amount === 0) {
                    vm.discountAmount = (parseFloat(vm.finaltotal) * parseFloat(vm.coupon.discount)) / 100;
                    vm.total = parseFloat(vm.finaltotal) - parseFloat(vm.discountAmount);
                } else {
                    vm.discountAmount = parseFloat(vm.coupon.discount)
                    vm.total = parseFloat(vm.finaltotal) - parseFloat(vm.coupon.discount);
                }
            } else {
                vm.total = parseFloat(vm.finaltotal);
            }
        }
        var params = {}; 
        params.filter = '{"skip":"0","limit":"all"}' 
        countries.get(params,function(response) {
            if (angular.isDefined(response.data)) {
                vm.countries = response.data;
            }
        });
        vm.index();
        vm.applyCouponCode = function(coupon_code) {
            if (coupon_code) {
                var params = {};
                params.coupon_code = coupon_code;
                getCoupons.get(params, function(response) {
                    if (response.error.code === 0) {
                        vm.coupon_code = response.data.coupon_code;
                        vm.coupon = response.data;
                        vm.calculate();
                    } else {
                        flash.set(response.error.message, 'error', false);
                    }
                })
            }
        }
        vm.revertCoupon = function() {
            vm.coupon_code = false;
            vm.coupon = {};
            vm.calculate();
        }
    });