'use strict';

describe('Service: restaurantView', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var restaurantView;
  beforeEach(inject(function (_restaurantView_) {
    restaurantView = _restaurantView_;
  }));

  it('should do something', function () {
    expect(!!restaurantView).toBe(true);
  });

});
