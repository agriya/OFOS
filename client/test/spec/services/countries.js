'use strict';

describe('Service: countries', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var countries;
  beforeEach(inject(function (_countries_) {
    countries = _countries_;
  }));

  it('should do something', function () {
    expect(!!countries).toBe(true);
  });

});
