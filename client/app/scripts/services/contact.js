'use strict';
/**
 * @ngdoc service
 * @name ofosApp.contact
 * @description
 * # contact
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('contact', function($resource) {
        return $resource('/api/v1/contacts', {}, {
            create: {
                method: 'POST'
            }
        });
    });