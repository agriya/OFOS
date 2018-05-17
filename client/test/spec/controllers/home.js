'use strict';

describe('Controller: HomeController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var HomeController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    HomeController = $controller('HomeController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(HomeController.awesomeThings.length).toBe(3);
  });
});
