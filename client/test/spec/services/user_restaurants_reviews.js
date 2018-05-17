'use strict';

describe('Service: userRestaurantsReviews', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var userRestaurantsReviews;
  beforeEach(inject(function (_userRestaurantsReviews_) {
    userRestaurantsReviews = _userRestaurantsReviews_;
  }));

  it('should do something', function () {
    expect(!!userRestaurantsReviews).toBe(true);
  });

});
