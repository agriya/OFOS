angular.module('ofosApp.Order.Coupon')
    .directive('coupons', function () {
        return {
            templateUrl: 'scripts/plugins/Order/Coupon/views/default/coupons.html',
            restrict: 'E'
            }
            });