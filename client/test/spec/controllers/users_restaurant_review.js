'use strict';

describe('Controller: UsersRestaurantReviewController as vm', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersRestaurantReviewController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersRestaurantReviewController = $controller('UsersRestaurantReviewController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersRestaurantReviewController.awesomeThings.length).toBe(3);
  });
});
