
angular.module('ofosApp.Restaurant.SingleRestaurant')
    .factory('singlerestaurantMenu', function($resource) {
        return $resource('/api/v1/restaurant_menus',{ filter: '@filter'}, {}, {
            get: {
                method: 'GET'
            }
        });
    });