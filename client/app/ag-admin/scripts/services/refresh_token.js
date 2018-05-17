'use strict';
/**
 * @ngdoc service
 * @name ofos.refreshToken
 * @description
 * # refreshToken
 * Factory in the ofosApp.
 */
angular.module('ofos')
    .factory('refreshToken', ['$resource', function($resource) {
        return $resource('/api/v1/oauth/refresh_token', {}, {
            get: {
                method: 'GET'
            }
        });
  }]);