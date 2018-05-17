'use strict';

describe('Controller: UsersLoginController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersLoginController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersLoginController = $controller('UsersLoginController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersLoginController.awesomeThings.length).toBe(3);
  });
});
