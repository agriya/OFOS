'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:MainController
 * @description
 * # MainController
 * Controller of the ofosApp
 */
angular.module('ofosApp')
    .controller('MainController', function($rootScope, $window, cities, pages, $cookies, md5, refreshToken, $location, $timeout, cfpLoadingBar, $uibModal, $uibModalStack, $state, anchorSmoothScroll) {
        var vm = this;
        $rootScope.cdate = new Date();
      cfpLoadingBar.start();
        if ($location.absUrl()
            .indexOf('app') !== -1) {
            vm.site_url = $window.location.protocol + '//' + $window.location.host + '/app/ag-admin/#/dashboard';
        } else {
            vm.site_url = $window.location.protocol + '//' + $window.location.host + '/ag-admin/#/dashboard';
        }
        vm.isAuth = false;
        if ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) {
            vm.isAuth = true;
            $rootScope.user = angular.fromJson($cookies.get("auth"));
        }
        var unregisterUpdateParent = $rootScope.$on('updateParent', function(event, args) {
            if (args.isAuth === true) {
                vm.isAuth = true;
            } else {
                vm.isAuth = false;
            }
        });
        if ($window.localStorage.getItem("location") !== null) {
            var location = angular.fromJson($window.localStorage.getItem("location"));
            $rootScope.lat = location.lat;
            $rootScope.lang = location.lang;
            $rootScope.address = location.address;
            $rootScope.location_name = location.location_name;
            $rootScope.city = location.city;
            $rootScope.state = location.state;
            $rootScope.country = location.country;
            $rootScope.zip_code = location.zip_code;
        }
        //jshint unused:false
        var unregisterUseRefreshToken = $rootScope.$on('useRefreshToken', function(event, args) {
            //jshint unused:false
            $rootScope.refresh_token_loading = true;
            var params = {};
            var auth = angular.fromJson($cookies.get("auth"));
            params.token = auth.refresh_token;
            refreshToken.get(params, function(response) {
                if (angular.isDefined(response.access_token)) {
                    $rootScope.refresh_token_loading = false;
                    $cookies.put('token', response.access_token, {
                        path: '/'
                    });
                } else {
                    $cookies.remove('auth');
                    $cookies.remove('token');
                    var redirectto = $location.absUrl()
                        .split('/');
                    redirectto = redirectto[0] + '/users/login';
                    $rootScope.refresh_token_loading = false;
                    $window.location.href = redirectto;
                }
                $timeout(function() {
                    $window.location.reload();
                }, 1000);
            });
        });
        vm.openLoginModal = function($redirect_url, $failed_url) {
            var redirect_url = "";
            var failed_url = "";
            if (angular.isDefined($redirect_url)) {
                redirect_url = $redirect_url;
                failed_url = $failed_url;
            } else {
                redirect_url = $location.url();
            }
            var current_state = $state.current.name;
            var exceptional_state = ['users_login', 'users_register'];
            if (exceptional_state.indexOf(current_state) === -1) {
                $cookies.put('redirect_url', redirect_url, {
                    path: '/'
                });
                $cookies.put('failed_url', failed_url, {
                    path: '/'
                });
                $state.go('users_login', {
                    param: ''
                }, {
                    notify: false
                });
                vm.modalInstance = $uibModal.open({
                    templateUrl: 'scripts/plugins/Order/Order/views/default/login_modal.html',
                    backdrop: 'static'
                });
            } else {
                $location.path('/users/login');
            }
        };
        vm.cancel = function() {
            var redirect_url = $cookies.get("redirect_url");
            var failed_url = $cookies.get("failed_url");
            if (failed_url === '') {
                $location.path(redirect_url);
            } else {
                $location.path(failed_url);
            }
            $uibModalStack.dismissAll();
        };
        vm.switch_tab = function(tab) {
            if (tab === 'login') {
                $state.go('users_login', {
                    param: ''
                }, {
                    notify: false
                });
            } else {
                $state.go('users_register', {
                    param: ''
                }, {
                    notify: false
                });
            }
        };
        vm.homePageScroll = function(eID) {
            var current_state = $state.current.name;
            if (current_state === 'home') {
                anchorSmoothScroll.scrollTo(eID);
            } else {
                $rootScope.scroll_position = eID;
                $location.path('/home');
            }
        };
        $rootScope.$on("$destroy", function() {
            unregisterUseRefreshToken();
            unregisterUpdateParent();
        });
        cfpLoadingBar.complete();
        $rootScope.cart_count=0;
       $rootScope.updateCardCount = function(action, value) {
        if(action === "add"){
            $rootScope.cart_count = value;      
        }else if(action === "delete"){
            $rootScope.cart_count = $rootScope.cart_count - 1;
        }else if(action === "cart_change"){
            $rootScope.cart_count = value;
        } 
    };
        vm.cartCookie = "";
        vm.index = function(){
            if ($cookies.getObject('cartCookie') !== null && angular.isDefined($cookies.getObject('cartCookie'))) {
                var cookiee = $cookies.getObject('cartCookie');
                vm.setCartCookie("create", cookiee.id, cookiee.hash);
            }    
        };
        vm.setCartCookie= function(action, res_id, hash){
            if(action === "create"){
                if(hash === ""){
                hash = md5.createHash(new Date().getTime().toString()+""+res_id);
                }
                vm.cartCookie = {'hash': hash,'id': res_id};
                $cookies.putObject('cartCookie',  vm.cartCookie);
                return vm.cartCookie;
            }else if(action === "remove"){
                $cookies.remove('cartCookie'); 
                vm.cartCookie = "";
                $rootScope.cart_count=0;
            }
        };
        $rootScope.getCartCookie=function(res_id){
            var response = false; 
            if(vm.cartCookie === ""){
               
                if(res_id !== ""){
                    response = vm.setCartCookie("create", res_id, "");
                }
            }else if(angular.isDefined(vm.cartCookie.id)){
                if(parseInt(vm.cartCookie.id) === parseInt(res_id)){
                    response = vm.cartCookie;
                }else{
                    if(res_id === ""){
                        response = vm.cartCookie;
                    } else {
                        response = vm.setCartCookie("create", res_id, "");
                    }
                }
            }
            return response;
        };
        $rootScope.removeCartCookie = function(){
            vm.setCartCookie("remove");
        };

vm.index();
    });