'use strict';

describe('Controller: UsersSettingsController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersSettingsController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersSettingsController = $controller('UsersSettingsController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersSettingsController.awesomeThings.length).toBe(3);
  });
});
