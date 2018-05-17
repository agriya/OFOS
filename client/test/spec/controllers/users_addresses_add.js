'use strict';

describe('Controller: UsersAddressesAddController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersAddressesAddController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersAddressesAddController = $controller('UsersAddressesAddController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersAddressesAddController.awesomeThings.length).toBe(3);
  });
});
