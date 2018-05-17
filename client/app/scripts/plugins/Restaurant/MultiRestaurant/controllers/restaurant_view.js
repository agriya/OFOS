'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:RestaurantViewController
 * @description
 * # RestaurantViewController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .controller('RestaurantViewController', function($rootScope, restaurantView, restaurantReviews, restaurantCategories, restaurantMenu, $stateParams, cart, $window, carts, flash, $location, checkout, anchorSmoothScroll, $filter, md5, NgMap, $cookies, $uibModal, $timeout, $scope) {
        var vm = this;
        /*jshint -W117 */
        vm.show_checkout_btn = false;
        vm.current_cookie_id = "";
        vm.total = 0;
        var is_allow_users_to_preorder = true;
        
        vm.more_reviews_btn = false;
        vm.maxRatings = [];
        vm.maxRating = 5;
        vm.restaurant = {};
        vm.reviews = [];
        for (var i = 0; i < vm.maxRating; i++) {
            vm.maxRatings.push(i);
        }            
        vm.index = function() {
            vm.loader = true;
            var params = {};
            var res_id = $stateParams.id;
            var cartCookies = $rootScope.getCartCookie(res_id);
            if (angular.isDefined(cartCookies.hash)) {
            vm.current_cookie_id=cartCookies.hash;
            }
            params.id = $stateParams.id;
            params.filter = '{"include":{"restaurant_category":{"order":"display_order asc"},"0":"restaurant_category.restaurant_menu.restaurant_menu_price","1":"restaurant_category.restaurant_menu.restaurant_addon.restaurant_addon_item.restaurant_menu_addon_price","2":"restaurant_category.restaurant_menu.attachment","3":"restaurant_timing","4":"restaurant_photo","5":"city","6":"state","7":"country","8":"attachment"}}';
            restaurantView.get(params, function (response) {
                vm.restaurant = response.data;
                params.sort = 'id';
                if (angular.isUndefined($rootScope.location_name)) {
                    $rootScope.city = vm.restaurant.city.name;
                    $rootScope.state = vm.restaurant.state.name;
                    $rootScope.country = vm.restaurant.country.iso2;
                    $rootScope.zip_code = parseInt(vm.restaurant.zip_code);
                    $rootScope.lat = vm.restaurant.latitude;
                    $rootScope.lang = vm.restaurant.longitude;
                    $rootScope.address = vm.restaurant.address;
                    $rootScope.location_name = vm.restaurant.address1;
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
                } else if (angular.isUndefined($rootScope.zip_code)) {
                    $rootScope.zip_code = parseInt(vm.restaurant.zip_code);
                }
                if (angular.isDefined(vm.restaurant)) {
                    vm.restaurant.minimum_order_for_booking = Number(vm.restaurant.minimum_order_for_booking);
                    $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + vm.restaurant.name;
                }
                if (vm.restaurant.is_closed === 1 && vm.restaurant.is_allow_users_to_preorder === 0) {
                    is_allow_users_to_preorder = false;
                }
                if (angular.isDefined(vm.restaurant.attachment) && vm.restaurant.attachment !== null) {
                    var hash = md5.createHash('Restaurant' + vm.restaurant.attachment.id + 'png' + 'medium_thumb');
                    vm.restaurant.image_name = 'images/medium_thumb/Restaurant/' + vm.restaurant.attachment.id + '.' + hash + '.png';
                } else {
                    vm.restaurant.image_name = 'images/no-image-restaurant-100x100.png';
                }
                if (angular.isDefined(vm.restaurant.restaurant_timing)) {
                    var d = new Date();
                    var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                    var n = weekday[d.getDay()];
                    angular.forEach(vm.restaurant.restaurant_timing, function(value, key) {
                        //jshint unused:false
                        if (value.day === n && value.period_type === 1 && value.start_time !== "00:00:00") {
                            vm.restaurant.open = value.start_time.substring(0, value.start_time.length - 3);
                        }
                    });
                }
                if (angular.isDefined(vm.restaurant.restaurant_review_count) && vm.restaurant.restaurant_review_count.length !== 0) {
                    vm.restaurant.rating_round = Math.round(vm.restaurant.restaurant_review_count[0].total_ratings / vm.restaurant.restaurant_review_count[0].total_user_rating_count);
                    vm.restaurant.rating_point = vm.restaurant.restaurant_review_count[0].total_ratings / vm.restaurant.restaurant_review_count[0].total_user_rating_count;
                }
                if (angular.isDefined(vm.restaurant.restaurant_photo) && vm.restaurant.restaurant_photo.length > 0) {
                    angular.forEach(vm.restaurant.restaurant_photo, function(value) {
                        if (angular.isDefined(value.filename) && value.filename !== null) {
                            var hash = md5.createHash('RestaurantPhoto' + value.id + 'png' + 'large_thumb');
                            value.image_name = 'images/large_thumb/RestaurantPhoto/' + value.id + '.' + hash + '.png';
                        } else {
                            value.image_name = 'images/no_image_available.png';
                        }
                    });
                }
                if (angular.isDefined(vm.restaurant.restaurant_category)) {
                    angular.forEach(vm.restaurant.restaurant_category, function(restaurant_category) {
                    angular.forEach(restaurant_category.restaurant_menu, function(menu) {
                        if(menu !==""){
                        vm.empty_menu=true;
                        if (angular.isDefined(menu.attachment) && menu.attachment !== null) {
                            var hash = md5.createHash('RestaurantMenu' + menu.attachment.id + 'png' + 'small_thumb');
                            menu.image_name = 'images/small_thumb/RestaurantMenu/' + menu.attachment.id + '.' + hash + '.png';
                        } else {
                            menu.image_name = 'images/no-image-menu-64x64.png';
                        }
                        if (angular.isDefined(menu.restaurant_menu_price) && menu.restaurant_menu_price !== null) {
                            angular.forEach(menu.restaurant_menu_price, function(v) {
                                menu.price_type_id = v.price_type_id;
                            });
                        }
                        if (angular.isDefined(menu.restaurant_addon) && menu.restaurant_addon !== null) {
                            angular.forEach(menu.restaurant_addon, function(value1) {
                                if (angular.isDefined(value1.restaurant_addon_item) && value1.restaurant_addon_item !== null) {
                                    angular.forEach(value1.restaurant_addon_item, function(value2, key2) {
                                        if (value2.restaurant_menu_addon_price.length > 0 && key2 === 0) {
                                            menu.is_popup = true;
                                        }
                                    });
                                } else {
                                    menu.is_popup = false;
                                }
                            });
                        }
                        }
                        else{
                           vm.empty_menu=false;  
                        }
                    });
                    });
                }                
                vm.loader = false;
            });
            vm.getReview(0, 10);           
        };        
        vm.getReview = function(skip, limit){
            vm.loader = true;           
            var params = {};
            params.filter = '{"where":{"restaurant_id":' + $stateParams.id + '},"order":"restaurant_id asc","skip": '+skip+',"limit":'+limit+'}';
            restaurantReviews.get(params, function(reviews) {
                if (angular.isDefined(reviews)) {
                    angular.forEach(reviews.data, function(value) {                        
                        vm.reviews.push(value);
                    });                  
                    vm.metadata = reviews._metadata;
                    if (vm.metadata.current_page < vm.metadata.last_page) {
                        vm.more_reviews_btn = true;
                    }
                }
                vm.loader = false;
            });
        } 
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
                templateUrl: 'scripts/plugins/Restaurant/MultiRestaurant/views/default/addon.html',
                controller: 'MenuAddonController as vm',
                resolve: {
                    menu: function() {
                        return vm.menu;
                    }
                }
            });
        };
        vm.add_cart = function(restaurant_id, restaurant_menu_id, restaurant_menu_price_id, price) {
            if (is_allow_users_to_preorder === false) {
                return false;
            }
            vm.show_checkout_btn = true;
            var params = {};
            var flashMessage;
            params.cookie_id =vm.current_cookie_id;
            params.user_id = ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) ? $rootScope.user.id : null;
            params.restaurant_id = restaurant_id;
            params.restaurant_menu_id = restaurant_menu_id;
            params.restaurant_menu_price_id = restaurant_menu_price_id;
            params.quantity = 1;
            params.price = price;
            params.total_price = 0;
            carts.create(params, function(response) {
                if (response.error.code === 0) {
                    $scope.$emit('updateCheckoutDashboard', {});
                } else {
                    flashMessage = $filter("translate")("Unable to add this item to your cart.");
                    flash.set(flashMessage, 'error', false);
                }
            });
        };
        vm.redirectToSearchPage = function(latitude, longitude) {
            $location.path('/restaurants')
                .search('lat', latitude)
                .search('lang', longitude);
        };
        vm.gotoElement = function(eID) {
            vm.menu_tab = true;
            vm.review_tab = false;
            vm.info_tab = false;
            $timeout(function() {
                anchorSmoothScroll.scrollTo("#c-" + eID.replace(/\s+/g, '-'));
            }, 500);
        };        
        vm.moreReviews = function() {
            vm.loader = true;
            vm.more_reviews_btn = false;
            if (vm.metadata.current_page < vm.metadata.last_page) {
                var skip = vm.metadata.current_page * 10;
                vm.getReview(skip, 10);
            }
        };
        vm.index();
    });