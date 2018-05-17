'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:HomeController
 * @description
 * # HomeController
 * Controller of the ofosApp
 */
angular.module('ofosApp')
    .controller('HomeController', function($rootScope, $location, $window, $filter) {
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Home");
       /*var vm = this;
        
        vm.place = null;
        vm.autocompleteOptions = {
            types: ['cities']
        };
        if (angular.isDefined($rootScope.scroll_position)) {
            $timeout(function() {
                anchorSmoothScroll.scrollTo($rootScope.scroll_position);
                delete $rootScope.scroll_position;
            }, 1000);
        }
        vm.show_retaurants = function() {
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
            $state.go('search_restaurant', {
                lat: $rootScope.lat,
                lang: $rootScope.lang
            });
        };*/
    });