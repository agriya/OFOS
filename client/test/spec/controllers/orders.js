'use strict';

describe('Controller: OrdersController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var OrdersController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    OrdersController = $controller('OrdersController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(OrdersController.awesomeThings.length).toBe(3);
  });
});
