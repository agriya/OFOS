'use strict';
/**
 * @ngdoc service
 * @name ofosApp.userReviews
 * @description
 * # userReviews
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Review')
    .factory('userReviews', function() {
        // Service logic
        // ...
        var meaningOfLife = 42;
        // Public API here
        return {
            someMethod: function() {
                return meaningOfLife;
            }
        };
    });