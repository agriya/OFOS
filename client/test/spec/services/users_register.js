'use strict';

describe('Service: usersRegister', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var usersRegister;
  beforeEach(inject(function (_usersRegister_) {
    usersRegister = _usersRegister_;
  }));

  it('should do something', function () {
    expect(!!usersRegister).toBe(true);
  });

});
