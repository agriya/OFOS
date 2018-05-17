'use strict';
/**
 * @ngdoc service
 * @name ofosApp.languages
 * @description
 * # languages
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Common.Translation')
    .factory('languages', function($resource) {
        return $resource('/api/v1/languages', {}, {
            get: {
                method: 'GET'
            }
        });
    });