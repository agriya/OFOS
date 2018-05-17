'use strict';

describe('Controller: SearchController', function () {

  // load the controller's module
  beforeEach(module('ofosApp'));

  var SearchController,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    SearchController = $controller('SearchController', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(SearchController.awesomeThings.length).toBe(3);
  });
});
