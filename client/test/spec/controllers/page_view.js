'use strict';

describe('Controller: PageViewController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var PageViewController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    PageViewController = $controller('PageViewController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(PageViewController.awesomeThings.length).toBe(3);
  });
});
