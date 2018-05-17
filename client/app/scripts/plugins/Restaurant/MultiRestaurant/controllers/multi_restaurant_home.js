'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:MultiRestaurantCtrl
 * @description
 * # MultiRestaurantCtrl
 * Controller of the ofosApp
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .controller('MultiRestaurantCtrl', function($rootScope, $location, $window, $filter, $state, anchorSmoothScroll, $timeout) {
        var vmh = this;
        vmh.index = function() {
            vmh.place = null;
            vmh.autocompleteOptions = {
                types: ['cities']
            };
            if (angular.isDefined($rootScope.scroll_position)) {
                $timeout(function() {
                    anchorSmoothScroll.scrollTo($rootScope.scroll_position);
                    delete $rootScope.scroll_position;
                }, 1000);
            }
        };
        vmh.showRetaurants = function() {
            var k = 0;
            angular.forEach(vmh.place.address_components, function(value, key) {
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
            $rootScope.lat = vmh.place.geometry.location.lat();
            $rootScope.lang = vmh.place.geometry.location.lng();
            $rootScope.address = vmh.place.formatted_address;
            $rootScope.location_name = vmh.place.name;
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
            $state.go('search_restaurant', {
                lat: $rootScope.lat,
                lang: $rootScope.lang
            });
        };
        vmh.index();
    });