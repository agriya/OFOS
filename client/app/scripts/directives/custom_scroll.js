'use strict';
/**
 * @ngdoc directive
 * @name ofosApp.directive:googleAnalytics
 * @description
 * # googleAnalytics
 */
angular.module('ofosApp')
    .directive('customScroll', function() {
        return {
            restrict: 'A',
            link: function postLink(scope, iElement) {
                iElement.mCustomScrollbar({
                    autoHideScrollbar: true,
                    theme: "rounded-dark",
                    mouseWheel: {
                        scrollAmount: 188
                    },
                    autoExpandScrollbar: true,
                    snapAmount: 188,
                    snapOffset: 65,
                    advanced: {
                        updateOnImageLoad: true
                    },
                    keyboard: {
                        scrollType: "stepped"
                    },
                    scrollButtons: {
                        enable: true,
                        scrollType: "stepped"
                    }
                });
            }
        };
    });