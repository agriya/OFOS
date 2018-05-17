'use strict';

describe('Controller: UsersForgotPasswordController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersForgotPasswordController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersForgotPasswordController = $controller('UsersForgotPasswordController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersForgotPasswordController.awesomeThings.length).toBe(3);
  });
});
