angular.module('ofosApp.Order.Coupon')
 .factory('getCoupons', function ($resource) {
        return $resource('/api/v1/coupons/get_status/:coupon_code', {}, {
            get: {
                method: 'GET',
                params: {
                    coupon_code: '@coupon_code'
                }
            }
        });
    });