'use strict';

describe('Controller: UsersRestaurantsReviewsController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersRestaurantsReviewsController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersRestaurantsReviewsController = $controller('UsersRestaurantsReviewsController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersRestaurantsReviewsController.awesomeThings.length).toBe(3);
  });
});
