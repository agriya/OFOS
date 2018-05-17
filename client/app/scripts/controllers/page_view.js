'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:PageViewController
 * @description
 * # PageViewController
 * Controller of the ofosApp
 */
angular.module('ofosApp')
    .controller('PageViewController', function($rootScope, $stateParams, page, flash, $filter) {
        var vm = this;
        var params = {};
        params.id = $stateParams.id;
        page.get(params, function(response) {
            if (angular.isDefined(response.data)) {
                vm.page = response.data;
                $rootScope.header = $rootScope.settings.SITE_NAME + ' | Page - ' + vm.page.title;
                var _descriptions = (vm.page.meta_description !== null && vm.page.meta_description !== '') ? vm.page.meta_description : vm.page.title;
                var _keywords = (vm.page.meta_keywords !== null && vm.page.meta_keywords !== '') ? vm.page.meta_keywords : vm.page.title;
                angular.element('html head meta[name=description]')
                    .attr("content", _descriptions);
                angular.element('html head meta[name=keywords]')
                    .attr("content", _keywords);
                angular.element('html head meta[property="og:description"], html head meta[name="twitter:description"]')
                    .attr("content", _descriptions);
                angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]')
                    .attr("content", $rootScope.header);
                angular.element('html head meta[property="og:image"], html head meta[name="twitter:image"]')
                    .attr("content", 'img/logo.png');
                angular.element('meta[property="og:url"], html head meta[name="twitter:url"]')
                    .attr('content', "/pages/" + $stateParams.id);
            } else {
                flash.set($filter("translate")("Invalid request."), 'error', false);
            }
        });
    });