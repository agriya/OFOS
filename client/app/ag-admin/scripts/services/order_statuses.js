'use strict';
/**
 * @ngdoc service
 * @name ofos.orderStatuses
 * @description
 * # orderStatuses
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('orderStatuses', ['$resource', function($resource) {
        return $resource('/api/v1/order_statuses', {}, {
            get: {
                method: 'GET'
            }
        });
}]);