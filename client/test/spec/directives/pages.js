'use strict';

describe('Directive: pages', function () {

  // load the directive's module
  beforeEach(module('ofosApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<pages></pages>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the pages directive');
  }));
});
