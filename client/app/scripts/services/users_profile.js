'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersProfile
 * @description
 * # usersProfile
 * Factory in the ofosApp.
 */
angular.module('ofosApp')
    .factory('usersProfile', function() {
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