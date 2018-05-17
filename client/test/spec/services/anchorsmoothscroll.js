'use strict';

describe('Service: anchorSmoothScroll', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var anchorSmoothScroll;
  beforeEach(inject(function (_anchorSmoothScroll_) {
    anchorSmoothScroll = _anchorSmoothScroll_;
  }));

  it('should do something', function () {
    expect(!!anchorSmoothScroll).toBe(true);
  });

});
