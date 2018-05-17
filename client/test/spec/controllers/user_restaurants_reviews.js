'use strict';

describe('Controller: UserRestaurantsReviewsController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UserRestaurantsReviewsController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UserRestaurantsReviewsController = $controller('UserRestaurantsReviewsController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UserRestaurantsReviewsController.awesomeThings.length).toBe(3);
  });
});
