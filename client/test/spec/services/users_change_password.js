'use strict';

describe('Service: usersChangePassword', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var usersChangePassword;
  beforeEach(inject(function (_usersChangePassword_) {
    usersChangePassword = _usersChangePassword_;
  }));

  it('should do something', function () {
    expect(!!usersChangePassword).toBe(true);
  });

});
