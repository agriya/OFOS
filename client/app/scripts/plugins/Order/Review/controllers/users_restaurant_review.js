'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:UsersRestaurantReviewController
 * @description
 * # UsersRestaurantReviewController
 * Controller of the ofosApp
 */
angular.module('ofosApp.Order.Review')
    .controller('UsersRestaurantReviewController', function($rootScope, $uibModalInstance, review, usersRestaurantReview, flash, $filter, $document) {
        var vm = this;
        vm.review = review;
        vm.rate = 0;
        vm.max = 5;
        vm.error = false;
        vm.hoveringOver = function(value) {
            vm.overStar = value;
            vm.percent = 100 * (value / vm.max);
        };
        vm.saveReview = function() {
            if (angular.isUndefined(review.rating) || review.rating === 0) {
                vm.error = true;
                return false;
            }
            if (vm.reviewForm.$valid) {
                vm.review.user_id = $rootScope.user.id;
                usersRestaurantReview.create(vm.review, function(response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        angular.element($document[0].querySelector('#order_' + review.order_id)
                            .remove());
                        flash.set($filter("translate")("Review has been posted successfully."), 'success', false);
                        $uibModalInstance.dismiss('cancel');
                    } else {
                        flash.set($filter("translate")("Review could not be posted. Please try again."), 'error', false);
                    }
                });
            }
        };
        vm.cancel = function() {
            $uibModalInstance.dismiss('cancel');
        };
    });