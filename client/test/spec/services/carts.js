'use strict';

describe('Service: carts', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var carts;
  beforeEach(inject(function (_carts_) {
    carts = _carts_;
  }));

  it('should do something', function () {
    expect(!!carts).toBe(true);
  });

});
