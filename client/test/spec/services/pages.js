'use strict';

describe('Service: pages', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var pages;
  beforeEach(inject(function (_pages_) {
    pages = _pages_;
  }));

  it('should do something', function () {
    expect(!!pages).toBe(true);
  });

});
