'use strict';

describe('Service: cities', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var cities;
  beforeEach(inject(function (_cities_) {
    cities = _cities_;
  }));

  it('should do something', function () {
    expect(!!cities).toBe(true);
  });

});
