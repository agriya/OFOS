'use strict';

describe('Service: usersAddress', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var usersAddress;
  beforeEach(inject(function (_usersAddress_) {
    usersAddress = _usersAddress_;
  }));

  it('should do something', function () {
    expect(!!usersAddress).toBe(true);
  });

});
