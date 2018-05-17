angular.module('ofosApp.Order.Order')
    .directive('cartCount', function() {
        return {
            restrict: 'AE',
            templateUrl: 'scripts/plugins/Order/Order/views/default/view_cart_modal.html',
            controller: 'viewcartModalcontroller as vm',
        };
    });