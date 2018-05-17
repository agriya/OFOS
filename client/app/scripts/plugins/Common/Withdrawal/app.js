
'use strict';
angular.module('ofosApp.Common.Withdrawal', [
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
        $stateProvider.state('user_cash_withdrawals', {
            url: '/users/cash_withdrawals',
            templateUrl: 'scripts/plugins/Common/Withdrawal/views/default/cash_withdrawals.html',
        }).state('money_transfer_account', {
            url: '/users/money_transfer_account',
            templateUrl: 'scripts/plugins/Common/Withdrawal/views/default/money_transfer_account.html',
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
