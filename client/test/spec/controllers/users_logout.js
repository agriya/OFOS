'use strict';

describe('Controller: UsersLogutController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var UsersLogutController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersLogutController = $controller('UsersLogutController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersLogutController.awesomeThings.length).toBe(3);
  });
});
