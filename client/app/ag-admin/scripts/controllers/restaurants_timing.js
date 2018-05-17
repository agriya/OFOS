'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:RestaurantTimingCtrl
 * @description
 * # RestaurantTimingCtrl
 * Controller of the ofosApp
 */
angular.module('ofos')
    .controller('RestaurantTimingCtrl', function($scope, restaurantTiming, restaurant, restaurants, $window, progression, notification, $stateParams, $cookies) {
        var auth = JSON.parse($cookies.get('auth'));
        $scope.hstep = 1;
        $scope.mstep = 15;
        $scope.ismeridian = false;
        $scope.week_days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $scope.periods = ['', 'Breakfast', 'Lunch', 'Dinner'];
        $scope.durations = ['start_time', 'end_time'];
        $scope.save_btn = false;
        $scope.day = {};
        $scope.addZero = function(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
        $scope.getTime = function(date) {
            var d = new Date(date);
            var h = $scope.addZero(d.getHours());
            var m = $scope.addZero(d.getMinutes());
            var s = $scope.addZero(d.getSeconds());
            return h + ":" + m + ":" + s;
        }
        $scope.index = function() {
            var params = {};
            params.filter = '{"where":{"restaurant_id":' +$scope.restaurant_id + '},"skip":0,"limit":"all","order":"id asc"}';
            restaurantTiming.get(params, function(response) {
                $scope.days = response.data;
                angular.forEach($scope.days, function(value, key) {
                    var start_time = new Date();
                    var end_time = new Date();
                    start_time.setHours(parseInt(response.data[key].start_time.split(':')[0]));
                    start_time.setMinutes(parseInt(response.data[key].start_time.split(':')[1]));
                    end_time.setHours(parseInt(response.data[key].end_time.split(':')[0]));
                    end_time.setMinutes(parseInt(response.data[key].end_time.split(':')[1]));
                    $scope.days[key][$scope.days[key].day] = {};
                    $scope.days[key][$scope.days[key].day].period_types = {};
                    $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]] = {};
                    $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]].start_time = start_time;
                    $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]].end_time = end_time;
                });
            });
        };
        $scope.setAll = function() {
            var breakfast_start_time = "";
            var breakfast_end_time = "";
            var lunch_start_time = "";
            var lunch_end_time = "";
            var dinner_start_time = "";
            var dinner_end_time = "";
            angular.forEach($scope.week_days, function(wd_value, wd_key) {
                var i = 0;
                angular.forEach($scope.days, function(d_value, d_key) {
                    if (wd_value === d_value.day) {
                        angular.forEach(d_value[d_value.day].period_types, function(value, key) {
                            if (wd_value === 'Sunday' && wd_key === 0) {
                                if (key === 'Breakfast') {
                                    breakfast_start_time = $scope.getTime(d_value[d_value.day].period_types[key].start_time);
                                    breakfast_end_time = $scope.getTime(d_value[d_value.day].period_types[key].end_time);
                                } else if (key === 'Lunch') {
                                    lunch_start_time = $scope.getTime(d_value[d_value.day].period_types[key].start_time);
                                    lunch_end_time = $scope.getTime(d_value[d_value.day].period_types[key].end_time);
                                } else if (key === 'Dinner') {
                                    dinner_start_time = $scope.getTime(d_value[d_value.day].period_types[key].start_time);
                                    dinner_end_time = $scope.getTime(d_value[d_value.day].period_types[key].end_time);
                                }
                            }
                        });
                        i++;
                    }
                });
            });
            var params = {};
            params.filter = '{"where":{"restaurant_id":' +$scope.restaurant_id + '},"skip":0,"limit":"all","order":"id asc"}';
            restaurantTiming.get(params, function(response) {
                $scope.days = response.data;
                angular.forEach($scope.days, function(value, key) {
                    if (value.period_type === 1) {
                        var start_time = new Date();
                        var end_time = new Date();
                        start_time.setHours(parseInt(breakfast_start_time.split(':')[0]));
                        start_time.setMinutes(parseInt(breakfast_start_time.split(':')[1]));
                        end_time.setHours(parseInt(breakfast_end_time.split(':')[0]));
                        end_time.setMinutes(parseInt(breakfast_end_time.split(':')[1]));
                        $scope.days[key][$scope.days[key].day] = {};
                        $scope.days[key][$scope.days[key].day].period_types = {};
                        $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]] = {};
                        $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]].start_time = start_time;
                        $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]].end_time = end_time;
                    }
                    if (value.period_type === 2) {
                        var start_time = new Date();
                        var end_time = new Date();
                        start_time.setHours(parseInt(lunch_start_time.split(':')[0]));
                        start_time.setMinutes(parseInt(lunch_start_time.split(':')[1]));
                        end_time.setHours(parseInt(lunch_end_time.split(':')[0]));
                        end_time.setMinutes(parseInt(lunch_end_time.split(':')[1]));
                        $scope.days[key][$scope.days[key].day] = {};
                        $scope.days[key][$scope.days[key].day].period_types = {};
                        $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]] = {};
                        $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]].start_time = start_time;
                        $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]].end_time = end_time;
                    }
                    if (value.period_type === 3) {
                        var start_time = new Date();
                        var end_time = new Date();
                        start_time.setHours(parseInt(dinner_start_time.split(':')[0]));
                        start_time.setMinutes(parseInt(dinner_start_time.split(':')[1]));
                        end_time.setHours(parseInt(dinner_end_time.split(':')[0]));
                        end_time.setMinutes(parseInt(dinner_end_time.split(':')[1]));
                        $scope.days[key][$scope.days[key].day] = {};
                        $scope.days[key][$scope.days[key].day].period_types = {};
                        $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]] = {};
                        $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]].start_time = start_time;
                        $scope.days[key][$scope.days[key].day].period_types[$scope.periods[$scope.days[key].period_type]].end_time = end_time;
                    }
                });
            });
        };
        $scope.save = function() {
            if ($scope.restaurantTimingForm.$valid) {
                $scope.final_data = {};
                $scope.final_data.id = $scope.restaurant_id;
                $scope.final_data.timing = [];
                angular.forEach($scope.week_days, function(wd_value, wd_key) {
                    $scope.final_data.timing[wd_key] = {};
                    $scope.final_data.timing[wd_key].day = wd_value;
                    $scope.final_data.timing[wd_key].period = [];
                    var i = 0;
                    angular.forEach($scope.days, function(d_value, d_key) {
                        if (wd_value === d_value.day) {
                            $scope.final_data.timing[wd_key].period[i] = {};
                            angular.forEach(d_value[d_value.day].period_types, function(value, key) {
                                var start_time = $scope.getTime(d_value[d_value.day].period_types[key].start_time);
                                var end_time = $scope.getTime(d_value[d_value.day].period_types[key].end_time);
                                $scope.final_data.timing[wd_key].period[i].period_type = $scope.periods.indexOf(key);
                                $scope.final_data.timing[wd_key].period[i].start_time = start_time;
                                $scope.final_data.timing[wd_key].period[i].end_time = end_time;
                            });
                            i++;
                        }
                    });
                });
                $scope.save_btn = true;
                restaurant.update($scope.final_data, function(response) {
                    $scope.response = response;
                    if ($scope.response.error.code === 0) {
                        progression.done();
                        notification.log('Data updated successfully.', {
                            addnCls: 'humane-flatty-success'
                        });
                        $scope.save_btn = false;
                    } else {
                        $scope.save_btn = false;
                    }
                });
            } else {
                notification.log('Could not update restaurant timing, enter valid timing.', {
                    addnCls: 'humane-flatty-error'
                });
            }
        };
        $scope.loadRestaurants = function() {
            var params={};
            params.filter = '{"limit":500}';
                restaurants.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    $scope.restaurants = response.data;
                }
            });
        };
        if (angular.isDefined($stateParams.restaurant)) {
            $scope.restaurant_id = $stateParams.restaurant;
        } else {
            $scope.restaurant_id = auth.restaurant.id;
        }
        $scope.index();
    });