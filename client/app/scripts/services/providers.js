'use strict';
/**
 * @ngdoc service
 * @name ofosApp.providers
 * @description
 * # providers
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('providers', function($resource) {
        return $resource('/api/v1/providers',{}, {
            get: {
                method: 'GET'
            }
        });
    });