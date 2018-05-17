'use strict';

describe('Controller: UsersAddressesController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersAddressesController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersAddressesController = $controller('UsersAddressesController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersAddressesController.awesomeThings.length).toBe(3);
  });
});
