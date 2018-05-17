'use strict';

describe('Directive: languages', function () {

  // load the directive's module
  beforeEach(module('ofosApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<languages></languages>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the languages directive');
  }));
});
