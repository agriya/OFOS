'use strict';

describe('Controller: CheckoutController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var CheckoutController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    CheckoutController = $controller('CheckoutController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(CheckoutController.awesomeThings.length).toBe(3);
  });
});
