'use strict';
/**
 * @ngdoc service
 * @name ofosApp.page
 * @description
 * # page
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('pages', function($resource) {
        return $resource('/api/v1/pages',{ filter: '@filter'}, {}, {
            get: {
                method: 'GET'
            }
        });
    });