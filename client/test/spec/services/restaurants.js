'use strict';

describe('Service: restaurants', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var restaurants;
  beforeEach(inject(function (_restaurants_) {
    restaurants = _restaurants_;
  }));

  it('should do something', function () {
    expect(!!restaurants).toBe(true);
  });

});
