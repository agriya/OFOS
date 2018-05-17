'use strict';

describe('Controller: UsersAddressAddController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersAddressAddController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersAddressAddController = $controller('UsersAddressAddController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersAddressAddController.awesomeThings.length).toBe(3);
  });
});
