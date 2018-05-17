'use strict';

describe('Controller: ReviewOrderController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var ReviewOrderController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    ReviewOrderController = $controller('ReviewOrderController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(ReviewOrderController.awesomeThings.length).toBe(3);
  });
});
