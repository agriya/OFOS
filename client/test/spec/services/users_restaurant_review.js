'use strict';

describe('Service: usersRestaurantReview', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var usersRestaurantReview;
  beforeEach(inject(function (_usersRestaurantReview_) {
    usersRestaurantReview = _usersRestaurantReview_;
  }));

  it('should do something', function () {
    expect(!!usersRestaurantReview).toBe(true);
  });

});
