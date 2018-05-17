'use strict';

describe('Directive: popularRestaurants', function () {

  // load the directive's module
  beforeEach(module('ofosApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<popular-restaurants></popular-restaurants>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the popularRestaurants directive');
  }));
});
