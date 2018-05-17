'use strict';
/**
 * @ngdoc service
 * @name ofos.getGateways
 * @description
 * # getGateways
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('getGateways', ['$resource', function($resource) {
        return $resource('/api/v1/get_gateways', {}, {
            get: {
                method: 'GET'
            }
        });
}]);