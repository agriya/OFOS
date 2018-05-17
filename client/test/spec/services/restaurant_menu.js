'use strict';

describe('Service: restaurantMenu', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var restaurantMenu;
  beforeEach(inject(function (_restaurantMenu_) {
    restaurantMenu = _restaurantMenu_;
  }));

  it('should do something', function () {
    expect(!!restaurantMenu).toBe(true);
  });

});
