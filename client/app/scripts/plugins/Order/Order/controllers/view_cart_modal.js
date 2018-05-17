angular.module('ofosApp.Order.Order')
    .controller('viewcartModalcontroller', function($rootScope, $window, flash, $location, $filter, $state, $cookies, userSettings, $stateParams, $scope, $uibModal, getCarts) {
        var vm = this;
        vm.current_cookie_id = "";
        vm.index = function() {
                var cartCookies = $rootScope.getCartCookie("");
                 if (angular.isDefined(cartCookies.hash)) {
                 vm.current_cookie_id=cartCookies.hash;
                 }
            if (vm.current_cookie_id !== "") {
                var params = {};
                params.filter = '{"where":{"cookie_id":"' + vm.current_cookie_id + '"},"include":{"0":"restaurant_menu","1":"restaurant_menu_price"}}';
                getCarts.get(params, function(carts) {
                    if (angular.isDefined(carts.cart)) {
                        $rootScope.updateCardCount("add", carts.cart.length);
                    }
                })
            }
        };
        vm.openViewCartModal = function() {
            $uibModal.open({
                animation: true,
                templateUrl: 'scripts/plugins/Order/Order/views/default/view_cart.html',
                controller: 'viewcartController as vm',
            });
        };
        vm.index();
    });