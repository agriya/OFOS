'use strict';

describe('Controller: UsersChangePasswordController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersChangePasswordController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersChangePasswordController = $controller('UsersChangePasswordController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersChangePasswordController.awesomeThings.length).toBe(3);
  });
});
