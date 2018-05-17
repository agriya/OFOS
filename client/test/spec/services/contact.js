'use strict';

describe('Service: contact', function () {

  // load the service's module
  beforeEach(module('ofosApp'));

  // instantiate service
  var contact;
  beforeEach(inject(function (_contact_) {
    contact = _contact_;
  }));

  it('should do something', function () {
    expect(!!contact).toBe(true);
  });

});
