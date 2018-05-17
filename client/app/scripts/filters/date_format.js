'use strict';
/**
 * @ngdoc filter
 * @name ofosApp.filter:dateFormat
 * @function
 * @description
 * # dateFormat
 * Filter in the ofosApp.
 */
angular.module('ofosApp')
    .filter('medium', function myDateFormat($filter) {
        return function(text) {
            var tempdate = new Date(text.replace(/(.+) (.+)/, "$1T$2Z"));
            return $filter('date')(tempdate, "medium");
        };
    });