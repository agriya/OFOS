'use strict';

describe('Controller: UsersRegisterController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersRegisterController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersRegisterController = $controller('UsersRegisterController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersRegisterController.awesomeThings.length).toBe(3);
  });
});
