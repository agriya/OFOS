'use strict';
/**
 * @ngdoc service
 * @name ofosApp.countries
 * @description
 * # countries
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('countries', function($resource) {
        return $resource('/api/v1/countries', {}, {
            get: {
                method: 'GET'
            }
        });
    });