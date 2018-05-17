'use strict';
angular.module('ofosApp.Restaurant.SingleRestaurant').factory('restaurantBranchCategories', function($resource) {
        return $resource('/api/v1/restaurants/:branch_id',{ filter: '@filter'}, {}, {
            get: {
                method: 'GET',
                params: {
                    branch_id: '@branch_id'
                }               
            }
        });
});
