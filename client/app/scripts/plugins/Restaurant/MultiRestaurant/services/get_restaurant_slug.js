'use strict';
/**
 * @ngdoc service
 * @name ofosApp.getRestaurantSlug
 * @description
 * # getRestaurantSlug
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Restaurant.MultiRestaurant')
    .factory('getRestaurantSlug', function($resource) {
        return $resource('/api/v1/restaurants/slug/:slug', {}, {
            get: {
                method: 'GET',
                params: {
                    slug: '@slug'
                }
            }
        });
    });