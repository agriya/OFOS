/*globals $:false */
'use strict';
/**
 * @ngdoc overview
 * @name ofosApp
 * @description
 * # ofosApp
 *
 * Main module of the application.
 */
angular.module('ofosApp', [
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
    .constant('ORDER_STATUS', {
        PaymentPending: 1,
        PaymentFailed: 2,
        Pending: 3,
        Rejected: 4,
        Processing: 5,
        DeliveryPersonAssigned: 6,
        Delivered: 7,
        Reviewed: 8,
        AwaitingCodValidation: 9,
        Cancel: 10
    })
    .constant('PAYMENT_GATEWAYS', {
        ZazPay: 1,
        Wallet: 2,
        Cod: 3,
        PayPal: 4
    })
    .config(function ($stateProvider, $urlRouterProvider, $translateProvider) {
        //$translateProvider.translations('en', translations).preferredLanguage('en');
        $translateProvider.useStaticFilesLoader({
            prefix: 'scripts/l10n/',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('en');
        $translateProvider.useLocalStorage(); // saves selected language to localStorage
        // Enable escaping of HTML
        $translateProvider.useSanitizeValueStrategy('escape');
        //	$translateProvider.useCookieStorage();
    })
    .config(function (tmhDynamicLocaleProvider) {
        tmhDynamicLocaleProvider.localeLocationPattern('scripts/l10n/angular-i18n/angular-locale_{{locale}}.js');
    })
    .config(function ($authProvider, $windowProvider) {
        var $window = $windowProvider.$get();
        var params = {};
        params.filter = '{"where":{"is_active":1}}';
        $.ajax({
            url: '/api/v1/providers',
            data: params,
            type: "GET",
            headers: { 'x-ag-app-id': '4542632501382585', 'x-ag-app-secret': '3f7C4l1Y2b0S6a7L8c1E7B3Jo3' },
            success: function (response) {
                var credentials = {};
                var url = '';
                var providers = response;
                angular.forEach(providers.data, function (res, i) {
                    //jshint unused:false
                    url = $window.location.protocol + '//' + $window.location.host + '/api/v1/users/social_login?type=' + res.slug;
                    credentials = {
                        clientId: res.api_key,
                        redirectUri: url,
                        url: url
                    };
                    if (res.slug === 'facebook') {
                        $authProvider.facebook(credentials);
                    }
                    if (res.slug === 'google') {
                        $authProvider.google(credentials);
                    }

                    if (res.slug === 'twitter') {
                        $authProvider.twitter(credentials);
                    }
                });
            }
        });

    })
    .config(function ($locationProvider) {
        $locationProvider.html5Mode(true);
        // $locationProvider.hashPrefix('!');
    })
    .config(function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/');
        $stateProvider.state('home', {
            url: '/?res_index',
            templateUrl: 'views/home.html',
            controller: 'HomeController as vm',
        }).state('contact', {
            url: '/contact',
            templateUrl: 'views/contact.html',
        }).state('maintenance', {
            url: '/maintenance',
            templateUrl: 'views/maintenance.html',
        }).state('pages_view', {
            url: '/pages/:id/:slug',
            templateUrl: 'views/pages_view.html',
        });
    })
    .config(function (growlProvider) {
        growlProvider.onlyUniqueMessages(true);
        growlProvider.globalTimeToLive(5000);
        growlProvider.globalPosition('top-center');
        growlProvider.globalDisableCountDown(true);
    })
    .run(function ($rootScope, $location, $cookies, SiteSettings) {
        $rootScope.settings = {};
        angular.forEach(SiteSettings.data, function (value) {
            $rootScope.settings[value.name] = value.value;
        });
        
        $rootScope.$on('stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
            //jshint unused:false
            var url = toState.name;
            var exception_arr = ['home', 'users_login', 'users_register', 'users_forgot_password', 'search_restaurant_by_city_cuisine', 'search_restaurant', 'restaurant_view', 'pages_view', 'contact', 'get_email', 'users_activation', 'review_order', 'order_track'];
            if (angular.isDefined(url)) {
                if (exception_arr.indexOf(url) === -1 && ($cookies.get("auth") === null || angular.isUndefined($cookies.get("auth")))) {
                    $location.path('/users/login');
                }
            }
        });

        $rootScope.$on('$viewContentLoaded', function () {
            $('div.loader')
                .hide();
            $('body')
                .removeClass('site-loading');
                if (parseInt($rootScope.settings.SITE_OFFLINE) === 1) {
                     $location.path('/maintenance');
                }
        });
        var unregisterStateChangeSuccess = $rootScope.$on('stateChangeSuccess', function () {
            angular.element('html, body')
                .stop(true, true)
                .animate({
                    scrollTop: 0
                }, 600);
        });
        $rootScope.$on("$destroy", function () {
            //unregisterStateChangeStart();
            unregisterStateChangeSuccess();
        });
    })
    .config(function ($httpProvider) {
        $httpProvider.interceptors.push('interceptor');
        $httpProvider.interceptors.push('oauthTokenInjector');
    })
    .config(function (cfpLoadingBarProvider) {
        // true is the default, but I left this here as an example:
        cfpLoadingBarProvider.includeSpinner = false;
    })
    .factory('interceptor', function ($q, $location, flash, $window, $timeout, $rootScope, $filter, $cookies) {
        return {
            // On response success
            response: function (response) {
                if (angular.isDefined(response.data)) {
                    if (angular.isDefined(response.data.thrid_party_login)) {
                        if (angular.isDefined(response.data.error)) {
                            if (angular.isDefined(response.data.error.code) && parseInt(response.data.error.code) === 0) {
                                $cookies.put('auth', angular.toJson(response.data.user), {
                                    path: '/'
                                });
                                $timeout(function () {
                                    location.reload(true);
                                });
                            } else {
                                var flashMessage;
                                flashMessage = $filter("translate")("Unable to connect your account.");
                                flash.set(flashMessage, 'error', false);
                            }
                        }
                    }
                }
                // Return the response or promise.
                return response || $q.when(response);
            },
            // On response failture
            responseError: function (response) {
                // Return the promise rejection.
                if (response.status === 401) {
                    if ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) {
                        var auth = angular.fromJson($cookies.get("auth"));
                        var refresh_token = auth.refresh_token;
                        if (refresh_token === null || refresh_token === '' || angular.isUndefined(refresh_token)) {
                            $cookies.remove('auth', {
                                path: '/'
                            });
                            $cookies.remove('token', {
                                path: '/'
                            });
                            var redirectto = $location.absUrl()
                                .split('/');
                            redirectto = redirectto[0] + '/users/login';
                            $rootScope.refresh_token_loading = false;
                            $window.location.href = redirectto;
                        } else {
                            if ($rootScope.refresh_token_loading !== true) {
                                $rootScope.$broadcast('useRefreshToken');
                            }
                        }
                    }
                }
                return $q.reject(response);
            }
        };
    })
    .filter('unsafe', function ($sce) {
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