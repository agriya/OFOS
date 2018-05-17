
'use strict';
angular.module('ofosApp.Order.Order', [
    'ngResource',
    'ngSanitize',
    'satellizer',
    'ngAnimate',
    'ui.bootstrap',
    'ui.bootstrap.datetimepicker',
    'ui.router',
    'angular-growl',
    'google.places',
    'angular.filter',
    'ngCookies',
    'angular-md5',
    'ui.select2',
    'http-auth-interceptor',
    'vcRecaptcha',
    'angulartics',
    'pascalprecht.translate',
    'angulartics.google.analytics',
    'tmh.dynamicLocale',
    'ngMap',
    'chieffancypants.loadingBar',
    'payment'
])
    .config(function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/');
        $stateProvider.state('users_login', {
            url: '/users/login',
            templateUrl: 'scripts/plugins/Order/Order/views/default/users_login.html',
        }).state('users_register', {
            url: '/users/register',
            templateUrl: 'scripts/plugins/Order/Order/views/default/users_register.html',
        }).state('guest_register', {
            url: '/guest/register',
            templateUrl: 'scripts/plugins/Order/Order/views/default/guest_register.html',
        }).state('users_logout', {
            url: '/users/logout',
            controller: 'UsersLogoutController as vm',
        }).state('users_forgot_password', {
            url: '/users/forgot_password',
            templateUrl: 'scripts/plugins/Order/Order/views/default/users_forgot_password.html',
        }).state('users_activation', {
            url: '/users/:user_id/activation/:hash',
            templateUrl: 'scripts/plugins/Order/Order/views/default/users_activation.html',
        }).state('get_email', {
            url: '/users/get_email',
            templateUrl: 'scripts/plugins/Order/Order/views/default/get_email.html',
        }).state('users_settings', {
            url: '/users/settings',
            templateUrl: 'scripts/plugins/Order/Order/views/default/users_settings.html',
        }).state('users_change_password', {
            url: '/users/change_password',
            templateUrl: 'scripts/plugins/Order/Order/views/default/users_change_password.html',
        }).state('orders', {
            url: '/orders?error_code&page',
            templateUrl: 'scripts/plugins/Order/Order/views/default/orders.html',
            reloadOnSearch: false,
        }).state('order_track', {
            url: '/orders/:order_id/track?error_code',
            templateUrl: 'scripts/plugins/Order/Order/views/default/order_track.html',
        }).state('user_transactions', {
            url: '/users/transactions',
            templateUrl: 'scripts/plugins/Order/Order/views/default/user_transactions.html',
        }).state('users_address_add', {
            url: '/users/addresses/add',
            templateUrl: 'scripts/plugins/Order/Order/views/default/users_address_add.html',
        }).state('users_addresses', {
            url: '/users/addresses',
            templateUrl: 'scripts/plugins/Order/Order/views/default/users_addresses.html',
        }).state('cart', {
            url: '/cart',
            templateUrl: 'scripts/plugins/Order/Order/views/default/cart.html',
        }).state('review_order', {
            url: '/review_order',
            controller: 'ReviewOrderController as vm',
            templateUrl: 'scripts/plugins/Order/Order/views/default/review_order.html',
        }).state('checkout', {
            url: '/checkout?error_code',
            controller: 'CheckoutController as vm',
            templateUrl: 'scripts/plugins/Order/Order/views/default/checkout.html',
        }).state('users_become_restaurant', {
            url: '/users/become_restaurant',
            controller: 'UsersBecomeRestaurantController as vm',
            templateUrl: 'scripts/plugins/Order/Order/views/default/users_become_restaurant.html',
        })
    }).filter('unsafe', function ($sce) {
        return function (val) {
            return $sce.trustAsHtml(val);
        };
    })
    .filter('split', function () {
        return function (input, splitChar) {
            var _input = input.split(splitChar);
            _input.pop();
            return _input.join(':');
        };
    })
    .filter('spaceless', function () {
        return function (input) {
            if (input) {
                return input.replace(/\s+/g, '-');
            }
        };
    });
