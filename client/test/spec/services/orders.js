'use strict';

describe('Service: orders', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var orders;
  beforeEach(inject(function (_orders_) {
    orders = _orders_;
  }));

  it('should do something', function () {
    expect(!!orders).toBe(true);
  });

});
