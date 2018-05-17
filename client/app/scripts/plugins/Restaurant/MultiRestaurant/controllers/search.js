'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:SearchController
 * @description
 * # SearchController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .controller('SearchController', function($rootScope, restaurants, cuisines, $stateParams, $window, $location, $timeout, md5, $filter, NgMap, $scope) {
        var vm = this;
        NgMap.getMap().then(function(map) {
    vm.map = map;
  });
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Restaurants");
        vm.q = '';
        vm.place = null;
        vm.search_box = true;
        vm.cuisinesSort = {};
        vm.maxRatings = [];
        vm.maxRating = 5;
        vm.showList = true;
        vm.showMap = false;
        for (var i = 0; i < vm.maxRating; i++) {
            vm.maxRatings.push(i);
        }
        vm.autocompleteOptions = {
            types: ['cities']
        };
        if (angular.isDefined($stateParams.filter)) {
            vm.currentSort = $stateParams.filter;
        }
        vm.show_retaurants = function() {
            vm.currentSort = '';
            var k = 0;
            angular.forEach(vm.place.address_components, function(value, key) {
                //jshint unused:false
                if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                    if (k === 0) {
                        $rootScope.city = value.long_name;
                    }
                    if (value.types[0] === 'locality') {
                        k = 1;
                    }
                }
                if (value.types[0] === 'administrative_area_level_1') {
                    $rootScope.state = value.long_name;
                }
                if (value.types[0] === 'country') {
                    $rootScope.country = value.short_name;
                }
                if (value.types[0] === 'postal_code') {
                    $rootScope.zip_code = parseInt(value.long_name);
                }
            });
            $rootScope.lat = vm.place.geometry.location.lat();
            $rootScope.lang = vm.place.geometry.location.lng();
            $rootScope.address = vm.place.formatted_address;
            $rootScope.location_name = vm.place.name;
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
            $location.search('page', null)
                .search('q', null)
                .search('lat', $rootScope.lat)
                .search('lang', $rootScope.lang)
                .search('cuisine', null)
                .search('filter', null)
                .search('rating', null);
            $timeout(function() {
                vm.q = '';
                vm.index();
            });
            vm.search_box = true;
        };
        vm.paginate = function() {
            vm.currentPage = parseInt(vm.currentPage);
            if (angular.isDefined(vm.q) && vm.q !== "") {
                $location.search('page', vm.currentPage)
                    .search('q', vm.q);
            } else {
                $location.search('page', vm.currentPage);
            }
            $timeout(function() {
                vm.index();
            }, 1000);
        };
        vm.index = function() {
            vm.loader = true;
            vm.show_search = false;
            vm.maxSize = 5;
            vm.randomImageClass = Math.floor((Math.random() * 7) + 1);
            vm.currentPage = (angular.isDefined($stateParams.page)) ? parseInt($stateParams.page) : 1;
            vm.q = (angular.isDefined(vm.q) && vm.q !== '') ? vm.q : ((angular.isDefined($stateParams.q)) ? $stateParams.q : "");
            var param = {};
            param.filter = '{"skip":0,"limit":500}';
            cuisines.get(param, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.cuisines = response.data;
                }
            });
            vm.limit = 10; 
            var params = {};
            params.filter = {};
            vm.skip = (vm.currentPage - 1) * vm.limit ; 
            params.filter.skip =  vm.skip;
            params.filter.limit =  vm.limit;
            params.filter.include =  {"0":"attachment"};
            params.filter.where = {"is_active":true};
            if (vm.q !== "") {
                params.q = vm.q;
            }
            if (angular.isDefined($stateParams.cuisine) && $stateParams.cuisine !== "") {
                params.cuisine = $stateParams.cuisine;
            }
            if (angular.isDefined($stateParams.filter) && $stateParams.filter !== "") {
                if($stateParams.filter == "name"){
                    params.filter.order = "name ASC";
                }else if($stateParams.filter == "rating"){
                    params.filter.order = "avg_rating DESC";
                }else if($stateParams.filter == "latest"){
                    params.filter.order = "created_at DESC";
                }else if($stateParams.filter == "distance"){
                    params.latitude = $rootScope.lat;
                    params.longitude = $rootScope.lang;
                    params.sort = "distance";
                    params.sortby = "ASC";
                }
             }
            restaurants.get(params, function(response) {
                vm.currentPage = params.page;
                if (angular.isDefined(response._metadata)) {
                    vm.totalItems = response._metadata.total;
                    vm.itemsPerPage = response._metadata.per_page;
                    vm.noOfPages = response._metadata.last_page;
                }
                if (angular.isDefined(response.data)) {
                    vm.restaurants = response.data;
                    var lat = vm.restaurants[0].latitude;
                    var lang = vm.restaurants[0].longitude;
                    vm.mapCenter= [lat,lang];
                    angular.forEach(vm.restaurants, function(value, key) {
                        if (angular.isDefined(value.attachment) && value.attachment !== null) {
                            var hash = md5.createHash('Restaurant' + value.attachment.id + 'png' + 'medium_thumb');
                            vm.restaurants[key].image_name = 'images/medium_thumb/Restaurant/' + value.attachment.id + '.' + hash + '.png';
                        } else {
                            vm.restaurants[key].image_name = 'images/no-image-restaurant-100x100.png';
                        }
                        if (angular.isDefined(value.restaurant_review_count) && value.restaurant_review_count.length !== 0) {
                            vm.restaurants[key].rating_round = Math.round(value.restaurant_review_count[0].total_ratings / value.restaurant_review_count[0].total_user_rating_count);
                            vm.restaurants[key].rating_point = value.restaurant_review_count[0].total_ratings / value.restaurant_review_count[0].total_user_rating_count;
                        }
                        if (angular.isDefined(value.restaurant_timing)) {
                            var d = new Date();
                            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                            var n = weekday[d.getDay()];
                            angular.forEach(value.restaurant_timing, function(k_value, k_key) {
                                //jshint unused:false
                                if (k_value.day === n && k_value.period_type === 1 && k_value.start_time !== "00:00:00") {
                                    vm.restaurants[key].open = k_value.start_time.substring(0, k_value.start_time.length - 3);
                                }
                            });
                        }
                    });
                } else {
                    vm.restaurants = [];
                }
                vm.loader = false;
            });
        };
        vm.find = function() {
            vm.currentPage = 1;
            if (angular.isDefined(vm.q) && vm.q !== "") {
                $location.path('/restaurants')
                    .search('page', vm.currentPage)
                    .search('q', vm.q);
            } else {
                $location.path('/restaurants')
                    .search('page', vm.currentPage)
                    .search('q', null);
            }
            $timeout(function() {
                vm.index();
            }, 1000);
        };
        vm.sort = function(a) {
            vm.currentPage = 1;
            vm.currentSort = a;
            var checked_row = vm.getChecked(vm.cuisinesSort);
            checked_row = (checked_row.length !== 0) ? checked_row.join() : null;
            var q = null;
            var new_first = null;
            if (angular.isDefined(vm.q) && vm.q !== "") {
                q = vm.q;
            }
            if (angular.isDefined(a) && a !== "") {
                new_first = a;
            }
            $location.path('/restaurants')
                .search('page', vm.currentPage)
                .search('q', q)
                .search('cuisine', checked_row)
                .search('filter', new_first);
            $timeout(function() {
                vm.index();
            }, 1000);
        };
        vm.getChecked = function(obj) {
            var checked = [];
            for (var key in obj) {
                if (obj[key]) {
                    checked.push(key);
                }
            }
            return checked;
        };
        vm.index();

  vm.showDetail = function(e, restaurant) {
    $scope.restaurant = restaurant;
    vm.map.showInfoWindow('foo-iw', restaurant.hash);
  };

  vm.hideDetail = function() {
    vm.map.hideInfoWindow('foo-iw');
  };
    });