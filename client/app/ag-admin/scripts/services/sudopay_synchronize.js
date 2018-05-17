'use strict';
/**
 * @ngdoc service
 * @name ofos.sudopaySynchronize
 * @description
 * # sudopaySynchronize
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('sudopaySynchronize', ['$resource', function($resource) {
        return $resource('/api/v1/payment_gateways/sudopay_synchronize', {}, {
            get: {
                method: 'GET'
            }
        });
}]);