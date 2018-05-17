'use strict';
/**
 * @ngdoc service
 * @name ofosApp.usersLogin
 * @description
 * # usersLogin
 * Factory in the ofosApp.
 */
angular.module('ofosApp.Order.Order')
    .factory('usersLogin', function($resource) {
        return $resource('/api/v1/users/login', {}, {
            login: {
                method: 'POST'
            }
        });
    })
    .factory('twitterLogin', function($resource) {
        return $resource('/api/v1/users/social_login?type=twitter', {}, {
            login: {
                method: 'POST'
            }
        });
    });