'use strict';

describe('Service: getCarts', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var getCarts;
  beforeEach(inject(function (_getCarts_) {
    getCarts = _getCarts_;
  }));

  it('should do something', function () {
    expect(!!getCarts).toBe(true);
  });

});
