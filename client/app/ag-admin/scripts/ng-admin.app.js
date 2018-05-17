var ngapp = angular.module('ofos', ['ng-admin', 'ng-admin.jwt-auth', 'http-auth-interceptor', 'angular-md5', 'ui.bootstrap', 'ngResource', 'angular.filter', 'ngCookies', 'ui.sortable']);
ngapp.constant('user_types', {
    admin: 1,
    user: 2,
    restaurant: 3,
    supervisor: 4,
    deliveryPerson: 5
});
ngapp.constant('order_status', {
    Paymentpending: 1,
    Paymentfailed: 2,
    Pending: 3,
    Rejected: 4,
    Processing: 5,
    Deliverypersonassigned: 6,
    Delivered: 7,
    Reviewed: 8,
    AwaitingCodValidation: 9,
    Cancel: 10,
    OutForDelivery: 11
});
ngapp.constant('payment_gateways', {
    SUDOPAY: 1,
    WALLET: 2,
    COD: 3,
    PAYPAL: 4
});
var admin_api_url = '/';
var limit_per_page = 20;
var auth;
var site_settings;
var $cookies;
var token;
var enabled_plugins;
angular.injector(['ngCookies'])
    .invoke(['$cookies', function(_$cookies_) {
        $cookies = _$cookies_;
  }]);
if ($cookies.get('auth') !== undefined && $cookies.get('auth') !== null) {
    auth = JSON.parse($cookies.get('auth'));
}
if ($cookies.get('SETTINGS') !== undefined && $cookies.get('SETTINGS') !== null) {
    site_settings = JSON.parse($cookies.get('SETTINGS'));
}
if ($cookies.get('enabled_plugins') !== undefined && $cookies.get('enabled_plugins') !== null) {
    enabled_plugins = JSON.parse($cookies.get('enabled_plugins'));
}
function truncate(value) {
    if (!value) {
        return '';
    }
    return value.length > 50 ? value.substr(0, 50) + '...' : value;
}
function calculatePercentage(valueAmt, percentageGet) {
    var percentageAmt = (valueAmt * percentageGet) / 100;
    return percentageAmt;
}
function toUpperCase(str) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0)
            .toUpperCase() + txt.substr(1)
            .toLowerCase();
    });
}
ngapp.config(['$httpProvider',
  function($httpProvider) {
        $httpProvider.interceptors.push('interceptor');
  }
]);
deferredBootstrapper.bootstrap({
    element: document.body,
    module: 'ofos',
    resolve: {
        CmsConfig: function($http) {
            if(auth !== null && auth !== undefined) {
                token = auth.token;
            }
            var config = {headers:  {
                    'x-ag-app-id': '4542632501382585',
                    'x-ag-app-secret': '3f7C4l1Y2b0S6a7L8c1E7B3Jo3'
                }
            }; 
            if(token!== null && token!== undefined ){
                config.headers.Authorization = 'Bearer ' +token;
            }  
            return $http.get(admin_api_url + 'api/v1/admin-config', config);
        }
    }
});
// dashboard page redirect changes
function homeController($scope, $http, $location) {
    $location.path('/dashboard');
}
ngapp.config(function($stateProvider) {
    var getToken = {
        'TokenServiceData': function(adminTokenService, $q) {
            return $q.all({
                AuthServiceData: adminTokenService.promise,
                SettingServiceData: adminTokenService.promiseSettings
            });
        }
    };
    $stateProvider.state('timing', {
            parent: 'main',
            url: '/restaurant/timing?restaurant',
            controller: 'RestaurantTimingCtrl',
            templateUrl: 'views/restaurants_timing.html',
            resolve: getToken
        })
        .state('payment_gateways', {
            parent: 'main',
            url: '/payment_gateways',
            controller: 'PaymentGatewayCtrl',
            templateUrl: 'views/payment_gateway.html',
            resolve: getToken
        })
        .state('menus', {
            parent: 'main',
            url: '/menus',
            controller: 'MenuCtrl',
            templateUrl: 'views/menu.html',
            resolve: getToken
        }).state('home', {
            parent: 'main',
            url: '/',
            controller: homeController,
            controllerAs: 'controller',
            resolve: getToken
        })
        .state('plugins', {
            parent: 'main',
            url: '/plugins',
            controller: 'PluginsController',
            templateUrl: 'views/plugins.html',
            resolve: getToken
        })
        .state('translations', {
            parent: 'main',
            url: '/translations/all',
            controller: 'TranslationsController',
            templateUrl: 'views/translations.html',
            resolve: getToken
        })
       .state('translation_edit', {
            parent: 'main',
            url: '/translations?lang_code',
            controller: 'TranslationsController',
            templateUrl: 'views/translation_edit.html',
            resolve: getToken
        })
        .state('translation_add', {
            parent: 'main',
            url: '/translations/add',
            controller: 'TranslationsController',
            templateUrl: 'views/make_new_translation.html',
            resolve: getToken
        })        
        .state('users/change_password', {
            parent: 'main',
            url: '/change_password',
            templateUrl: 'views/changePassword.tpl.html',
            params: {
                id: null
            },
            controller: changePasswordController,
            controllerAs: 'controller',
            resolve: {}
        })
});
ngapp.directive('customHeader', ['$location', '$state', '$http', function($location, $state, $http, $scope) {
    return {
        restrict: 'E',
        scope: {},
        templateUrl: 'views/custom_header.html',
        link: function(scope) {
            scope.auth = auth;
        },
    };
}]);
ngapp.directive('loadDependField', function($compile) {
    return {
        restrict: 'E',
        scope: {
            field: "&",
            entity: "&",
            datastore: "&",
            entry: "=",
            filter: "="
        },
        link: function(scope, element, attrs) {
            const field = scope.field();
            const datastore = scope.datastore();
            scope.field = field;
            scope.field.permanentFilters(scope.filter);
            scope.datastore = datastore;
            element.html('<ma-reference-field field="::field" datastore="::datastore"></ma-reference-field>');
            $compile(element.contents())
            (scope);
        }
    };
});
ngapp.directive('changePassword', ['$location', '$state', '$http', 'notification', function ($location, $state, $http, notification) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class=\"btn btn-default btn-xs\" title="Change Password" ng-click=\"password()\" >\n<span class=\"glyphicon glyphicon-lock sync-icon\" aria-hidden=\"true\"></span>&nbsp;<span class=\"sync hidden-xs\"> {{label}}</span> <span ng-show=\"disableButton\"><i class=\"fa fa-spinner fa-pulse fa-lg\"></i></span>\n</a>',
        link: function (scope, element) {
            var id = scope.entry()
                .values.id;
            scope.password = function () {
                $state.go('users/change_password', {
                    id: id
                });
            };
        }
    };
}]);
ngapp.directive('loadRestaurantBranches', function(restaurants, $http, user_types) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function($scope, elem, attrs) {
            $scope.restaurants = [];
            $scope.refreshRestaurants = function(address) {
                var param = {};
                param.fields = 'id,name,parent_id';
                if (auth.role_id !== user_types.restaurant) {
                    return $http.get(admin_api_url + 'api/v1/restaurants?filter={"where":{"parent_id":null}}')
                        .then(function(response) {
                            restaurant_list = [];
                            $scope.restaurants = [];
                            angular.forEach(response.data.data, function(value, key) {
                                restaurant_list.push({
                                    'id': value.id,
                                    'name': value.name
                                });
                                if (parseInt(restaurant_list.length) === parseInt(response.data.data.length)) {
                                    $scope.restaurants = restaurant_list;
                                }
                            });
                        });
                } else {
                    param.filter = {"limit":1000};
                    restaurants.get(param, function(response) {
                        $scope.restaurants = response.data;
                        $scope.restaurants.push({
                            'id': auth.restaurant.id,
                            'name': 'Main branch'
                        });
                    });
                }
            };
            $scope.getRestaurant = function(item) {
                if (auth.role_id !== user_types.restaurant) {
                    $scope.restaurant_id = item.id;
                    $scope.load_branches();
                } else {
                    if (item.id !== auth.restaurant.id) {
                        $scope.entry()
                            .values.restaurant_branch_id = item.id;
                    } else {
                        $scope.entry()
                            .values.restaurant_branch_id = 0;
                    }
                }
            };
            $scope.setValues = function(id) {
                $scope.entry()
                    .values[id] = $scope[id];
            };
            $scope.load_branches = function() {
                $scope.setValues('restaurant_id');
                $scope.restaurant_branches = [];
                var params = {};
                params.filter = {"where":{"parent_id":$scope.restaurant_id}, "limit":1000};
                restaurants.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        if (response.data.length !== 0) {
                            $scope.restaurant_branches.push({
                                'id': 0,
                                'name': 'Main branch'
                            });
                            angular.forEach(response.data, function(value, key) {
                                $scope.restaurant_branches.push(value);
                            });
                        } else {
                            $scope.restaurant_branches.push({
                                'id': 0,
                                'name': 'Main branch'
                            });
                        }
                    }
                });
            };
        },
        template: '<ui-select ng-model="restaurant_id" on-select="getRestaurant($item)" style="margin-bottom:10px;"><ui-select-match>{{$select.selected.name}}</ui-select-match><ui-select-choices repeat="restaurant in restaurants track by $index" refresh="refreshRestaurants($select.search)" refresh-delay="0"><div ng-bind-html="restaurant.name | highlight: $select.search"></div></ui-select-choices></ui-select><select ng-show="restaurant_id" ng-change="setValues(\'restaurant_branch_id\')" ng-model="restaurant_branch_id" ng-options="restaurant_branch.id as restaurant_branch.name for restaurant_branch in restaurant_branches" class="form-control"><option value="" disabled selected>Please select branch</option></select>'
    };
});
ngapp.directive('loadRestaurantSupervisors', function(restaurants, restaurantSupervisors, user_types) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function($scope, elem, attrs) {
            $scope.restaurant_branches = [];
            $scope.show_supervisor = false;
            if (angular.isDefined(attrs.type)) {
                var params = {};
                params.filter = {"where":{"parent_id":null}, "limit":1000};
                restaurants.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        $scope.restaurants = response.data;
                    }
                });
            }
            $scope.setValues = function(id) {
                $scope.entry()
                    .values[id] = $scope[id];
            };
            $scope.load_branches = function() {
                $scope.setValues('restaurant_id');
                var params = {};
                params.filter = {"where":{"parent_id":$scope.restaurant_id}, "limit":1000};
                restaurants.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        $scope.restaurant_branches = [];
                        if (response.data.length !== 0) {
                            $scope.restaurant_branches.push({
                                'id': null,
                                'name': 'Main branch'
                            });
                            angular.forEach(response.data, function(value, key) {
                                $scope.restaurant_branches.push(value);
                            });
                        } else {
                            $scope.restaurant_branches.push({
                                'id': null,
                                'name': 'Main branch'
                            });
                        }
                    }
                });
            };
            $scope.load_supervisors = function() {
                $scope.setValues('restaurant_branch_id');
                var params = {};
                params.filter = {"include":{"0":"user"},"where":{"restaurant_id":$scope.restaurant_id, "restaurant_branch_id":$scope.restaurant_branch_id}, "limit":1000};
                restaurantSupervisors.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        $scope.show_supervisor = true;
                        $scope.restaurant_supervisors = response.data;
                    }
                });
            };
            if (angular.isDefined(attrs.action)) {
                $scope.restaurant_id = $scope.entry()
                    .values.restaurant_id;
                $scope.restaurant_branch_id = $scope.entry()
                    .values.restaurant_branch_id;
                $scope.restaurant_supervisor_id = $scope.entry()
                    .values.restaurant_supervisor_id;
                $scope.load_branches();
                $scope.load_supervisors();
            }
            if (angular.isUndefined(attrs.type) && parseInt(auth.role_id) === user_types.restaurant) {
                angular.element(document.querySelector('#restaurant_id')
                    .remove());
                $scope.restaurant_id = parseInt(auth.restaurant.id)
                $scope.load_branches();
            }
        },
        template: '<select selectpicker id="restaurant_id" ng-model="restaurant_id" ng-options="restaurant.id as restaurant.name for restaurant in restaurants" ng-change="load_branches()" class="form-control" style="margin-bottom:10px;"></select><select selectpicker ng-show="restaurant_id" ng-change="load_supervisors()" ng-model="restaurant_branch_id" ng-options="restaurant_branch.id as restaurant_branch.name for restaurant_branch in restaurant_branches" class="form-control" style="margin-bottom:10px;"><option value="" disabled selected>Please select branch</option></select><select selectpicker ng-show="show_supervisor" ng-change="setValues(\'restaurant_supervisor_id\')" ng-model="restaurant_supervisor_id" ng-options="restaurant_supervisor.id as restaurant_supervisor.user.username for restaurant_supervisor in restaurant_supervisors" class="form-control"><option value="" disabled selected>Please select supervisor</option></select>'
    };
});
ngapp.directive('loadRestaurantBranchSupervisor', function(restaurants, restaurantSupervisors, user_types) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function($scope, elem, attrs) {
            $scope.restaurant_branches = [];
            $scope.show_supervisor = false;
            if (angular.isDefined(attrs.type)) {
                var params = {};
                params.filter = {"where":{"parent_id":auth.restaurant.id}, "limit":1000};
                restaurants.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        $scope.restaurants = response.data;
                    }
                });
            }
            $scope.setValues = function(id) {
                $scope.entry()
                    .values[id] = $scope[id];
            };
            $scope.load_supervisors = function() {
                $scope.setValues('restaurant_branch_id');
                var params = {};
                params.filter = {"include":{"0":"user"},"where":{"restaurant_id":auth.restaurant.id, "restaurant_branch_id":$scope.restaurant_branch_id}, "limit":1000};
                restaurantSupervisors.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        $scope.show_supervisor = true;
                        $scope.restaurant_supervisors = response.data;
                    }
                });
            };
            if (angular.isDefined(attrs.action)) {
                $scope.restaurant_id = $scope.entry()
                    .values.restaurant_id;
                $scope.restaurant_branch_id = $scope.entry()
                    .values.restaurant_branch_id;
                $scope.restaurant_supervisor_id = $scope.entry()
                    .values.restaurant_supervisor_id;
                $scope.load_supervisors();
            }
            if (angular.isUndefined(attrs.type) && parseInt(auth.role_id) === user_types.restaurant) {
                angular.element(document.querySelector('#restaurant_id')
                    .remove());
                $scope.restaurant_id = parseInt(auth.restaurant.id)
            }
        },
        template: '<select selectpicker id="restaurant_branch_id" ng-model="restaurant_branch_id" ng-options="restaurant.id as restaurant.name for restaurant in restaurants" ng-change="load_supervisors()" class="form-control" style="margin-bottom:10px;"></select><select selectpicker ng-show="show_supervisor" ng-change="setValues(\'restaurant_supervisor_id\')" ng-model="restaurant_supervisor_id" ng-options="restaurant_supervisor.id as restaurant_supervisor.user.username for restaurant_supervisor in restaurant_supervisors" class="form-control"><option value="" disabled selected>Please select supervisor</option></select>'
    };
});

ngapp.directive('displayImage', function(md5) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            scope.type = attrs.type;
            if (angular.isDefined(scope.entry()
                    .values['attachment.id']) && scope.entry()
                .values['attachment.id'] !== null && scope.entry()
                .values['attachment.id'] !== 0) {
                var hash = md5.createHash(scope.type + scope.entry()
                    .values['attachment.id'] + 'png' + 'micro_thumb');
                scope.image = '../images/micro_thumb/' + scope.type + '/' + scope.entry()
                    .values['attachment.id'] + '.' + hash + '.png';
            } else {
                if (scope.type === 'Restaurant') {
                    scope.image = '../images/no-image-restaurant-42x42.png';
                } else {
                    scope.image = '../images/no-image-menu-42x42.png';
                }
            }
        },
        template: '<img ng-src="{{image}}" height="42" width="42" />'
    };
});
ngapp.directive('pickupOrDelivery', function(md5) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            if (angular.isDefined(scope.entry()
                    .values['is_pickup_or_delivery'])) {
                if (scope.entry().values['is_pickup_or_delivery'] == 0) {
                        scope.delivery = 'Pickup';
                }
                else if(scope.entry().values['is_pickup_or_delivery'] == 1) {
                    scope.delivery = 'Delivery';
                }
            }
        },
        template: '<p height="42" width="42">{{delivery}}</p>'
    };
});
ngapp.directive('displayOrder', function(md5) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            scope.order = scope.entry()
                .values;
            var sub_total = 0;
            angular.forEach(scope.order.order_items, function(value, key) {
                //jshint unused:false
                sub_total += parseFloat(value.restaurant_menu_price.price) * parseFloat(value.quantity);
            });
            scope.currency = site_settings.CURRENCY_SYMBOL;
            scope.sub_total = sub_total;
            scope.sales_tax = (scope.sub_total * parseFloat(scope.order.sales_tax) / 100);
        },
        templateUrl: 'views/order.html'
    };
});
ngapp.directive('changeStatus', function(notification, $state) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function($scope, $http) {
            if ($scope.entry()
                .values.is_pickup_or_delivery === true) {
                $scope.status = [{
                        id: 5,
                        status: "Accept"
          },
                    {
                        id: 4,
                        status: "Reject"
          }
        ];
            } else {
                $scope.status = [{
                    id: 7,
                    status: "Delivered"
        }];
            }
            $scope.change = function() {
                var params = {};
                params.order_status_id = $scope.status_id;
                $http.put(admin_api_url + 'api/v1/orders/' + $scope.entry()
                        .values.id + '?restaurant_id=' + $scope.entry()
                        .values.restaurant_id, params)
                    .success(function(response) {
                        if (angular.isDefined(response.error.code === 0)) {
                            $scope.entry()
                                .values.order_status_id = $scope.status_id;
                            notification.log('Status changed successfully', {
                                addnCls: 'humane-flatty-success'
                            });
                            $state.reload();
                        }
                    });
            };
        },
        template: '<select class="form-control action-select input-sm" ng-change="change()" ng-model="status_id"><option value="" disabled selected>Status</option><option ng-repeat="x in status" value="{{x.id}}">{{x.status}}</option></select>'
    };
});
ngapp.directive('dashboardSummary', ['$location', '$state', '$timeout', '$http', 'CustomFactory', function($location, $state, $timeout, $http, CustomFactory) {
    return {
        restrict: 'E',
        scope: {},
        templateUrl: 'views/dashboardSummary.html',
        link: function(scope, elem, attr) {
            scope.auth = auth;
            $http.get(admin_api_url + 'api/v1/stats')
                .success(function(response) {
                    scope.adminstats = response;
                    scope.enabled_plugins = enabled_plugins;
                });
        }
    };
}]);
ngapp.directive('deliveryPerson', function($http, user_types, notification, restaurants, restaurantSupervisors, restaurantDeliveryPersons, $uibModal, $state, order_status) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@",
            type: '@'
        },
        link: function($scope, elem, attrs) {
            $scope.zoomThumbnail = function($event) {
                $event.preventDefault();
                $uibModal.open({
                    backdrop: true,
                    scope: $scope,
                    controller: function($scope, $uibModalInstance) {
                        $scope.close = function() {
                            $uibModalInstance.close();
                        }
                        $scope.show_supervisor = true;
                        $scope.load_supervisors = function() {
                            var params = {};
                            if (attrs.type === 'site') {
                                params.filter = {"include":{"0":"user"},"where":{"restaurant_id":null},"limit":"all"};
                            } 
                            if (attrs.type === 'restaurant') {
                                params.filter = {"include":{"0":"user"},"where":{"restaurant_id":$scope.restaurant_id,"restaurant_branch_id":$scope.restaurant_branch_id},"limit":"all"};
                            }
                            restaurantSupervisors.get(params, function(response) {
                                if (angular.isDefined(response.data)) {
                                    $scope.restaurant_supervisors = response.data;
                                }
                            });
                        };
                        $scope.load_delivery_persons = function() {
                            var params = {};
                            params.filter = {"include":{"0":"user"},"where":{"restaurant_supervisor_id":$scope.restaurant_supervisor_id},"limit":"all"};
                            restaurantDeliveryPersons.get(params, function(response) {
                                if (angular.isDefined(response.data)) {
                                    $scope.restaurant_delivery_persons = response.data;
                                }
                            });
                        };
                        if (parseInt(auth.role_id) === user_types.admin || parseInt(auth.role_id) === user_types.restaurant) {
                            $scope.restaurant_id = $scope.entry()
                                .values.restaurant_id;
                            $scope.restaurant_branch_id = $scope.entry()
                                .values.restaurant_branch_id;
                            $scope.load_supervisors();
                        }
                        if (parseInt(auth.role_id) === user_types.supervisor) {
                            $scope.show_supervisor = false;
                            $scope.restaurant_id = $scope.entry()
                                .values.restaurant_id;
                            $scope.restaurant_branch_id = $scope.entry()
                                .values.restaurant_branch_id;
                            $scope.restaurant_supervisor_id = auth.restaurant_supervisor.id;
                            $scope.load_delivery_persons();
                        }
                        $scope.submitCreation = function($event) {
                            var params = {};
                            params.restaurant_delivery_person_id = $scope.restaurant_delivery_person_id;
                            params.order_status_id = order_status.Deliverypersonassigned;
                            $http.put(admin_api_url + 'api/v1/orders/' + $scope.entry()
                                    .values.id + '?restaurant_id'+ $scope.restaurant_id, params)
                                .success(function(response) {
                                    if (angular.isDefined(response.error.code === 0)) {
                                        notification.log('Order assigned successfully', {
                                            addnCls: 'humane-flatty-success'
                                        });
                                        $scope.close();
                                        $state.reload();
                                    }
                                });
                        };
                    },
                    template: '<div class="modal-header"><h4><strong>Assign to delivery person</strong></h4></div><div class="modal-body"><div class="row" ng-class="::\'ng-admin-entity-\' + formController.entity.name()"><form class="col-lg-12 form-horizontal" name="formController.form" ng-submit="submitCreation($event)"><select selectpicker ng-show="show_supervisor" ng-change="load_delivery_persons()" id="restaurant_supervisor_id" ng-model="restaurant_supervisor_id" ng-options="restaurant_supervisor.id as restaurant_supervisor.user.username for restaurant_supervisor in restaurant_supervisors" class="form-control action-select" style="margin-bottom:10px;"><option value="" disabled selected>Please select supervisor</option></select><select selectpicker ng-show="restaurant_supervisor_id" ng-model="restaurant_delivery_person_id" ng-options="restaurant_delivery_person.id as restaurant_delivery_person.user.username for restaurant_delivery_person in restaurant_delivery_persons" class="form-control action-select" style="margin-bottom:10px;"><option value="" disabled selected>Please select delivery persons</option></select><div ng-repeat="field in newForm._views.CreateView._fields" compile="::field.getTemplateValueWithLabel(entry)"><ma-field field="::field" value="entry.values[field.name()]" entry="entry" entity="::entity" form="formController.form" datastore="::formController.dataStore"></ma-field></div><div class="form-group" ng-show="restaurant_delivery_person_id"><div class="col-sm-offset-5 col-sm-5"><ma-submit-button label="SUBMIT"></ma-submit-button></div></div></form></div></div><div class="modal-footer"><button ng-click="close()" class="btn btn-success" >Close</button></div>'
                });
            }
        },
        template: '<p ng-click="zoomThumbnail($event)"><a class="btn btn-xs bg-primary"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>&nbsp;<span class="hidden-xs" translate="Assign">Assign</span></a></p>'
    };
});
ngapp.directive('starRating', function() {
    return {
        restrict: 'E',
        scope: {
            stars: '@'
        },
        link: function(scope, elm, attrs, ctrl) {
            scope.starsArray = Array.apply(null, {
                    length: parseInt(scope.stars)
                })
                .map(Number.call, Number);
        },
        template: '<i ng-repeat="star in starsArray" class="glyphicon glyphicon-star"></i>'
    };
});
ngapp.directive('changeOrderStatus', function($state, order_status) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&"
        },
        controller: function($scope, $http, notification, $timeout, $location) {
            $scope.update = function() {
                var params = {};
                params.restaurant_delivery_person_id = $scope.entry()
                    .values.restaurant_delivery_person_id;
                params.order_status_id = order_status.Delivered;
                $http.put(admin_api_url + 'api/v1/orders/' + $scope.entry()
                        .values.id, params)
                    .success(function(response) {
                        if (angular.isDefined(response.error.code === 0)) {
                            $scope.success = true;
                            notification.log('Status changed successfully', {
                                addnCls: 'humane-flatty-success'
                            });
                            $state.reload();
                        }
                    });
            };
        },
        template: '<button type="button" ng-disabled="success" class="btn btn-default btn-xs" ng-click="update()">Delivered</button>'
    };
});
ngapp.directive('outForDelivery', function($state, order_status) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&"
        },
        controller: function($scope, $http, notification, $timeout, $location) {
            $scope.update = function() {
                var params = {};
                params.restaurant_delivery_person_id = $scope.entry()
                    .values.restaurant_delivery_person_id;
                params.order_status_id = order_status.OutForDelivery;
                $http.put(admin_api_url + 'api/v1/orders/' + $scope.entry()
                        .values.id, params)
                    .success(function(response) {
                        if (angular.isDefined(response.error.code === 0)) {
                            $scope.success = true;
                            notification.log('Status changed successfully', {
                                addnCls: 'humane-flatty-success'
                            });
                            $state.reload();
                        }
                    });
            };
        },
        template: '<button type="button" ng-disabled="success" class="btn btn-default btn-xs" ng-click="update()">Out For Delivery</button>'
    };
});
ngapp.directive('addonItemBasket', function() {
    return {
        restrict: 'E',
        scope: {
            entry: "&",
            type: "@"
        },
        templateUrl: 'views/addon_item.html',
        controller: function($scope) {
            $scope.view_type = false;
            if ($scope.type === "show") {
                $scope.view_type = true;
            }
            $scope.restaurant_addon_item = [];
            // for create view - no AddonItem
            if ($scope.entry()
                .values.restaurant_addon_item === null || $scope.entry()
                .values.restaurant_addon_item === undefined) {
                $scope.restaurant_addon_item = [];
            } else {
                if ($scope.entry()
                    .values.restaurant_addon_item.length > 0) {
                    $scope.restaurant_addon_item = $scope.entry()
                        .values.restaurant_addon_item;
                }
            }
            $scope.entry()
                .values.restaurant_addon_item = $scope.restaurant_addon_item;
            $scope.addAddonItem = function() {
                $scope.restaurant_addon_item.push({
                    name: ''
                });
            };
            $scope.deleteAddonItem = function(schedule_id, index) {
                $scope.restaurant_addon_item.splice(index, 1);
            };
        }
    };
});
ngapp.directive('paymentGateway', function(paymentGateway, sudopaySynchronize, payment_gateways) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&"
        },
        controller: function($rootScope, $scope, $location, notification) {
            angular.element(document.querySelector('ma-submit-button')
                .remove());
            $scope.test_mode_value = {};
            $scope.live_mode_value = {};
            $scope.liveMode = false;
            $scope.save = function() {
                $scope.data = {};
                if($scope.liveMode === true)
                {
                    $scope.data.live_mode_value = $scope.live_mode_value;
                    $scope.data.is_live_mode = true;
                } else {
                    $scope.data.test_mode_value = $scope.test_mode_value;
                    $scope.data.is_live_mode = false;
                }
                $scope.data.id = $scope.entry()
                    .values.id;
                paymentGateway.update($scope.data, function(response) {
                    if (angular.isDefined(response.error.code === 0)) {
                        notification.log('Data updated successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                    }
                });
            };
            $scope.sudopay_synchronize = function() {
                sudopaySynchronize.get({}, function(response) {
                    if (angular.isDefined(response.error.code === 0)) {
                        notification.log('Synchronize with sudopay successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                    }
                });
            };
            $scope.index = function() {
                if ($scope.entry().values.id === payment_gateways.PAYPAL) {
                    $scope.paypal_rest = true;

                } else {
                    $scope.paypal_rest = false;
                }
                angular.forEach($scope.entry()
                    .values.payment_settings,
                    function(value, key) {
                        $scope.test_mode_value[value.name] = value.test_mode_value;
                        $scope.live_mode_value[value.name] = value.live_mode_value;
                    });
            };
            $scope.index();
        },
        template: '<div><input type="checkbox" ng-model="liveMode">&nbsp;&nbsp;Live Mode?</div><table><tr><th></th><th>Live Mode Credential</th><th>&nbsp;</th><th>Test Mode Credential</th></tr><span ng-show="paypal_rest == true"><tr ng-if="paypal_rest !== false"><td>Client ID &nbsp;&nbsp;</td><td><input type="text" ng-model="live_mode_value.paypal_client_id" class="form-control" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" class="form-control" ng-model="test_mode_value.paypal_client_id" style="margin-bottom:10px;"></td></tr><tr ng-if="paypal_rest !== false"><td>Client Secret &nbsp;&nbsp;</td><td><input type="text" ng-model="live_mode_value.paypal_client_Secret" class="form-control" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" class="form-control" ng-model="test_mode_value.paypal_client_Secret" style="margin-bottom:10px;"></td></tr></span><tr ng-if="paypal_rest !== true"><td>Merchant ID &nbsp;&nbsp;</td><td><input type="text" ng-model="live_mode_value.sudopay_merchant_id" class="form-control" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" class="form-control" ng-model="test_mode_value.sudopay_merchant_id" style="margin-bottom:10px;"></td></tr><tr ng-if="paypal_rest !== true"><td>Website ID</td><td><input type="text" class="form-control" ng-model="live_mode_value.sudopay_website_id" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" class="form-control" ng-model="test_mode_value.sudopay_website_id" style="margin-bottom:10px;"></td></tr><tr ng-if="paypal_rest !== true"><td>Secret Key</td><td><input type="text" ng-model="live_mode_value.sudopay_secret_string" class="form-control" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" ng-model="test_mode_value.sudopay_secret_string" class="form-control" style="margin-bottom:10px;"></td></tr><tr ng-if="paypal_rest !== true"><td>API Key</td><td><input type="text" ng-model="live_mode_value.sudopay_api_key" class="form-control" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" ng-model="test_mode_value.sudopay_api_key" class="form-control" style="margin-bottom:10px;"></td></tr><tr><td>&nbsp;</td><td><button type="button" ng-click="save()" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;<span class="hidden-xs">Save changes</span></button></td><td>&nbsp;</td><td ng-if="paypal_rest !== true"><button type="button" ng-click="sudopay_synchronize()" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span>&nbsp;<span class="hidden-xs">Sync with ZazPay</span></button></td></tr></table>',
    };
});
ngapp.directive('batchActive', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Active' : 'Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? 'Active' : 'Active';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_active = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchDeactive', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.icon = attrs.type == 'deactive' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_active = 0;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchActions', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            action: '@',
            icon: '@',
            label: '@',
            resource: '@'
        },
        link: function(scope, element, attrs) {
            scope.updateStatus = function(resource) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + resource + '/' + e.values.id);
                        
                        // @TODO action set by loop
                        if (scope.action == 'active') {
                            p.is_active = 1;
                        } else {
                            p.is_active = 0;
                        }
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + label, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(resource)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('googlePlaces', ['$location', function($location) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        link: function(scope) {
            var inputFrom = document.getElementById('goo-place');
            var autocompleteFrom = new google.maps.places.Autocomplete(inputFrom);
            google.maps.event.addListener(autocompleteFrom, 'place_changed', function() {
                var place = autocompleteFrom.getPlace();
                scope.entry()
                    .values.latitude = place.geometry.location.lat();
                scope.entry()
                    .values.longitude = place.geometry.location.lng();
                angular.forEach(place.address_components, function(value, key) {
                    //jshint unused:false
                    if (value.types[0] === 'premise' || value.types[0] === 'route') {
                        if (scope.entry()
                            .values['address'] !== '') {
                            scope.entry()
                                .values['address'] = scope.entry()
                                .values['address'] + ', ' + value.long_name;
                        } else {
                            scope.entry()
                                .values['address'] = value.long_name;
                        }
                    }
                    if (value.types[0] === 'sublocality_level_1' || value.types[0] === 'sublocality_level_2') {
                        if (scope.entry()
                            .values['address1'] !== '') {
                            scope.entry()
                                .values['address1'] = scope.entry()
                                .values['address1'] + ', ' + value.long_name;
                        } else {
                            scope.entry()
                                .values['address1'] = value.long_name;
                        }
                    }
                    if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                        scope.entry()
                            .values['city.name'] = value.long_name;
                    }
                    if (value.types[0] === 'administrative_area_level_1') {
                        scope.entry()
                            .values['state.name'] = value.long_name;
                    }
                    if (value.types[0] === 'country') {
                        scope.entry()
                            .values['country.iso2'] = value.short_name;
                    }
                    if (value.types[0] === 'postal_code') {
                        scope.entry()
                            .values.zip_code = parseInt(value.long_name);
                    }
                });
                scope.$apply();
            });
        },
        template: '<input class="form-control" id="goo-place"/><p class="help-text" class="form-text text-muted">You must select address from autocomplete</p>'
    };
}]);
/* Used in Settings edit */
ngapp.directive('inputType', function() {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            elem.bind('change', function() {
                scope.$apply(function() {
                    scope.entry()
                        .values.value = scope.value;
                    if (scope.entry()
                        .values.type === 'checkbox') {
                        scope.entry()
                            .values.value = scope.value ? 1 : 0;
                    }
                });
            });
        },
        controller: function($scope) {
            $scope.text = true;
            $scope.value = $scope.entry()
                .values.value;
            if ($scope.entry()
                .values.type === 'checkbox') {
                $scope.text = false;
                $scope.value = Number($scope.value);
            }
        },
        template: '<textarea ng-model="$parent.value" id="value" name="value" class="form-control" ng-if="text"></textarea><input type="checkbox" ng-model="$parent.value" id="value" name="value" ng-if="!text" ng-true-value="1" ng-false-value="0" ng-checked="$parent.value == 1"/>'
    };
});
//Change password controller defined here.
function changePasswordController($state, $scope, $http, $location, notification) {
    var id = $state.params.id;
    $scope.ChangePassword = function () {
        $http.put('/api/v1/users/' + id + '/change_password', $scope.passwordArr)
            .success(function (response) {
                if (response.error.code === 0) {
                    notification.log("Password Changed Successfully", {
                        addnCls: 'humane-flatty-success'
                    });
                    $location.path('/users/list');
                }
            })
            .catch(function (error) {
                notification.log(error.data.message, {
                    addnCls: 'humane-flatty-error'
                });
            });
    };
}
ngapp.config(['RestangularProvider', 'user_types', 'order_status', function(RestangularProvider, userTypes, orderStatus) {
    RestangularProvider.setDefaultHeaders({'x-ag-app-secret': '3f7C4l1Y2b0S6a7L8c1E7B3Jo3'});
    RestangularProvider.setDefaultHeaders({'x-ag-app-id': '4542632501382585'});
    RestangularProvider.addFullRequestInterceptor(function(element, operation, what, url, headers, params) {
        headers = headers || {};
        headers['x-ag-app-secret'] = '3f7C4l1Y2b0S6a7L8c1E7B3Jo3';
        var filter = {};
        if (operation === 'post') {
            if (auth.role_id == userTypes.restaurant) {
                if(url == '/api/v1/own_restaurant_delivery_persons'){
                    element.restaurant_id = auth.restaurant.id;
                }
                else if(url == '/api/v1/own_restaurant_restaurant_supervisors'){
                    element.restaurant_id = auth.restaurant.id;
                }
                else if(url == '/api/v1/restaurant_menus'){
                    element.restaurant_id = auth.restaurant.id;
                }
            } else if (auth.role_id == userTypes.supervisor) {
                if(url == '/api/v1/own_restaurant_delivery_persons'){
                    element.restaurant_id = auth.restaurant_supervisor.restaurant_id;
                    element.restaurant_branch_id = auth.restaurant_supervisor.restaurant_branch_id;
                }
            }
                
        }
        if (operation === 'put') {
            if (auth.role_id == userTypes.restaurant) {
                if(url == '/api/v1/own_restaurant_delivery_persons'){
                    element.restaurant_id = auth.restaurant.id;
                }
                else if(url == '/api/v1/own_restaurant_restaurant_supervisors'){
                    element.restaurant_id = auth.restaurant.id;
                }
                else if(url == '/api/v1/restaurant_menus'){
                    element.restaurant_id = auth.restaurant.id;
                }
                else if(url == '/api/v1/restaurant_addons'){
                    element.restaurant_id = auth.restaurant.id;
                }
            } else if (auth.role_id == userTypes.supervisor) {
                if(url == '/api/v1/own_restaurant_delivery_persons'){
                    element.restaurant_id = auth.restaurant_supervisor.restaurant_id;
                    element.restaurant_branch_id = auth.restaurant_supervisor.restaurant_branch_id;
                }
            }
                
        }
        if (operation === 'getList') {
            var whereCond = {};
            if(url == '/api/v1/users'){
                filter.include = {"0": "role", "1": "city", "2": "state", "3": "country", "4": "restaurant_delivery_person", "5":"restaurant_supervisor", "6": "restaurant"};
                if (angular.isDefined(params._filters)) {
                    if (angular.isDefined(params._filters.supervisor) && params._filters.supervisor == 'site_supervisor') {
                        filter.include = {"restaurant_supervisor":{"whereHas":{"restaurant_id":null}}};
                        delete params._filters.supervisor;
                    }
                }
            }          
            else if(url == '/api/v1/restaurants'){
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['user_id'] = auth.id;
                    filter.include = {"0": "city", "1":"user", "2": "state", "3": "country", "4": "attachment"};
                }
            }
            else if(url == '/api/v1/cities'){
                filter.include = {"0": "state","1": "country"};
            }
            else if(url == '/api/v1/states'){
                filter.include = {"0": "country"};
            }
            else if(url == '/api/v1/contacts'){
                filter.include = {"0": "ip"};
            }
            else if(url == '/api/v1/shops'){
                filter.include = {"0": "city", "1":"user", "2": "state", "3": "country", "4": "attachment"};
            }
            else if(url == '/api/v1/restaurant_supervisors'){
                whereCond['restaurant_id'] = null;
                filter.include = {"0": "user","1": "restaurant_branch", "2": "restaurant"};
            }
            else if(url == '/api/v1/restaurant_delivery_persons'){
                whereCond['restaurant_id'] = null;
                filter.include = {"0": "user", "1": "restaurant_branch", "2": "restaurant", "restaurant_supervisor":["user"]};
            }
            else if(url == '/api/v1/restaurant_addons'){
                filter.include = {"0": "restaurant","1":"restaurant_category","2":"restaurant_addon_item"};
                if (auth.role_id == userTypes.restaurant) {
                    filter.include = {"1":"restaurant_category","2":"restaurant_addon_item","restaurant":{"whereHas":{"user_id":auth.id}}};
                }
            }
            else if(url == '/api/v1/user_cash_withdrawals'){
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['user_id'] = auth.id;
                }
                filter.include = {"0": "restaurant","1":"user","2":"money_transfer_account"};
            }
            else if(url == '/api/v1/transactions'){
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['user_id'] = auth.id;
                }
                filter.include = {"0": "user", "1": "restaurant", "2": "other_user", "3": "transaction_type", "4": "order"};
            }
            else if(url == '/api/v1/orders'){
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['restaurant_id'] = auth.restaurant.id;
                }
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":false}}};
            }
            else if(url == '/api/v1/restaurant_reviews'){
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['restaurant_id'] = auth.restaurant.id;
                }
                filter.include = {"0": "user","1": "restaurant", "order":["restaurant"]};
            }
            else if(url == '/api/v1/restaurant_menus'){
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['restaurant_id'] = auth.restaurant.id;
                }
                filter.include = {"0": "cuisine", "1": "restaurant", "2":"restaurant_categories"};
            }
            else if(url == '/api/v1/coupons'){
                filter.include = {"0": "user", "1": "restaurant"};
            }
            else if(url == '/api/v1/own_restaurant_delivery_orders'){
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['restaurant_id'] = auth.restaurant.id;
                }
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":true}}};
            }
            else if(url == '/api/v1/own_restaurant_delivery_persons'){
                whereCond['restaurant_id'] = {"nin":{"0":0}};
                if (auth.role_id == userTypes.supervisor) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['restaurant_id'] = auth.restaurant.id;
                }
                filter.include = {"0": "user", "1": "restaurant_branch", "2": "restaurant","restaurant_supervisor":["user"]};
            }
            else if(url == '/api/v1/assingned_orders'){
                if (auth.role_id == userTypes.deliveryPerson) {
                    whereCond['restaurant_delivery_person_id'] = auth.restaurant_delivery_person.id;
                }
                whereCond['order_status_id'] = orderStatus.Deliverypersonassigned;
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":false}}};
            }
            else if(url == '/api/v1/own_restaurant_assingned_orders'){
                whereCond['order_status_id'] = orderStatus.Deliverypersonassigned;
                if (auth.role_id == userTypes.supervisor) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }                
                if (auth.role_id == userTypes.deliveryPerson) {
                    whereCond['restaurant_id'] = auth.restaurant_delivery_person.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_delivery_person.restaurant_branch_id;
                    whereCond['restaurant_delivery_person_id'] = auth.restaurant_delivery_person.id;
                }
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":true}}};
            }
            else if(url == '/api/v1/delivered_orders'){
                if (auth.role_id == userTypes.supervisor && angular.isDefined(auth.restaurant_supervisor) && auth.restaurant_supervisor.restaurant_id !== null) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }
                if (auth.role_id == userTypes.deliveryPerson) {
                    whereCond['restaurant_delivery_person_id'] = auth.restaurant_delivery_person.id;
                }
                whereCond['order_status_id'] = orderStatus.Delivered;
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":false}}};
            }
            else if(url == '/api/v1/own_restaurant_delivered_orders'){
                whereCond['order_status_id'] = orderStatus.Delivered;
                if (auth.role_id == userTypes.supervisor) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }
                if (auth.role_id == userTypes.deliveryPerson) {
                    whereCond['restaurant_id'] = auth.restaurant_delivery_person.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_delivery_person.restaurant_branch_id;
                    whereCond['restaurant_delivery_person_id'] = auth.restaurant_delivery_person.id;
                }
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":true}}};
            }
            else if(url == '/api/v1/out_for_delivery_orders') {
                if (auth.role_id == userTypes.supervisor && angular.isDefined(auth.restaurant_supervisor) && auth.restaurant_supervisor.restaurant_id !== null) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }
                if (auth.role_id == userTypes.deliveryPerson) {
                    whereCond['restaurant_delivery_person_id'] = auth.restaurant_delivery_person.id;
                }
                whereCond['order_status_id'] = orderStatus.OutForDelivery;
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":false}}};
            }
            else if(url == '/api/v1/own_restaurant_out_for_delivery_orders'){
                whereCond['order_status_id'] = orderStatus.OutForDelivery;
                if (auth.role_id == userTypes.supervisor) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }
                if (auth.role_id == userTypes.deliveryPerson) {
                    whereCond['restaurant_id'] = auth.restaurant_delivery_person.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_delivery_person.restaurant_branch_id;
                    whereCond['restaurant_delivery_person_id'] = auth.restaurant_delivery_person.id;
                }
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":true}}};
            }
            else if(url == '/api/v1/own_restaurant_restaurant_supervisors'){
                whereCond['restaurant_id'] = {"nin":{"0":0}};
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['restaurant_id'] = auth.restaurant.id;
                }
                filter.include = {"0": "user","1": "restaurant_branch", "2": "restaurant"};
            }
            else if(url == '/api/v1/processing_orders'){
                if (auth.role_id == userTypes.supervisor && angular.isDefined(auth.restaurant_supervisor) && auth.restaurant_supervisor.restaurant_id !== null) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }
                whereCond['order_status_id'] = 5;
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":false}}};
            }
            else if(url == '/api/v1/own_restaurant_processing_orders'){
                whereCond['order_status_id'] = 5;
                if (auth.role_id == userTypes.supervisor) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":true}}};
            }
            else if(url == '/api/v1/pending_orders'){
                if (auth.role_id == userTypes.supervisor && angular.isDefined(auth.restaurant_supervisor) && auth.restaurant_supervisor.restaurant_id !== null) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }
                whereCond['order_status_id'] = 3;
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":false}}};
            }
            else if(url == '/api/v1/own_restaurant_pending_orders'){
                whereCond['order_status_id'] = 3;
                if (auth.role_id == userTypes.supervisor) {
                    whereCond['restaurant_id'] = auth.restaurant_supervisor.restaurant_id;
                    whereCond['restaurant_branch_id'] = auth.restaurant_supervisor.restaurant_branch_id;
                }
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":true}}};
            }
            else if(url == '/api/v1/money_transfer_accounts'){
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['user_id'] = auth.id;
                }
                filter.include = {"0": "user"};
            }
        }
        else if (operation === 'get') {
            if(url.indexOf('/api/v1/restaurant_reviews') !== -1) {
                filter.include = {"0": "user","1": "restaurant", "order":["restaurant"]};
            }
            if(url.indexOf('/api/v1/restaurant_delivery_persons') !== -1) {
                filter.include = {"0": "user", "1": "restaurant", "2": "restaurant_branch","restaurant_supervisor":["user"]};
            }
            if(url.indexOf('/api/v1/orders') !== -1) {
                 filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "10": "restaurant"};
            }
            if(url.indexOf('/api/v1/restaurant_reviews') !== -1) {
                filter.include = {"0": "user","1": "restaurant", "order":["restaurant"]};
            }
            if(url.indexOf('/api/v1/user_cash_withdrawals') !== -1) {
                if (auth.role_id == userTypes.restaurant) {
                    whereCond['user_id'] = auth.id;
                }
                filter.include = {"0": "restaurant","1":"user","2":"money_transfer_account"};
            }
            if(url.indexOf('/api/v1/restaurant_addons') !== -1) {
                filter.include = {"0": "restaurant","1":"restaurant_category","2":"restaurant_addon_item"};
            }
            if(url.indexOf('/api/v1/restaurant_supervisors') !== -1) {
                filter.include = {"0": "user","1": "restaurant","2": "restaurant_branch"};
            }
            if(url.indexOf('/api/v1/shops') !== -1) {
                filter.include = {"0": "city", "1":"user", "2": "state", "3": "country", "4":"restaurant_cuisine.cuisine"};
            }
            if(url.indexOf('/api/v1/restaurants') !== -1) {
                filter.include = {"0": "city", "1":"user", "2": "state", "3": "country", "4":"restaurant_cuisine.cuisine"};
            }
            if(url.indexOf('/api/v1/users') !== -1) {
                filter.include = {"0": "role", "1": "city", "2": "state", "3": "country", "4": "restaurant_delivery_person", "5":"restaurant_supervisor", "6": "restaurant"};
            }
            if(url.indexOf('/api/v1/cities') !== -1) {
                filter.include = {"0": "state","1": "country"};
            }
            if(url.indexOf('/api/v1/states') !== -1) {
                filter.include = {"0": "country"};
            }
            if(url.indexOf('/api/v1/contacts') !== -1) {
                filter.include = {"0": "ip"};
            }
            if(url.indexOf('/api/v1/restaurant_menus') !== -1) {
                filter.include = {"0": "cuisine", "1": "restaurant", "2":"restaurant_categories"};
            }
            if(url.indexOf('/api/v1/coupons') !== -1) {
                filter.include = {"0": "user", "1": "restaurant"};
            }
            if(url.indexOf('/api/v1/payment_gateways') !== -1) {
                filter.include = {"0": "payment_settings"};
            }
            if(url.indexOf('/api/v1/own_restaurant_delivered_orders') !== -1) {
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":true}}};
            }
            if(url.indexOf('/api/v1/delivered_orders') !== -1) {
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":false}}};
            }
            if(url.indexOf('/api/v1/own_restaurant_assingned_orders') !== -1) {
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":true}}};
            }
            if(url.indexOf('/api/v1/out_for_delivery_orders') !== -1) {
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":false}}};
            }
            if(url.indexOf('/api/v1/own_restaurant_out_for_delivery_orders') !== -1) {
                filter.include = {"0": "user","1": "city","2": "state","3": "country", "delivery_person":["user"],"5": "order_status","6": "user_address","7": "payment_gateway","8": "order_items", "9": "order_item_addons", "restaurant":{"whereHas":{"is_delivered_by_own":true}}};
            }

        }
        var addtional_param = {};
            for (var k in params) {
                if (params.hasOwnProperty(k)) {
                    if (k == "_page") {
                        filter.skip = (params[k] - 1) * params._perPage;
                        filter.limit = params._perPage;
                    }
                    else if (k == "_sortField") {
                        if (params._sortDir) {
                            filter.order = params[k] + ' ' +params._sortDir;
                        }else{
                            filter.order = params[k] + ' DESC';
                        }
                    }
                    else if (k == "_filters") {                        
                        for (var field in params._filters) {
                            if(field !== 'q' && field != 'autocomplete'){
                                whereCond[field] = params[k][field];
                            }else{
                                addtional_param[field] = params[k][field];
                            }
                        }
                    }
                    if(Object.keys(whereCond).length > 0){
                        filter.where = whereCond;
                    }                  
                }
            }
            if(Object.keys(filter).length > 0 || Object.keys(addtional_param).length > 0){
                filter = JSON.stringify(filter);
                filter = {'filter': filter};
                Object.assign(filter, addtional_param);
            }
        return {
            params: filter,
            url: url
        };
    });
    RestangularProvider.addResponseInterceptor(function(data, operation, what, url, response) {
        headers = headers || {};
        headers['x-ag-app-secret'] = '3f7C4l1Y2b0S6a7L8c1E7B3Jo3';
        if (operation === "getList") {
            var headers = response.headers();
            if (typeof response.data._metadata !== 'undefined' && response.data._metadata.total !== null) {
                response.totalCount = response.data._metadata.total;
            }
        }
        return data;
    });
    //To cutomize single view results, we added setResponseExtractor.
    //Our API Edit view results single array with following data format data[{}], Its not working with ng-admin format
    //so we returned data like data[0];
    RestangularProvider.setResponseExtractor(function(data, operation, what, url) {
        var extractedData;
        // .. to look for getList operations        
        extractedData = data.data;
        return extractedData;
    });
}]);
ngapp.config(['NgAdminConfigurationProvider', 'user_types', 'CmsConfig', 'ngAdminJWTAuthConfiguratorProvider', function(NgAdminConfigurationProvider, userTypes, CmsConfig, ngAdminJWTAuthConfigurator) {
    var nga = NgAdminConfigurationProvider;
    ngAdminJWTAuthConfigurator.setJWTAuthURL(admin_api_url + 'api/v1/users/login');
    ngAdminJWTAuthConfigurator.setCustomLoginTemplate('views/users_login.html');
    ngAdminJWTAuthConfigurator.setCustomAuthHeader({
        name: 'Authorization',
        template: 'Bearer {{token}}'
    });    
    var admin = nga.application('OFOS Admin')
        .baseApiUrl(admin_api_url + 'api/v1/'); // main API endpoint;
    var customHeaderTemplate = '<div class="navbar-header">' + '<button type="button" class="navbar-toggle" ng-click="isCollapsed = !isCollapsed">' + '<span class="icon-bar"></span>' + '<span class="icon-bar"></span>' + '<span class="icon-bar"></span>' + '</button>' + '<a class="al-logo ng-binding ng-scope" href="#/dashboard" ng-click="appController.displayHome()"><img src="assets/img/logo.png" alt="[Image: OFOS]" title="" width="130px"> </a>' + '<a href="" ng-click="isCollapsed = !isCollapsed" class="collapse-menu-link ion-navicon" ba-sidebar-toggle-menu=""></a>' + '</div>' + '<custom-header></custom-header>';
    admin.header(customHeaderTemplate);
    generateMenu(CmsConfig.menus);
    var entities = {};
    if (angular.isDefined(CmsConfig.dashboard)) {
        dashboard_template = '';
        var collections = [];
        angular.forEach(CmsConfig.dashboard, function(v, collection) {
            var fields = [];
            dashboard_template = dashboard_template + v.addCollection.template;
            if (angular.isDefined(v.addCollection)) {
                angular.forEach(v.addCollection, function(v1, k1) {
                    if (k1 == 'fields') {
                        angular.forEach(v1, function(v2, k2) {
                            var field = nga.field(v2.name, v2.type);
                            if (angular.isDefined(v2.label)) {
                                field.label(v2.label);
                            }
                            if (angular.isDefined(v2.template)) {
                                field.template(v2.template);
                            }
                            fields.push(field);
                        });
                    }
                });
            }
            collections.push(nga.collection(nga.entity(collection))
                    .name(v.addCollection.name)
                    .title(v.addCollection.title)
                    .perPage(v.addCollection.perPage)
                    .fields(fields)
                    .order(v.addCollection.order));
        });
        dashboard_page_template = '<div class="row list-header"><div class="col-lg-12"><div class="page-header">' + '<h4><span>Dashboard</span></h4></div></div></div>' + '<dashboard-summary></dashboard-summary>' + '<div class="row dashboard-content">' + dashboard_template + '</div>';
        var nga_dashboard = nga.dashboard();
        angular.forEach(collections, function(v, k) {
            nga_dashboard.addCollection(v);
        });
        nga_dashboard.template(dashboard_page_template)
        admin.dashboard(nga_dashboard);
    }
    if (angular.isDefined(CmsConfig.tables)) {
        angular.forEach(CmsConfig.tables, function(v, table) {
            var listview = {},
                editionview = {},
                creationview = {},
                showview = {},
                editViewCheck = false,
                editViewFill = "",
                showViewCheck = false,
                showViewFill = "";
            listview.fields = [];
            editionview.fields = [];
            creationview.fields = [];
            listview.filters = [];
            listview.listActions = [];
            listview.batchActions = [];
            listview.actions = [];
            showview.fields = [];
            listview.infinitePagination = "",
                listview.perPage = "";
            entities[table] = nga.entity(table);
            if (angular.isDefined(v.listview)) {
                angular.forEach(v.listview, function(v1, k1) {
                    if (k1 == 'fields') {
                        angular.forEach(v1, function(v2, k2) {
                            var field = nga.field(v2.name, v2.type);
                            if (angular.isDefined(v2.label)) {
                                field.label(v2.label);
                            }
                            if (angular.isDefined(v2.isDetailLink)) {
                                field.isDetailLink(v2.isDetailLink);
                            }
                            if (angular.isDefined(v2.detailLinkRoute)) {
                                field.detailLinkRoute(v2.detailLinkRoute);
                            }
                            if (angular.isDefined(v2.template)) {
                                field.template(v2.template);
                            }
                            if (angular.isDefined(v2.permanentFilters)) {
                                field.permanentFilters(v2.permanentFilters);
                            }
                            if (angular.isDefined(v2.infinitePagination)) {
                                field.infinitePagination(v2.infinitePagination);
                            }
                            if (angular.isDefined(v2.singleApiCall)) {
                                if (angular.isDefined(v2.targetEntity)) {
                                    field.targetEntity(nga.entity(v2.targetEntity));
                                }
                                if (angular.isDefined(v2.targetField)) {
                                    field.targetField(nga.field(v2.targetField));
                                }
                            }
                            if (angular.isDefined(v2.singleApiCall)) {
                                field.singleApiCall(v2.singleApiCall);
                            }
                            if (angular.isDefined(v2.batchActions)) {
                                field.batchActions(v2.batchActions);
                            }
                            if (angular.isDefined(v2.stripTags)) {
                                field.stripTags(v2.stripTags);
                            }
                            if (angular.isDefined(v2.exportOptions)) {
                                field.exportOptions(v2.exportOptions);
                            }
                            if (angular.isDefined(v2.remoteComplete)) {
                                field.remoteComplete(true, {
                                    searchQuery: function(search) {
                                        return {
                                            q: search,
                                            autocomplete: true
                                        };
                                    }
                                });
                            }
                            if (angular.isDefined(v2.map)) {
                                angular.forEach(v2.map, function(v2m, k2m) {
                                    field.map(eval(v2m));
                                });
                            }
                            listview.fields.push(field);
                        });
                    }
                    if (k1 == 'filters') {
                        angular.forEach(v1, function(v3, k3) {
                            var field;
                            if (v3.type === "template") {
                                field = nga.field(v3.name);
                            } else {
                                field = nga.field(v3.name, v3.type);
                            }
                            if (angular.isDefined(v3.label)) {  
                                field.label(v3.label);
                            }
                            if (angular.isDefined(v3.choices)) {
                                field.choices(v3.choices);
                            }
                            if (angular.isDefined(v3.pinned)) {
                                field.pinned(v3.pinned);
                            }
                            if (angular.isDefined(v3.template) && v3.template !== "") {
                                field.template(v3.template);
                            }
                            if (angular.isDefined(v3.targetEntity)) {
                                field.targetEntity(nga.entity(v3.targetEntity));
                            }
                            if (angular.isDefined(v3.targetField)) {
                                field.targetField(nga.field(v3.targetField));
                            }
                            if (angular.isDefined(v3.permanentFilters)) {
                                field.permanentFilters(v3.permanentFilters);
                            }
                            if (angular.isDefined(v3.remoteComplete)) {
                                field.remoteComplete(true, {
                                    searchQuery: function(search) {
                                        var remoteComplete = {
                                            q: search,
                                            autocomplete: true
                                        };
                                        if (angular.isDefined(v3.remoteCompleteAdditionalParams)) {
                                            angular.forEach(v3.remoteCompleteAdditionalParams, function(value, key) {
                                                remoteComplete[key] = value;
                                            });
                                        }
                                        return remoteComplete;
                                    }
                                });
                            }
                            if (angular.isDefined(v3.map)) {
                                angular.forEach(field.map, function(v2m, k2m) {
                                    field.map(eval(v2m));
                                });
                            }
                            listview.filters.push(field);
                        });
                    }
                    if (k1 == 'listActions') {
                        if (Array.isArray(v1) === true) {
                            angular.forEach(v1, function(v3, k3) {
                                if (v3 === "edit") {
                                    editViewCheck = true;
                                }
                                if (v3 === "show") {
                                    showViewCheck = true;
                                }
                                listview.listActions.push(v3);
                            });
                        } else if (v1 !== "") {
                            listview.listActions.push(v1);
                        }
                    }
                    if (k1 == 'batchActions') {
                        if (Array.isArray(v1) === true) {
                            angular.forEach(v1, function(v3, k3) {
                                listview.batchActions.push(v3);
                            });
                        } else if (v1 !== "") {
                            listview.batchActions.push(v1);
                        }
                    }
                    if (k1 == 'actions') {
                        if (Array.isArray(v1) === true) {
                            angular.forEach(v1, function(v3, k3) {
                                listview.actions.push(v3);
                            });
                        } else if (v1 !== "") {
                            listview.actions.push(v1);
                        }
                    }
                    if (k1 == 'infinitePagination') {
                        entities[table].listView()
                            .infinitePagination(v1);
                    }
                    if (k1 == 'perPage') {
                        entities[table].listView()
                            .perPage(v1);
                    }
                    if (k1 == 'sortDir') {
                        entities[table].listView()
                            .sortDir(v1);
                    }
                });
                if (angular.isDefined(v.creationview)) {
                    editViewFill = generateFields(v.creationview.fields);
                    creationview.fields.push(editViewFill);
                    if (editViewCheck === true && !angular.isDefined(v.editionview)) {
                        editionview.fields.push(editViewFill);
                    } else if (angular.isDefined(v.editionview)) {
                        editionview.fields.push(generateFields(v.editionview.fields));
                    }
                }
            }
             if (angular.isDefined(v.editionview)) {
                angular.forEach(v.editionview, function(v1, k1) {
                    if (k1 == 'actions') {
                        if (Array.isArray(v1) === true) {
                            editionview.actions = [];
                            angular.forEach(v1, function(v3, k3) {
                                editionview.actions.push(v3);
                            });
                        } else if (v1 !== "") {
                            editionview.actions.push(v1);
                        }
                    }
                });
             }
            if (angular.isDefined(v.showview)) {
                showview.fields.push(generateFields(v.showview.fields));
            } else if (showViewCheck === true) {
                showview.fields.push(listview.fields);
            }
            if (angular.isDefined(v.showview)) {
                angular.forEach(v.showview, function(v1, k1) {
                    if (k1 == 'actions') {
                        if (Array.isArray(v1) === true) {
                            showview.actions = [];
                            angular.forEach(v1, function(v3, k3) {
                                showview.actions.push(v3);
                            });
                        } else if (v1 !== "") {
                            showview.actions.push(v1);
                        }
                    }
                });
             }
            admin.addEntity(entities[table]);
            entities[table].listView()
                .title(v.listview.title)
                .fields(listview.fields)
                .listActions(listview.listActions)
                .batchActions(listview.batchActions)
                .actions(listview.actions)
                .filters(listview.filters);
            if (angular.isDefined(v.creationview)) {
                entities[table].creationView()
                    .title(v.creationview.title)
                    .fields(creationview.fields)
                    .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function(progression, notification, $state, entry, entity) {
                        progression.done();
                        notification.log(entity.name().charAt(0).toUpperCase() + entity.name().slice(1) + ' added successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                        $state.go($state.get('list'), {
                            entity: entity.name()
                        });
                        return false;
                    }])
                     .onSubmitError(['error', 'form', 'progression', 'notification', 'entity', function(error, form, progression, notification, entity) {
                        angular.forEach(error.data.errors, function(value, key) {
                            if (this[key]) {
                                this[key].$valid = false;
                            }
                        }, form);
                        progression.done();
                        if(entity.name() === 'users')
                        {
                        if (angular.isDefined(error.data.error.fields) && angular.isDefined(error.data.error.fields.unique) && error.data.error.fields.unique.length !== 0) {
                                notification.log(' Please choose different ' + ' ' + error.data.error.fields.unique.join(), {
                                addnCls: 'humane-flatty-error'
                                });
                            }else {
                                notification.log(error.data.message, {
                                addnCls: 'humane-flatty-error'
                                });
                            }
                        }
                        if (entity.name() === 'countries') {
                            notification.log(error.data.error.message, {
                                addnCls: 'humane-flatty-error'
                            });
                        }
                        return false;
                    }]);
                if (angular.isDefined(v.creationview.prepare)) {
                    entities[table].creationView()
                        .prepare(['entry', function(entry) {
                            angular.forEach(v.creationview.prepare, function(value, key) {
                                entry.values[key] = value;
                            });
                            return entry;
                        }]);
                }
            }
            if (angular.isDefined(v.editionview) || editViewCheck === true) {
                var editTitle;
                if (editViewCheck === true && angular.isDefined(v.editionview)) {
                    editTitle = v.editionview.title;
                } else if(angular.isDefined(v.creationview)) {
                    editTitle = v.creationview.title;
                } else {
                    editTitle = 'Edit';
                }
                entities[table].editionView()
                    .title(editTitle)
                    .fields(editionview.fields)
                    .actions(editionview.actions)
                    .onSubmitSuccess(['progression', 'notification', '$location', '$state', 'entry', 'entity', function(progression, notification, $location, $state, entry, entity) {
                        progression.done();
                        if (entity.name().indexOf("_") != -1 ) {
                            var entity_name = toUpperCase(entity.name());
                            var entity_rep = entity_name.replace(/_/g , " ");
                            notification.log(entity_rep +' ' + 'updated successfully', {addnCls: 'humane-flatty-success'});
                        } else {
                            notification.log(entity.name().charAt(0).toUpperCase() + entity.name().slice(1) + ' updated successfully', {addnCls: 'humane-flatty-success'});
                        }
                        if (entity.name() === 'settings') {
                            var current_id = entry.values.setting_category_id;
                            $location.path('/setting_categories/show/' + current_id);
                        } else {
                            $state.go($state.get('list'), {
                                entity: entity.name()
                            });
                        }
                        return false;
                    }])
                    .onSubmitError(['error', 'form', 'progression', 'notification', 'entity', function(error, form, progression, notification, entity) {
                        angular.forEach(error.data.errors, function(value, key) {
                            if (this[key]) {
                                this[key].$valid = false;
                            }
                        }, form);
                        progression.done();
                        if (entity.name() === 'countries') {
                            notification.log(error.data.error.message, {
                                addnCls: 'humane-flatty-error'
                            });
                        } else { 
                            notification.log(error.data.error.message, {
                            addnCls: 'humane-flatty-error'
                            });
                        }
                        return false;
                    }]);
            }
            if (angular.isDefined(v.showview) || showViewCheck === true) {
                if (showViewCheck === true) {
                    entities[table].showView()
                        .title(v.listview.title);
                } else if (angular.isDefined(v.showview) && angular.isDefined(v.showview.title)) {
                    entities[table].showView()
                        .title(v.showview.title);
                }
                entities[table].showView()
                    .fields(showview.fields)
                    .actions(showview.actions);
            }
        });
    }

    function generateMenu(menus) {
        angular.forEach(menus, function(menu_value, menu_keys) {
            var menus;
            if (angular.isDefined(menu_value.link)) {
                menusIndex = nga.menu();
                menusIndex.link(menu_value.link);
            } else if (angular.isDefined(menu_value.child_sub_menu)) {
                menusIndex = nga.menu();
            } else {
                menusIndex = nga.menu(nga.entity(menu_keys));
            }
            if (angular.isDefined(menu_value.title)) {
                menusIndex.title(menu_value.title);
            }
            if (angular.isDefined(menu_value.icon_template)) {
                menusIndex.icon(menu_value.icon_template);
            }
            if (angular.isDefined(menu_value.child_sub_menu)) {
                angular.forEach(menu_value.child_sub_menu, function(val, key) {
                    var child = nga.menu(nga.entity(key));
                    if (angular.isDefined(val.title)) {
                        child.title(val.title);
                    }
                    if (angular.isDefined(val.icon_template)) {
                        child.icon(val.icon_template);
                    }
                    if (angular.isDefined(val.link)) {
                        child.link(val.link);
                    }
                    menusIndex.addChild(child);
                });
            }
            admin.menu()
                .addChild(menusIndex);
        });
    }

    function generateFields(fields) {
        var generatedFields = [];
        angular.forEach(fields, function(targetFieldValue, targetFieldKey) {
            var field = nga.field(targetFieldValue.name, targetFieldValue.type),
                fieldAdd = true;
            if (angular.isDefined(targetFieldValue.label)) {
                field.label(targetFieldValue.label);
            }
            if (angular.isDefined(targetFieldValue.stripTags)) {
                field.stripTags(targetFieldValue.stripTags);
            }
            if (angular.isDefined(targetFieldValue.choices)) {
                field.choices(targetFieldValue.choices);
            }
            if (angular.isDefined(targetFieldValue.editable)) {
                field.editable(targetFieldValue.editable);
            }
            if (angular.isDefined(targetFieldValue.attributes)) {
                field.attributes(targetFieldValue.attributes);
            }
            if (angular.isDefined(targetFieldValue.perPage)) {
                field.perPage(targetFieldValue.perPage);
            }
            if (angular.isDefined(targetFieldValue.listActions)) {
                field.listActions(targetFieldValue.listActions);
            }
            if (angular.isDefined(targetFieldValue.targetEntity)) {
                field.targetEntity(nga.entity(targetFieldValue.targetEntity));
            }
            if (angular.isDefined(targetFieldValue.targetReferenceField)) {
                field.targetReferenceField(targetFieldValue.targetReferenceField);
            }
            if (angular.isDefined(targetFieldValue.targetField)) {
                field.targetField(nga.field(targetFieldValue.targetField));
            }
            if (angular.isDefined(targetFieldValue.map)) {
                angular.forEach(targetFieldValue.map, function(v2m, k2m) {
                    field.map(eval(v2m));
                });
            }
            if (angular.isDefined(targetFieldValue.format)) {
                field.format(targetFieldValue.format);
            }
            if (angular.isDefined(targetFieldValue.template)) {
                field.template(targetFieldValue.template);
            }
            if (angular.isDefined(targetFieldValue.permanentFilters)) {
                field.permanentFilters(targetFieldValue.permanentFilters);
            }
            if (angular.isDefined(targetFieldValue.defaultValue)) {
                field.defaultValue(targetFieldValue.defaultValue);
            }
            if (angular.isDefined(targetFieldValue.validation)) {
                field.validation(eval(targetFieldValue.validation));
            }
            if (angular.isDefined(targetFieldValue.remoteComplete)) {
                field.remoteComplete(true, {
                    searchQuery: function(search) {
                        return {
                            q: search,
                            autocomplete: true
                        };
                    }
                });
            }
            if (angular.isDefined(targetFieldValue.uploadInformation) && angular.isDefined(targetFieldValue.uploadInformation.url) && angular.isDefined(targetFieldValue.uploadInformation.apifilename)) {
                field.uploadInformation({
                    'url': admin_api_url + targetFieldValue.uploadInformation.url,
                    'apifilename': targetFieldValue.uploadInformation.apifilename,
                    'multiple': targetFieldValue.uploadInformation.multiple
                });
            }
            if (targetFieldValue.type === "file" && (!angular.isDefined(targetFieldValue.uploadInformation) || !angular.isDefined(targetFieldValue.uploadInformation.url) || !angular.isDefined(targetFieldValue.uploadInformation.apifilename))) {
                fieldAdd = false;
            }
            if (angular.isDefined(targetFieldValue.targetFields) && (targetFieldValue.type === "embedded_list" || targetFieldValue.type === "referenced_list")) {
                var embField = generateFields(targetFieldValue.targetFields);
                field.targetFields(embField);
            }
            if (fieldAdd === true) {
                generatedFields.push(field);
            }
        });
        return generatedFields;
    }
    nga.configure(admin);
    function getUsers(userIds) {
        return {
            "user_id[]": userIds
        };
    }
}]);
ngapp.run(['$rootScope', '$location', '$window', '$state', 'user_types', function($rootScope, $location, $window, $state, userTypes) {
        $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
            var url = toState.name;
            var exception_arr = ['login', 'logout'];
            if (($cookies.get("auth") === null || $cookies.get("auth") === undefined) && exception_arr.indexOf(url) === -1) {
                $location.path('/login');
            }
            if (exception_arr.indexOf(url) === 0 && $cookies.get("auth") !== null && $cookies.get("auth") !== undefined) {
                $location.path('/dashboard');
            }
            if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined) {
                var auth = JSON.parse($cookies.get("auth"));
                if (auth.role_id === userTypes.user) {
                    $location.path('/logout');
                }
            }
        });
  }])
    .filter('spaceless', function() {
        return function(input) {
            if (input) {
                return input.replace(/\s+/g, '-');
            }
        };
    })
    .factory('CustomFactory', function($rootScope, md5, $http) {
        var data = {};
        var d = new Date();
        return {
            /* [ Generate Image ] */
            generateimage: function(value) {
                var hash = md5.createHash(value.class + value.foreign_id + 'png' + value.thumb_type);
                return value.path + value.thumb_type + '/' + value.class + '/' + value.foreign_id + '.' + hash + '.png?' + d.getTime();
            },
            /* [ Create Image ] */
            createimage: function(key, value) {
                var hash = md5.createHash(value.class + value.foreign_id + 'png' + value.thumb_type);
                data[key] = value.path + value.thumb_type + '/' + value.class + '/' + value.foreign_id + '.' + hash + '.png?' + d.getTime();
            },
        };
    });