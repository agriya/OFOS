'use strict';

describe('Service: languages', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var languages;
  beforeEach(inject(function (_languages_) {
    languages = _languages_;
  }));

  it('should do something', function () {
    expect(!!languages).toBe(true);
  });

});
