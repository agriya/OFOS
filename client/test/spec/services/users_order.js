'use strict';

describe('Service: usersOrder', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var usersOrder;
  beforeEach(inject(function (_usersOrder_) {
    usersOrder = _usersOrder_;
  }));

  it('should do something', function () {
    expect(!!usersOrder).toBe(true);
  });

});
