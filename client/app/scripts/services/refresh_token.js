'use strict';
/**
 * @ngdoc service
 * @name ofosApp.refreshToken
 * @description
 * # refreshToken
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('refreshToken', function($resource) {
        return $resource('/api/v1/oauth/refresh_token', {}, {
            get: {
                method: 'GET'
            }
        });
    });