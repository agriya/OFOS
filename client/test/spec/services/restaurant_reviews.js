'use strict';

describe('Service: restaurantReviews', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var restaurantReviews;
  beforeEach(inject(function (_restaurantReviews_) {
    restaurantReviews = _restaurantReviews_;
  }));

  it('should do something', function () {
    expect(!!restaurantReviews).toBe(true);
  });

});
