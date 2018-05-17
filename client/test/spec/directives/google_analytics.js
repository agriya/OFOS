'use strict';

describe('Directive: googleAnalytics', function () {

  // load the directive's module
  beforeEach(module('ofosApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<google-analytics></google-analytics>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the googleAnalytics directive');
  }));
});
