'use strict';
/**
 * @ngdoc service
 * @name ofosApp.sessionService
 * @description
 * # sessionService
 * Factory in the ofosApp.
 */
angular.module('ofos')
    .factory('interceptor', ['$q', '$location', '$injector', '$window', 'user_types', '$rootScope', '$timeout', '$cookies', function($q, $location, $injector, $window, userTypes, $rootScope, $timeout, $cookies) {
        return {
            // On response success
            response: function(response) {
                if (angular.isDefined(response.data)) {
                    if (angular.isDefined(response.data.error_message) && parseInt(response.data.error) === 1 && response.data.error_message === 'Authentication failed') {
                        $cookies.remove('auth', {
                            path: '/'
                        });
                        $cookies.remove('token', {
                            path: '/'
                        });
                        window.location = "#/login";
                    }
                }
                // Return the response or promise.
                return response || $q.when(response);
            },
            // On response failture
            responseError: function(response) {
                if (response.status === 401) {
                    if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined) {
                        var auth = JSON.parse($cookies.get("auth"));
                        var refresh_token = auth.refresh_token;
                        if (refresh_token === null || refresh_token === '' || refresh_token === undefined) {
                            $cookies.remove('auth', {
                                path: '/'
                            });
                            $cookies.remove('token', {
                                path: '/'
                            });
                            var redirectto = $location.absUrl()
                                .split('/#/');
                            redirectto = redirectto[0] + '#/login';
                            $rootScope.refresh_token_loading = false;
                            window.location.href = redirectto;
                        } else {
                            if ($rootScope.refresh_token_loading !== true) {
                                //jshint unused:false
                                $rootScope.refresh_token_loading = true;
                                var params = {};
                                var auth = JSON.parse($cookies.get("auth"));
                                params.token = auth.refresh_token;
                                var refreshToken = $injector.get('refreshToken');
                                refreshToken.get(params, function(response) {
                                    if (angular.isDefined(response.access_token)) {
                                        $rootScope.refresh_token_loading = false;
                                        $cookies.put('token', response.access_token, {
                                            path: '/'
                                        });
                                    } else {
                                        $cookies.remove('auth', {
                                            path: '/'
                                        });
                                        $cookies.remove('token', {
                                            path: '/'
                                        });
                                        //var redirectto = $location.absUrl().split('/#/');
                                        //redirectto = redirectto[0] + '#/login';
                                        $rootScope.refresh_token_loading = false;
                                        window.location.href = redirectto;
                                    }
                                    $timeout(function() {
                                        $window.location.reload();
                                    }, 1000);
                                });
                            }
                        }
                    }
                }
                // Return the promise rejection.
                return $q.reject(response);
            },
            request: function(config) {
                config.headers['x-ag-app-id'] = "4542632501382585";
                config.headers['x-ag-app-secret'] = "3f7C4l1Y2b0S6a7L8c1E7B3Jo3";                
                var exceptional_array = ['/api/v1/stats', '/api/v1/settings', '/api/v1/users/logout', '/api/v1/oauth/refresh_token', '/api/v1/attachments', '/api/v1/cuisines', '/api/v1/restaurants', '/api/v1/order_statuses'];
                if ($cookies.get('auth') !== null && $cookies.get('auth') !== undefined) {
                    var auth = angular.fromJson($cookies.get('auth'));
                }
                if(config.url.indexOf('/api/v1/shops') !== -1) {
                    config.url = config.url.replace('/api/v1/shops', '/api/v1/restaurants');
                }
                if(config.url.indexOf('/api/v1/own_restaurant_delivery_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/own_restaurant_delivery_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/own_restaurant_delivery_persons') !== -1) {
                    config.url = config.url.replace('/api/v1/own_restaurant_delivery_persons', '/api/v1/restaurant_delivery_persons');
                }
                if(config.url.indexOf('/api/v1/assingned_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/assingned_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/delivered_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/delivered_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/own_restaurant_assingned_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/own_restaurant_assingned_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/own_restaurant_delivered_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/own_restaurant_delivered_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/own_restaurant_restaurant_supervisors') !== -1) {
                    config.url = config.url.replace('/api/v1/own_restaurant_restaurant_supervisors', '/api/v1/restaurant_supervisors');
                }
                if(config.url.indexOf('/api/v1/processing_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/processing_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/own_restaurant_processing_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/own_restaurant_processing_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/pending_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/pending_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/own_restaurant_pending_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/own_restaurant_pending_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/out_for_delivery_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/out_for_delivery_orders', '/api/v1/orders');
                }
                if(config.url.indexOf('/api/v1/own_restaurant_out_for_delivery_orders') !== -1) {
                    config.url = config.url.replace('/api/v1/own_restaurant_out_for_delivery_orders', '/api/v1/orders');
                }
                return config;
            },
        };
    }]);