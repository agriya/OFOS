'use strict';

describe('Filter: dateFormat', function () {

  // load the filter's module
  beforeEach(module('ofosApp'));

  // initialize a new instance of the filter before each test
  var dateFormat;
  beforeEach(inject(function ($filter) {
    dateFormat = $filter('dateFormat');
  }));

  it('should return the input prefixed with "dateFormat filter:"', function () {
    var text = 'angularjs';
    expect(dateFormat(text)).toBe('dateFormat filter: ' + text);
  });

});
