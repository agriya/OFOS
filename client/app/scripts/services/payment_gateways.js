'use strict';
/**
 * @ngdoc service
 * @name ofosApp.paymentGateways
 * @description
 * # paymentGateways
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('paymentGateways', function($resource) {
        return $resource('/api/v1/payment_gateways/list', {}, {
            get: {
                method: 'GET'
            }
        });
    });