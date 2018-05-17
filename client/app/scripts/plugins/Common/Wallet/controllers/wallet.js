'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:WalletController
 * @description
 * # WalletController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Common.Wallet')
    .controller('WalletController', function($rootScope, $window, countries, states, cities, usersAddresses, wallet, flash, $location, $filter, $state, paymentGateways, userSettings, $stateParams, PAYMENT_GATEWAYS) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Add to wallet");
        vm.minimum_wallet_amount = $rootScope.settings.WALLET_MIN_WALLET_AMOUNT;
        vm.maximum_wallet_amount = $rootScope.settings.WALLET_MAX_WALLET_AMOUNT;
        vm.user_available_balance = $rootScope.user.available_wallet_amount;
        vm.buyer = {};
        vm.paynow_is_disabled = false;
        vm.show_paypal_credit_form = true;
        vm.payment_note_enabled = false;
        vm.payer_form_enabled = true;
        vm.is_wallet_page = true;
        vm.existing_new_address = 1;
        vm.user_address_id = "";
        vm.user_address_add = {};
        vm.save_btn = false;
        vm.first_gateway_id = "";
        var flashMessage;
        if (parseInt($stateParams.error_code) === 512) {
            flashMessage = $filter("translate")("Amount could not be added, please try again.");
            flash.set(flashMessage, 'error', false);
            $location.search({});
        } else if (parseInt($stateParams.error_code) === 0) {
            flashMessage = $filter("translate")("Amount Added successfully.");
            flash.set(flashMessage, 'success', false);
            $location.search({});
        }
        vm.index = function() {
             vm.loader = true;
            var params = {"filter":{"skip":0,"limit":"all"}};
            countries.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.countries = response.data;
                }
            });
            var params = {};
            params.id = $rootScope.user.id;
            userSettings.get(params, function(response) {
                vm.user_available_balance = response.data.available_wallet_amount;
            });
            var payment_gateways = [];
            paymentGateways.get({}, function(payment_response) {
                vm.group_gateway_id = "";
                if (payment_response.error.code === 0) {
                    if (payment_response.wallet) {
                        vm.wallet_enabled = payment_response.wallet.enabled;
                    }
                    if (payment_response.cod) {
                        vm.cod_enabled = false;
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
             vm.loader = false;
        };
        vm.paneChanged = function(pane) {
            if (pane === 'Manual / Offline') {
                vm.payment_note_enabled = true;
            }
            var keepGoing = true;
            vm.buyer = {};
            vm.WalletForm.$setPristine();
            vm.WalletForm.$setUntouched();
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
            vm.WalletForm.$setPristine();
            vm.WalletForm.$setUntouched();
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
        vm.WalletFormSubmit = function(form) {
            var payment_id = '';
            if (vm.sel_payment_gateway && vm.gateway_id === 1) {
                payment_id = vm.sel_payment_gateway.split('_')[1];
            }
            vm.buyer.user_id = $rootScope.user.id;
            vm.buyer.amount = vm.amount;
            vm.buyer.payment_gateway_id = vm.gateway_id;
             if (vm.gateway_id === 1) {
                vm.buyer.gateway_id = payment_id;
            } else {
                vm.buyer.gateway_id = vm.gateway_id;
            }
            if (angular.isDefined(vm.buyer.cancel_url) && angular.isDefined(vm.buyer.success_url)) {
                delete vm.buyer.success_url;
                delete vm.buyer.cancel_url;
            }
            if ($location.absUrl().indexOf('app') !== -1) {
                vm.buyer.cancel_url = $window.location.protocol + '//' + $window.location.host + '/app/wallets?error_code=512';
                vm.buyer.success_url = $window.location.protocol + '//' + $window.location.host + '/app/wallets?error_code=0';
            } else {
                vm.buyer.cancel_url = $window.location.protocol + '//' + $window.location.host + '/wallets?error_code=512';
                vm.buyer.success_url = $window.location.protocol + '//' + $window.location.host + '/wallets?error_code=0';
            }
            if (angular.isDefined(vm.buyer.credit_card_expired) && (vm.buyer.credit_card_expired.month || vm.buyer.credit_card_expired.year)) {
                vm.buyer.credit_card_expire = vm.buyer.credit_card_expired.month + "/" + vm.buyer.credit_card_expired.year;
            }
            if (form) {
                vm.paynow_is_disabled = true;
                wallet.create(vm.buyer, function(response) {
                    if (response.error.code === 0) {
                        if (angular.isDefined(response.redirect_url)) {
                            $window.location.href = response.redirect_url;
                        } else if (response.payment_response.status === 'Pending') {
                            flashMessage = $filter("translate")("Your request is in pending.");
                            flash.set(flashMessage, 'error', false);
                            $state.reload();
                        } else if (response.payment_response.status === 'Captured') {
                            flashMessage = $filter("translate")("Amount added successfully.");
                            flash.set(flashMessage, 'success', false);
                            $state.reload();
                        } else if (response.error.code === 0) {
                            flashMessage = $filter("translate")("Payment successfully completed.");
                            flash.set(flashMessage, 'success', false);
                            $state.reload();
                        } else if (response.error.code === 512) {
                            flashMessage = $filter("translate")("Process Failed. Please, try again.");
                            flash.set(flashMessage, 'error', false);
                        }
                    } else {
                        flashMessage = $filter("translate")("We are unable to process your request. Please try again.");
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
        vm.index();
    });