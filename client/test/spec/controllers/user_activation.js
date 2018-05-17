'use strict';

describe('Controller: UserActivationController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UserActivationController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UserActivationController = $controller('UserActivationController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UserActivationController.awesomeThings.length).toBe(3);
  });
});
