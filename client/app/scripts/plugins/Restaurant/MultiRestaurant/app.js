
'use strict';
angular.module('ofosApp.Restaurant.MultiRestaurant', [
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
        $stateProvider.state('search_restaurant_by_city_cuisine', {
            url: '/restaurants/cuisine/{cuisine}/{type_manner}',
            templateUrl: 'scripts/plugins/Restaurant/MultiRestaurant/views/default/search.html',
            controller: 'SearchController as vm',
        })
            .state('search_restaurant', {
                url: '/restaurants?lat&lang&page&q&filter&cuisine&rating',
                templateUrl: 'scripts/plugins/Restaurant/MultiRestaurant/views/default/search.html',
                controller: 'SearchController as vm',
                reloadOnSearch: false,
            })
            .state('restaurant_view', {
                url: '/restaurant/:id/:slug',
                templateUrl: 'scripts/plugins/Restaurant/MultiRestaurant/views/default/restaurant_view.html',
                controller: 'RestaurantViewController as vm',
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
