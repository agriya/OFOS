'use strict';
/**
 * @ngdoc service
 * @name ofosApp.userProfile
 * @description
 * # userProfile
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('userSettings', function($resource) {
        return $resource('/api/v1/users/:id',{}, {
            update: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            },
            get: {
                method: 'GET',
                params: {
                    id: '@id'
                }
            }
        });
    });