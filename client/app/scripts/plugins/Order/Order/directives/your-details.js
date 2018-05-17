'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:yourDetails
 * @description
 * # yourDetails
 */
angular.module('ofosApp.Order.Order')
    .directive('yourDetails', function () {
        return {
            templateUrl: 'scripts/plugins/Order/Order/views/default/your-details.html',
            restrict: 'E',
            replace: 'true',
            controllerAs: 'vmy',
            controller: function ($rootScope, $scope, getCarts, $auth, $cookies, md5) {
                var vmy = this;
                vmy.current_cookie_id = "";
                vmy.index = function(){
                var cartCookies = $rootScope.getCartCookie("");
                 if (angular.isDefined(cartCookies.hash)) {
                    vmy.current_cookie_id=cartCookies.hash;
                 }
                if (vmy.current_cookie_id !== "") {
                    var params = {};
                    params.filter = '{"where":{"cookie_id":"'+ vmy.current_cookie_id +'"},"include":{"0":"restaurant_menu","1":"restaurant_menu_price","2":"restaurant.attachment"}}';
                    getCarts.get(params, function (carts) {
                        if (angular.isDefined(carts.cart)) {
                            vmy.carts = carts.cart;
                            vmy.restaurant = carts.restaurant;
                            if (!vmy.restaurant.is_allow_users_to_door_delivery_order) {
                                vmy.is_allow_users_to_door_delivery_order = 0;
                            }
                            if (angular.isDefined(vmy.restaurant.attachment) && vmy.restaurant.attachment !== null) {
                                var hash = md5.createHash('Restaurant' + vmy.restaurant.attachment.id + 'png' + 'medium_thumb');
                                vmy.restaurant.image_name = 'images/medium_thumb/Restaurant/' + vmy.restaurant.attachment.id + '.' + hash + '.png';
                            } else {
                                vmy.restaurant.image_name = 'images/no-image-restaurant-100x100.png';
                            }
                            vmy.calculate();
                        }
                    });
                }
                }
               
                vmy.calculate = function () {
                    var sub_total = 0;
                    angular.forEach(vmy.carts, function (value, key) {
                        //jshint unused:false
                        sub_total += parseFloat(value.restaurant_menu_price.price) * parseFloat(value.quantity);
                    });
                    vmy.sub_total = sub_total;
                    vmy.sales_tax = (vmy.sub_total * parseFloat(vmy.restaurant.sales_tax) / 100);
                    vmy.total = vmy.sub_total + parseFloat(vmy.restaurant.delivery_charge) + parseFloat(vmy.sales_tax);
                };
                vmy.index();
            },
        };
    });