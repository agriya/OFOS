'use strict';

describe('Service: cuisines', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var cuisines;
  beforeEach(inject(function (_cuisines_) {
    cuisines = _cuisines_;
  }));

  it('should do something', function () {
    expect(!!cuisines).toBe(true);
  });

});
