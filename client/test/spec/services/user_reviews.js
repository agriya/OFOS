'use strict';

describe('Service: userReviews', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var userReviews;
  beforeEach(inject(function (_userReviews_) {
    userReviews = _userReviews_;
  }));

  it('should do something', function () {
    expect(!!userReviews).toBe(true);
  });

});
