'use strict';

describe('Service: userActivation', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var userActivation;
  beforeEach(inject(function (_userActivation_) {
    userActivation = _userActivation_;
  }));

  it('should do something', function () {
    expect(!!userActivation).toBe(true);
  });

});
