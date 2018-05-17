'use strict';

describe('Service: usersAddresses', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var usersAddresses;
  beforeEach(inject(function (_usersAddresses_) {
    usersAddresses = _usersAddresses_;
  }));

  it('should do something', function () {
    expect(!!usersAddresses).toBe(true);
  });

});
