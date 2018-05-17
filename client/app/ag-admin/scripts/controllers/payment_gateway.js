'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:PaymentGatewayCtrl
 * @description
 * # PaymentGatewayCtrl
PaymentGatewayCtrl * Controller of the ofosApp
 */
angular.module('ofos')
    .controller('PaymentGatewayCtrl', function($scope, getGateways, postGateways, notification) {
        $scope.payment_gateways = [];
        $scope.payment_gateways_values = {};
        $scope.payment_gateways_values.test_mode_value = {};
        $scope.payment_gateways_values.live_mode_value = {};
        $scope.index = function() {
            var params = {};
            getGateways.get(params, function(response) {
                angular.forEach(response.data, function(g_value, g_key) {
                    $scope.payment_gateway = {};
                    $scope.payment_gateway.gateway_name = g_value.name;
                    $scope.payment_gateway.gateway_id = g_value.id;
                    $scope.payment_gateway.fields = [];
                    $scope.payment_gateways_values.test_mode_value[g_value.id] = {};
                    $scope.payment_gateways_values.live_mode_value[g_value.id] = {};
                    angular.forEach(g_value.test_mode_value, function(value, key) {
                        $scope.fields = {};
                        $scope.fields.field_name = key;
                        $scope.fields.fields = value;
                        $scope.payment_gateways_values.test_mode_value[g_value.id][key] = value.value;
                        $scope.payment_gateway.fields.push($scope.fields);
                    });
                    angular.forEach(g_value.live_mode_value, function(value, key) {
                        $scope.payment_gateways_values.live_mode_value[g_value.id][key] = value.value;
                    });
                    $scope.payment_gateways.push($scope.payment_gateway);
                });
            });
        };
        $scope.save = function() {
            postGateways.save($scope.payment_gateways_values, function(response) {
                if (angular.isDefined(response.error.code === 0)) {
                    notification.log('Data saved successfully', {
                        addnCls: 'humane-flatty-success'
                    });
                }
            });
        };
        $scope.index();
    });