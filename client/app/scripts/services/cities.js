'use strict';
/**
 * @ngdoc service
 * @name ofosApp.cities
 * @description
 * # cities
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('cities', function($resource) {
        return $resource('/api/v1/cities', {}, {
            get: {
                method: 'GET'
            }
        });
    });