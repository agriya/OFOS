'use strict';
/**
 * @ngdoc service
 * @name ofosApp.states
 * @description
 * # states
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('states', function($resource) {
        return $resource('/api/v1/states', {}, {
            get: {
                method: 'GET'
            }
        });
    });