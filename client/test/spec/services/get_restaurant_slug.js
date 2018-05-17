'use strict';

describe('Service: getRestaurantSlug', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var getRestaurantSlug;
  beforeEach(inject(function (_getRestaurantSlug_) {
    getRestaurantSlug = _getRestaurantSlug_;
  }));

  it('should do something', function () {
    expect(!!getRestaurantSlug).toBe(true);
  });

});
