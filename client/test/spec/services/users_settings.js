'use strict';

describe('Service: userSettings', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var userSettings;
  beforeEach(inject(function (_userSettings_) {
    userSettings = _userSettings_;
  }));

  it('should do something', function () {
    expect(!!userSettings).toBe(true);
  });

});
