'use strict';
/**
 * @ngdoc service
 * @name ofosApp.sessionService
 * @description
 * # sessionService
 * Factory in the ofosApp.
 */
angular.module('ofos')
    .service('adminTokenService', function($rootScope, $http, $window, $q, $cookies) {
        //jshint unused:false
        var promise;
        var promiseSettings;
        var deferred = $q.defer();
        if (angular.isUndefined($rootScope.settings)) {
            $rootScope.settings = {};
            var params = {};
            promiseSettings = $http({
                    method: 'GET',
                    url: '/api/v1/settings?filter={"limit":100,"skip":"0","where":{"is_front_end_access":1},"fields":{"name":"true","value":"true"}}',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    params: params
                })
                .success(function(response) {
                    if (angular.isDefined(response.data)) {
                        var settings = {};
                        angular.forEach(response.data, function(value, key) {
                            //jshint unused:false
                            $rootScope.settings[value.name] = value.value;
                            settings[value.name] = value.value;
                        });
                        if ($cookies.get("SETTINGS") === null || $cookies.get("SETTINGS") === undefined) {
                            $cookies.put("SETTINGS", JSON.stringify(settings), {
                                path: '/'
                            });
                        }
                    }
                });
        } else {
            promiseSettings = true;
        }
        return {
            promiseSettings: promiseSettings
        };
    });