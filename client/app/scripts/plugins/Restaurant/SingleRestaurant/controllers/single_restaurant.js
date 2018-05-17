'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:RestaurantViewController
 * @description
 * # RestaurantViewController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Restaurant.SingleRestaurant')
    .controller('SingleRestaurantController', function($rootScope, restaurants, restaurantBranchCategories, $stateParams, cart, $window, carts, flash, $location, checkout, anchorSmoothScroll, $filter, md5, NgMap, $cookies, $uibModal, $timeout, $scope) {
        var vm = this;
        vm.restaurant_branch_index = 0;
        vm.restaurant_menu_image_limit = 8;
        vm.category_index = 0;
        vm.categories_limit = 4;
        vm.categories_limit_from = vm.categories_limit;
        vm.categories_limit_to = 100;
        vm.categories = {};
        var is_allow_users_to_preorder = true;       
        vm.index = function() {         
            if (angular.isDefined($stateParams.res_index)) {
                vm.restaurant_branch_index = $stateParams.res_index ;                              
            }                
            vm.getCategory();
        };
        vm.setAddress = function(location){
                    $rootScope.city = location.city.name;
                    $rootScope.state = location.state.name;
                    $rootScope.country = location.country.iso2;
                    $rootScope.zip_code = parseInt(location.zip_code);
                    $rootScope.lat = location.latitude;
                    $rootScope.lang = location.longitude;
                    $rootScope.address = location.address;
                    $rootScope.location_name = location.address1;
                    $window.localStorage.setItem('location', angular.toJson({
                        lat: $rootScope.lat,
                        lang: $rootScope.lang,
                        address: $rootScope.address,
                        location_name: $rootScope.location_name,
                        city: $rootScope.city,
                        state: $rootScope.state,
                        country: $rootScope.country,
                        zip_code: $rootScope.zip_code
                    }));
        }
        vm.getCategory = function() {
            var param = {};
            param.filter = '{"include":{"5":"restaurant_category","0":"restaurant_category.restaurant_menu.restaurant_menu_price","1":"restaurant_category.restaurant_menu.restaurant_addon.restaurant_addon_item.restaurant_menu_addon_price","2":"restaurant_category.restaurant_menu.attachment","3":"restaurant_branch","4":"restaurant_photo","6":"city","7":"state","8":"country"},"where":{"is_active":1},"limit":"all","skip":"0","order":"parent_id DESC"}';
            restaurants.get(param, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.restaurants = response.data;
                    vm.setAddress(vm.restaurants[vm.restaurant_branch_index]);
                    angular.forEach(vm.restaurants, function(restaurant) {                        
                        angular.forEach(restaurant.restaurant_photo, function(restaurantphoto) {
                            if (angular.isDefined(restaurantphoto) && restaurantphoto !== null) {
                                    var hash = md5.createHash('RestaurantPhoto' + restaurantphoto.id + 'png' + 'large_thumb');
                                    restaurantphoto.image_name = 'images/large_thumb/RestaurantPhoto/' + restaurantphoto.id + '.' + hash + '.png';
                                } else {
                                    restaurantphoto.image_name = 'images/no-image-menu-64x64.png';
                                }
                            });
                        angular.forEach(restaurant.restaurant_category, function(category) {
                            angular.forEach(category.restaurant_menu, function(menu) {
                                if (angular.isDefined(menu.attachment) && menu.attachment !== null) {
                                    var hash = md5.createHash('RestaurantMenu' + menu.attachment.id + 'png' + 'small_thumb');
                                    menu.image_name = 'images/small_thumb/RestaurantMenu/' + menu.attachment.id + '.' + hash + '.png';
                                } else {
                                    menu.image_name = 'images/no-image-menu-64x64.png';
                                }
                                if (angular.isDefined(menu.restaurant_menu_price) && menu.restaurant_menu_price !== null) {
                                    angular.forEach(menu.restaurant_menu_price, function(menu_price) {
                                        menu.price_type_id = menu_price.price_type_id;
                                    });
                                }
                                if (angular.isDefined(menu.restaurant_addon) && menu.restaurant_addon !== null) {
                                    angular.forEach(menu.restaurant_addon, function(addon) {
                                        if (angular.isDefined(addon.restaurant_addon_item) && addon.restaurant_addon_item !== null) {
                                            angular.forEach(addon.restaurant_addon_item, function(addonvalue, addonkey) {
                                                if (addonvalue.restaurant_menu_addon_price.length > 0 && addonkey === 0) {
                                                    menu.is_popup = true;
                                                }
                                            });
                                        } else {
                                            menu.is_popup = false;
                                        }
                                    });
                                }
                            });
                        });
                    });
                } else {
                    vm.empty_menu = true;
                }
            });
        };
        vm.selectCategory=function(index,category_limit){                  
            vm.category_index = index + category_limit;                    
        };        
        vm.open = function(restaurant_id, menu_id, restaurant_menu_price_id, price) {
            if (is_allow_users_to_preorder === false) {
                return false;
            }
            //jshint unused:false
            vm.menu = {};
            vm.menu.restaurant_id = restaurant_id;
            vm.menu.menu_id = menu_id;
            vm.menu.restaurant_menu_price_id = restaurant_menu_price_id;
            vm.menu.price = price;
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'scripts/plugins/Restaurant/SingleRestaurant/views/default/addon.html',
                controller: 'singleMenuAddonController as vm',
                resolve: {
                    menu: function() {
                        return vm.menu;
                    }
                }
            });
        };
        vm.quick_add_cart = function() {             
            vm.restaurant_id = vm.restaurants[vm.restaurant_branch_index].id;
            vm.restaurant_menu_id = vm.restaurants[vm.restaurant_branch_index].restaurant_category[vm.quick.category_index].restaurant_menu[vm.quick.menu_index].id;
            vm.restaurant_menu_price_id = vm.restaurants[vm.restaurant_branch_index].restaurant_category[vm.quick.category_index].restaurant_menu[vm.quick.menu_index].restaurant_menu_price[0].id;
            vm.restaurant_menu_price = vm.restaurants[vm.restaurant_branch_index].restaurant_category[vm.quick.category_index].restaurant_menu[vm.quick.menu_index].restaurant_menu_price[0].price;
            vm.add_cart(vm.restaurant_id, vm.restaurant_menu_id, vm.restaurant_menu_price_id, vm.restaurant_menu_price);
        };
        vm.add_cart = function(restaurant_id, restaurant_menu_id, restaurant_menu_price_id, price) {
            if (is_allow_users_to_preorder === false) {
                return false;
            }
            var params = {};
            var flashMessage;
            var cartCookies = $rootScope.getCartCookie(vm.restaurants[vm.restaurant_branch_index].id);
            if (angular.isDefined(cartCookies.hash)) {
            vm.current_cookie_id=cartCookies.hash;
            }
            params.cookie_id = vm.current_cookie_id;
            params.user_id = ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) ? $rootScope.user.id : null;
            params.restaurant_id = restaurant_id;
            params.restaurant_menu_id = restaurant_menu_id;
            params.restaurant_menu_price_id = restaurant_menu_price_id;
            params.quantity = 1;
            params.price = price;
            params.total_price = 0;
            params.restaurant_menu_addon_price = vm.restaurant_menu_addon_price;
            carts.create(params, function(response) {
                if (response.error.code === 0) {
                    vm.length = response.cart.length;
                   $rootScope.updateCardCount("add", response.cart.length);
                    $scope.$emit('updateCheckoutDashboard', {});
                    flashMessage = $filter("translate")("Added this item to your view cart.");
                    flash.set(flashMessage, 'success', false);
                } else {
                    flashMessage = $filter("translate")("Unable to add this item to your cart.");
                    flash.set(flashMessage, 'error', false);
                }                
            });
        };        
        $rootScope.selectedBranch=function(branch_index){                       
            vm.restaurant_branch_index  = branch_index;
            vm.category_index = 0;
             vm.setAddress(vm.restaurants[branch_index]);
        }
        vm.quick_addon=function(){           
            if (vm.restaurants[vm.restaurant_branch_index].restaurant_category[vm.quick.category_index].restaurant_menu[vm.quick.menu_index].restaurant_addon) {
                   return true;
                } else {
                    return false;
                }            
        }
        vm.restaurant_menu_addon_price = [];
        vm.addonItems = function(price_id, addon_id, type) {
            if (type === false) {
                vm.selected_id = addon_id;
            }
            if (vm.restaurant_menu_addon_price.length > 0) {
                angular.forEach(vm.restaurant_menu_addon_price, function(value, key) {
                    if (parseInt(value.id) === parseInt(price_id)) {
                        vm.restaurant_menu_addon_price.splice(key, 1);                    
                    }
                    if (parseInt(value.id) !== parseInt(price_id) && key === 0) {
                        vm.restaurant_menu_addon_price.push({
                            'id': price_id
                        });
                    }
                });
            } else {
                vm.restaurant_menu_addon_price.push({
                    'id': price_id
                });
            }
        };
        vm.index();
    });