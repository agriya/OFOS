'use strict';

describe('Controller: ContactController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var ContactController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    ContactController = $controller('ContactController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(ContactController.awesomeThings.length).toBe(3);
  });
});
