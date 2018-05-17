'use strict';

describe('Directive: popularCities', function () {

  // load the directive's module
  beforeEach(module('ofosApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<popular-cities></popular-cities>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the popularCities directive');
  }));
});
