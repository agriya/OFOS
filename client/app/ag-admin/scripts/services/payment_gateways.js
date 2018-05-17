'use strict';
/**
 * @ngdoc service
 * @name ofos.paymentGateway
 * @description
 * # paymentGateway
 * Factory in the ofos.
 */
angular.module('ofos')
    .factory('paymentGateway', ['$resource', function($resource) {
        return $resource('/api/v1/payment_gateway_settings/:id', {}, {
            update: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            }
        });
}]);