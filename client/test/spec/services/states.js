'use strict';

describe('Service: states', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var states;
  beforeEach(inject(function (_states_) {
    states = _states_;
  }));

  it('should do something', function () {
    expect(!!states).toBe(true);
  });

});
