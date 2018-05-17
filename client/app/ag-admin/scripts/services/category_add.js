'use strict';
/**
 * @ngdoc service
 * @name ofos.categoryAdd
 * @description
 * # categoryAdd
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('categoryAdd', ['$resource', function($resource) {
        return $resource('/api/v1/restaurant_categories', {}, {
            add: {
                method: 'POST'
            }
        });
}]);