'use strict';
/**
 * @ngdoc service
 * @name ofosApp.page
 * @description
 * # page
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('page', function($resource) {
        return $resource('/api/v1/pages/:id', {}, {
            get: {
                method: 'GET',
                params: {
                    id: '@id'
                }
            }
        });
    });