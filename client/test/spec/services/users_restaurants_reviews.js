'use strict';

describe('Service: usersRestaurantsReviews', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var usersRestaurantsReviews;
  beforeEach(inject(function (_usersRestaurantsReviews_) {
    usersRestaurantsReviews = _usersRestaurantsReviews_;
  }));

  it('should do something', function () {
    expect(!!usersRestaurantsReviews).toBe(true);
  });

});
