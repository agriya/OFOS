'use strict';
/**
 * @ngdoc service
 * @name ofosApp.getCarts
 * @description
 * # getCarts
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('getCarts', function($resource) {
        return $resource('/api/v1/carts',{filter:'@filter'}, {}, {
            get: {
                method: 'GET',
                /*params: {
                    cookie_id: '@cookie_id'
                }*/
            }
        });
    });