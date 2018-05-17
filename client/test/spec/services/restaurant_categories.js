'use strict';

describe('Service: restaurantCategories', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var restaurantCategories;
  beforeEach(inject(function (_restaurantCategories_) {
    restaurantCategories = _restaurantCategories_;
  }));

  it('should do something', function () {
    expect(!!restaurantCategories).toBe(true);
  });

});
