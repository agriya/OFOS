'use strict';
/**
 * @ngdoc service
 * @name ofos.categoryUpdate
 * @description
 * # categoryUpdate
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('categoryUpdate', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_categories/:id', {}, {
            update: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            }
        });
}]);