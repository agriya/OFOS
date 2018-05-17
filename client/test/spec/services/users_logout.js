'use strict';

describe('Service: usersLogout', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var usersLogout;
  beforeEach(inject(function (_usersLogout_) {
    usersLogout = _usersLogout_;
  }));

  it('should do something', function () {
    expect(!!usersLogout).toBe(true);
  });

});
