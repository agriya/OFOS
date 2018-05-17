'use strict';

describe('Controller: CartController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var CartController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    CartController = $controller('CartController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(CartController.awesomeThings.length).toBe(3);
  });
});
