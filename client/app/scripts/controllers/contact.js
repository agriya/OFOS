'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:ContactController
 * @description
 * # ContactController
 * Controller of the ofosApp
 */
angular.module('ofosApp')
    .controller('ContactController', function($rootScope, contact, flash, vcRecaptchaService, $filter) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Contact us");
        vm.save_btn = false;
        vm.save = function() {
            var response = vcRecaptchaService.getResponse(vm.widgetId);
            if (response.length === 0) {
                vm.captchaErr = $filter("translate")("Please resolve the captcha and submit");
            } else {
                vm.captchaErr = '';
            }
            if (vm.contactForm.$valid && !vm.save_btn) {
                vm.save_btn = true;
                contact.create(vm.contact, function(response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        flash.set($filter("translate")("Thank you, we received your message and will get back to you as soon as possible."), 'success', false);
                        vm.contact = {};
                    } else {
                        flash.set($filter("translate")("Contact could not be submitted. Please try again."), 'error', false);
                    }
                    vm.save_btn = false;
                    vcRecaptchaService.reload(vm.widgetId);
                });
            }
        };
    });