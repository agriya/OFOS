'use strict';

describe('Service: refreshToken', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var refreshToken;
  beforeEach(inject(function (_refreshToken_) {
    refreshToken = _refreshToken_;
  }));

  it('should do something', function () {
    expect(!!refreshToken).toBe(true);
  });

});
