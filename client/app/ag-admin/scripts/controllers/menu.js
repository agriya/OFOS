'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:MenuCtrl
 * @description
 * # MenuCtrl
MenuCtrl * Controller of the ofosApp
 */
angular.module('ofos')
    .controller('MenuCtrl', function($scope, restaurant, restaurants, restaurantCategories, restaurantMenu, restaurantMenuUpdate, restaurantMenuAdd, categoryAdd, categoryUpdate, $window, progression, notification, $stateParams, $cookies, $state, $http, md5, restaurantCuisines, restaurantMenuPositionUpdate, restaurantCategoryPositionUpdate, user_types) {
        var admin_api_url = '/';
        var restaurant_list = [];
        $scope.loader = true;
        $scope.restaurants = [];
        $scope.refreshAddresses = function(address) {
            var param = {};
            $scope.auth = auth;
            $scope.user_types = user_types;
            if (auth.role_id !== user_types.restaurant) {
                if (auth.role_id == user_types.supervisor) {
                    if (auth.restaurant_supervisor.restaurant_branch_id === null) {
                        $scope.restaurant_id = auth.restaurant_supervisor.restaurant_id;
                    } else {
                      $scope.restaurant_id =  auth.restaurant_supervisor.restaurant_branch_id
                    }
                    $scope.load_category();
                }
                param.filter = {"fields":{"id":true,"name":true}};
                if(address != ''){
                   param.q = address;
                }
                return $http.get(admin_api_url + 'api/v1/restaurants', {
                        params: param
                    })
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
                param.filter = {"where":{"user_id":auth.id},"limit":"all","skip":0}
                restaurant.get(param, function(response) {
                    $scope.restaurants = response.data;
                    if (auth.role_id === user_types.restaurant) {
                        $scope.restaurants.push({
                            'id': auth.restaurant.id,
                            'name': 'Main branch'
                        });
                    }
                });
            }
        };
        $scope.getRestaurant = function(item) {
            $scope.restaurant_id = item.id;
            $scope.load_category();
        };
        $scope.load_category = function() {
            $scope.loader = false;
            var params = {};
            params.filter = {"include":{"0":"restaurant","1":"restaurant_addon.restaurant_addon_item.restaurant_menu_addon_price","restaurant_addon":{"where":{"restaurant_id":$scope.restaurant_id}}},"where":{"restaurant_id":$scope.restaurant_id},"skip":0,"limit":"all","order":"display_order asc"};
            restaurantCategories.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    $scope.restaurant_categories = response.data;
                }
            });
            var params = {};
            params.filter = {"include":{"0":"cuisine"},"where":{"restaurant_id":$scope.restaurant_id},"skip":0,"limit":"all","order":"id asc"};
            restaurantCuisines.get(params, function(response) {
                   var cuisines_lists = [];
                if (angular.isDefined(response.data)) {
                    angular.forEach(response.data, function(val, key) {
                       cuisines_lists.push(val.cuisine);
                    });
                    $scope.cuisines_lists = cuisines_lists;
                }
            });
            var params = {};
            params.filter = {"where":{"restaurant_id":$scope.restaurant_id},"include":{"0":"attachment","1":"cuisine","2":"restaurant","3":"restaurant_categories","4":"restaurant_menu_price","5":"restaurant_addon.restaurant_addon_item.restaurant_menu_addon_price","restaurant_addon":{"where":{"restaurant_id":$scope.restaurant_id}},"6":"restaurant_menu_addon_price"}, "skip":0,"limit":"all","order":"display_order asc"};
            restaurantMenu.get(params, function(menus) {
                if (angular.isDefined(menus.data)) {
                    var categories = [];
                    $scope.loader = true;
                    angular.forEach($scope.restaurant_categories, function(val, key) {
                        var isExists = categories.filter(function(v, i) {
                            return v.category_id === val.id;
                        });
                        if (!isExists.length) {
                            categories.push({
                                'category_name': val.name,
                                'category_id': val.id,
                                'category_position': val.position,
                                'menus': []
                            });
                        }
                    });
                    angular.forEach(menus.data, function(value, key) {
                        delete params.sort;
                        value.cuisine_id = parseInt(value.cuisine_id);
                        if (angular.isDefined(value.attachment) && value.attachment !== null) {
                            var hash = md5.createHash('RestaurantMenu' + value.attachment.id + 'png' + 'small_thumb');
                            value.image_name = '../images/small_thumb/RestaurantMenu/' + value.attachment.id + '.' + hash + '.png';
                        } else {
                            value.image_name = '../images/no-image-menu-64x64.png';
                        }
                        if (angular.isDefined(value.restaurant_addon) && value.restaurant_addon !== null) {
                            angular.forEach(value.restaurant_addon, function(addons, addon_key) {
                                if (angular.isDefined(addons.restaurant_addon_item) && addons.restaurant_addon_item !== null) {
                                    angular.forEach(addons.restaurant_addon_item, function(addon_items, addon_items_key) {
                                        if (angular.isDefined(addon_items.restaurant_menu_addon_price) && addons.restaurant_menu_addon_price !== null) {
                                            if(addon_items.restaurant_menu_addon_price.length > 0) {
                                                angular.forEach(addon_items.restaurant_menu_addon_price, function(addon_menu_price, menu_price_key) {
                                                    if (parseInt(addon_menu_price.restaurant_menu_id) !== parseInt(value.id)) {
                                                        value.restaurant_addon[addon_key].restaurant_addon_item[addon_items_key].restaurant_menu_addon_price.splice(menu_price_key);
                                                    }
                                                });
                                            }
                                        }
                                    });
                                }
                            });
                        }
                        if (angular.isDefined(value.restaurant_menu_price) && value.restaurant_menu_price !== null) {
                            angular.forEach(value.restaurant_menu_price, function(v, k) {
                                value.price_type_id = v.price_type_id;
                                if (v.price_type_id == 1) {
                                    value.fixed_price = v.price;
                                }
                                if (v.price_type_id == 2) {
                                    if (v.price_type_name === 'Small') {
                                        value.small_price = v.price;
                                        value.small_price_type_name = v.price_type_name;
                                    } else if (v.price_type_name === 'Medium') {
                                        value.medium_price = v.price;
                                        value.medium_price_type_name = v.price_type_name;
                                    } else if (v.price_type_name === 'Large') {
                                        value.large_price = v.price;
                                        value.large_price_type_name = v.price_type_name;
                                    }
                                }
                            });
                        }
                        if (categories.length > 0) {
                            angular.forEach(categories, function(v, k) {
                                if (v.category_id === value.restaurant_categories.id) {
                                    categories[k].menus.push(value);
                                }
                            });
                        } else {
                            categories.push({
                                'category_name': value.restaurant_categories.name,
                                'category_id': value.restaurant_categories.id,
                                'category_position': value.restaurant_categories.position,
                                'menus': [value]
                            });
                        }
                    });
                    $scope.categories = categories;
                }
            });
        };
        $scope.isCheckedSections = function(sections) {
            var items = ['Small', 'Medium', 'Large'];
            for (var i = 0; i < items.length; i++) {
                if (sections === items[i]) {
                    return true;
                }
            }
            return false;
        };
        //Menu sorting
        $scope.menuSortableOptions = {
            disabled: true,
            placeholder: "app",
            connectWith: ".apps-container",
            stop: function(e, ui) {
                angular.forEach($scope.categories, function(cat, key) {
                    cat.position = key + 1;
                    angular.forEach(cat.menus, function(value, k1) {
                        value.restaurant_category_id = cat.category_id;
                        value.restaurant_categories.id = cat.category_id;
                        value.position = k1 + 1;
                    });
                });
                var params = {};
                params.restaurant_id = $scope.restaurant_id;
                params.id = $scope.restaurant_id;
                params.categories = $scope.categories;
                restaurantMenuPositionUpdate.updatePosition(params, function(response) {
                    if (response.error.code == 0) {
                        notification.log('Menus has been rearranged', {
                            addnCls: 'humane-flatty-success'
                        });
                    }
                });
            }
        };
        $scope.menuSortableReorder = function(is_disabled) {
            if (is_disabled) {
                $(".block-reorder")
                    .addClass('reorder');
                return false;
            } else {
                $(".block-reorder")
                    .removeClass('reorder');
                return true;
            }
        };
        //Category sorting
        $scope.categorySortableOptions = {
            disabled: true,
            placeholder: "category",
            connectWith: ".category-container",
            stop: function(e, ui) {
                angular.forEach($scope.categories, function(cat, key) {
                    cat.position = key + 1;
                    angular.forEach(cat.menus, function(value, k1) {
                        value.restaurant_category_id = cat.category_id;
                        value.restaurant_categories.id = cat.category_id;
                    });
                });
                var params = {};
                params.restaurant_id = $scope.restaurant_id;
                params.id = $scope.restaurant_id;
                params.categories = $scope.categories;
                restaurantCategoryPositionUpdate.updatePosition(params, function(response) {
                    if (response.error.code == 0) {
                        notification.log('Category has been rearranged', {
                            addnCls: 'humane-flatty-success'
                        });
                    }
                });
            }
        };
        $scope.categorySortableReorder = function(is_disabled) {
            if (is_disabled) {
                $(".block-reorder1")
                    .addClass('reorder1');
                return false;
            } else {
                $(".block-reorder1")
                    .removeClass('reorder1');
                return true;
            }
        };
        $scope.uploadFile = function(files) {
            var menu_id = $(this)[0]['value']['id'];
            if (files.length > 0) {
                var fd = new FormData();
                fd.append("file", files[0]);
                fd.append("menu_id", menu_id);
                $http.post(admin_api_url + 'api/v1/attachments', fd, {
                        withCredentials: true,
                        headers: {
                            'Content-Type': undefined
                        },
                        transformRequest: angular.identity
                    })
                    .success(function(response, status, headers, config) {
                        if (response.id !== '') {
                            if (menu_id !== "" && menu_id !== undefined) {
                                angular.forEach($scope.categories, function(cat, key) {
                                    angular.forEach(cat.menus, function(value, key) {
                                        if (value.id === menu_id) {
                                            delete value.attachment;
                                            delete value.image_name;
                                            value.image = {};
                                            value.image['attachment'] = response.id;
                                            $scope.editMenu = value;
                                            restaurantMenuUpdate.update($scope.editMenu, function(response) {
                                                if (response.error.code == 0) {
                                                    notification.log('Menu image updated successfully', {
                                                        addnCls: 'humane-flatty-success'
                                                    });
                                                    $scope.load_category();
                                                }
                                            });
                                        }
                                    });
                                });
                            }
                        }
                    })
                    .error();
            }
        };
        $scope.addNewCategory = function() {
            var params = {};
            params.name = $scope.new_category_name;
            params.restaurant_id = $scope.restaurant_id;
            params.is_active = 'true';
            if ($scope.categories === undefined) {
                params.position = 1;
            } else {
                params.position = $scope.categories.length + 1;
            }
            categoryAdd.add(params, function(response) {
                if (response.error.code === 0) {
                    $scope.restaurant_id = $scope.restaurant_id;
                    notification.log('Category added successfully', {
                        addnCls: 'humane-flatty-success'
                    });
                    $scope.menuNew = {};
                    $scope.menuNew['category_id'] = response.id;
                    $scope.menuNew['category_name'] = response.name;
                    $scope.menuNew['menus'] = [];
                    if ($scope.categories === undefined) {
                        $scope.categories = [];
                        $scope.categories[0] = $scope.menuNew;
                    } else {
                        $scope.categories.splice($scope.categories.length, 0, $scope.menuNew);
                    }
                }
            });
        };
        $scope.updateCategory = function(name, category_id) {
            var params = {};
            params.id = category_id;
            params.name = name;
            params.restaurant_id = $scope.restaurant_id;
            params.is_active = 'true';
            categoryUpdate.update(params, function(response) {
                if (response.error.code === 0) {
                    $scope.restaurant_id = $scope.restaurant_id;
                    notification.log('Category updated successfully', {
                        addnCls: 'humane-flatty-success'
                    });
                }
            });
        };
        $scope.addNewMenu = function(category_name, category_id, category_index) {
            $scope.menuNew = {};
            $scope.menuNew['name'] = '';
            $scope.menuNew['is_popular'] = false;
            $scope.menuNew['is_spicy'] = false;
            $scope.menuNew['is_addon'] = false;
            $scope.menuNew['is_active'] = false;
            $scope.menuNew['cuisine_id'] = null;
            $scope.menuNew['description'] = '';
            $scope.menuNew['position'] = $scope.categories[category_index].menus.length + 1;
            $scope.menuNew['image_name'] = '../images/no_image_available.png';
            $scope.menuNew['restaurant_menu_price'] = {};
            $scope.menuNew['restaurant_menu_price']['price_type_id'] = 1;
            $scope.menuNew['restaurant_menu_price']['price'] = 0;
            $scope.menuNew['restaurant_category_id'] = category_id;
            $scope.menuNew['restaurant_categories'] = {};
            $scope.menuNew['restaurant_categories']['name'] = category_name;
            $scope.categories[category_index].menus.push($scope.menuNew);
        }
        $scope.editMenus = function(menu, key) {                      
            $scope.menu = {};
            $scope.menu['restaurant_id'] = $scope.restaurant_id;
            $scope.menu['id'] = menu['id'];
            $scope.menu['name'] = menu['name'];
            $scope.menu['restaurant_category_id'] = menu['restaurant_category_id'];
            $scope.menu['menu_type_id'] = menu['menu_type_id'];
            $scope.menu['cuisine_id'] = menu['cuisine_id'];            
            $scope.menu['is_popular'] = menu['is_popular'];
            $scope.menu['is_spicy'] = menu['is_spicy'];
            $scope.menu['is_addon'] = menu['is_addon'];
            $scope.menu['is_active'] = menu['is_active'];
            $scope.menu['position'] = menu['position'];
            if ($scope.menu['name'] !== "") {
                if ($scope.menu['id'] !== "" && $scope.menu['id'] !== undefined) {
                    var is_new_small_size = true;
                    var is_new_medium_size = true;
                    var is_new_large_size = true;
                    angular.forEach($scope.categories, function(cat, key) {
                        angular.forEach(cat.menus, function(value, key) {
                            if (value.id === menu['id']) {
                                angular.forEach(value.restaurant_menu_price, function(v, k) {
                                    if (v.price_type_id !== value.price_type_id) {
                                        delete value.restaurant_menu_price;
                                        value.restaurant_menu_price = [];
                                        if (value.price_type_id == 1) {
                                            var fixed = {};
                                            fixed['price'] = menu.fixed_price;
                                            fixed['price_type_id'] = 1;
                                            fixed['price_type_name'] = 'Fixed';
                                            fixed['restaurant_menu_id'] = $scope.menu['id'];
                                            value.restaurant_menu_price.push(fixed);
                                        }
                                        if (value.price_type_id == 2) {
                                            if (menu.small_price_type_name === true && (menu.small_price !== '' || menu.small_price !== 0)) {
                                                var small = {};
                                                small['price'] = menu.small_price;
                                                small['price_type_id'] = 2;
                                                small['price_type_name'] = 'Small';
                                                small['restaurant_menu_id'] = $scope.menu['id'];
                                                value.restaurant_menu_price.push(small);
                                                is_new_small_size = false;
                                            }
                                            if (menu.medium_price_type_name === true && (menu.medium_price !== '' || menu.medium_price !== 0)) {
                                                var medium = {};
                                                medium['price'] = menu.medium_price;
                                                medium['price_type_id'] = 2;
                                                medium['price_type_name'] = 'Medium';
                                                medium['restaurant_menu_id'] = $scope.menu['id'];
                                                value.restaurant_menu_price.push(medium);
                                                is_new_medium_size = false;
                                            }
                                            if (menu.large_price_type_name === true && (menu.large_price !== '' || menu.large_price !== 0)) {
                                                var large = {};
                                                large['price'] = menu.large_price;
                                                large['price_type_id'] = 2;
                                                large['price_type_name'] = 'Large';
                                                large['restaurant_menu_id'] = $scope.menu['id'];
                                                value.restaurant_menu_price.push(large);
                                                is_new_large_size = false;
                                            }
                                        }
                                    } else if (v.price_type_id == 1) {
                                        v.price = menu.fixed_price;
                                    } else if (v.price_type_id == 2) {
                                        if (v.price_type_name === 'Small') {
                                            if (menu.small_price_type_name === false) {
                                                value.restaurant_menu_price.splice(k, 1);
                                            } else {
                                                v.price = menu.small_price;
                                                is_new_small_size = false;
                                            }
                                        } else if (v.price_type_name === 'Medium') {
                                            if (menu.medium_price_type_name === false) {
                                                value.restaurant_menu_price.splice(k, 1);
                                            } else {
                                                v.price = menu.medium_price;
                                                is_new_medium_size = false;
                                            }
                                        } else if (v.price_type_name === 'Large') {
                                            if (menu.large_price_type_name === false) {
                                                value.restaurant_menu_price.splice(k, 1);
                                            } else {
                                                v.price = menu.large_price;
                                                is_new_large_size = false;
                                            }
                                        }
                                    }
                                });
                                if (parseInt(value.price_type_id) == 2) {
                                    if (menu.small_price_type_name === true && (menu.small_price !== '' || menu.small_price !== 0) && is_new_small_size === true) {
                                        var small = {};
                                        small['price'] = menu.small_price;
                                        small['price_type_id'] = 2;
                                        small['price_type_name'] = 'Small';
                                        small['restaurant_menu_id'] = $scope.menu['id'];
                                        value.restaurant_menu_price.push(small);
                                    }
                                    if (menu.medium_price_type_name === true && (menu.medium_price !== '' || menu.medium_price !== 0) && is_new_medium_size === true) {
                                        var medium = {};
                                        medium['price'] = menu.medium_price;
                                        medium['price_type_id'] = 2;
                                        medium['price_type_name'] = 'Medium';
                                        medium['restaurant_menu_id'] = $scope.menu['id'];
                                        value.restaurant_menu_price.push(medium);
                                    }
                                    if (menu.large_price_type_name === true && (menu.large_price !== '' || menu.large_price !== 0) && is_new_large_size === true) {
                                        var large = {};
                                        large['price'] = menu.large_price;
                                        large['price_type_id'] = 2;
                                        large['price_type_name'] = 'Large';
                                        large['restaurant_menu_id'] = $scope.menu['id'];
                                        value.restaurant_menu_price.push(large);
                                    }
                                }
                                delete value.cuisine;
                                delete value.image_name;
                                $scope.editMenu = value;
                            }
                        });
                    });                    
                    restaurantMenuUpdate.update($scope.editMenu, function(response) {                       
                        if (response.error.code == 0) {
                            notification.log('Menu has been updated successfully', {
                                addnCls: 'humane-flatty-success'
                            });
                            $scope.load_category();
                        }
                    });
                } else {
                    $scope.menu['cuisine_id'] = null;
                    $scope.menu['menu_type_id'] = 0;
                    $scope.menu['restaurant_menu_price'] = {};
                    $scope.menu['restaurant_menu_price']['price_type_id'] = 1;
                    $scope.menu['restaurant_menu_price']['price'] = 0;
                    restaurantMenuAdd.add($scope.menu, function(response) {
                        if (response.error.code == 0) {
                            notification.log('Menu has been added successfully', {
                                addnCls: 'humane-flatty-success'
                            });
                            $scope.load_category();
                        }
                    });
                }
            }
        };
        $scope.isSizePriceType = function(menu, key, type) {
            angular.forEach($scope.categories, function(value, key) {
                angular.forEach(value.menus, function(v, k) {
                    if (v.id == menu.id) {
                        angular.forEach(v.restaurant_menu_price, function(v1, k1) {
                            if (v1.price_type_name === type) {
                                v.restaurant_menu_price.splice(k1, 1);
                            }
                        });
                        if (type !== true) {
                            $scope.editMenu = v;
                            restaurantMenuUpdate.update($scope.editMenu, function(response) {
                                if (response.error.code == 0) {
                                    notification.log('Menu price updated successfully', {
                                        addnCls: 'humane-flatty-success'
                                    });
                                    $scope.load_category();
                                }
                            });
                        }
                    }
                });
            });
        };
        $scope.deleteMenu = function(category_index, menu_index, menu_id) {
            $scope.categories[category_index].menus.splice(menu_index, 1);
            if (angular.isDefined(menu_id)) {
                var params = {};
                params.restaurant_id = $scope.restaurant_id;
                params.id = menu_id;
                restaurantMenuUpdate.delete(params, function(response) {
                    if (response.error.code == 0) {
                        notification.log('Menu deleted successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                        $scope.load_category();
                    }
                });
            }
        };
        $scope.addMenuPrice = function(menu_id) {
            angular.forEach($scope.categories, function(cat, key) {
                angular.forEach(cat.menus, function(value, key) {
                    if (value.id === menu_id) {
                        $scope.menuPrice = {};
                        $scope.menuPrice['price_type_id'] = 3;
                        $scope.menuPrice['restaurant_menu_id'] = menu_id;
                        $scope.menuPrice['price_type_name'] = "";
                        $scope.menuPrice['price'] = "";
                        value.restaurant_menu_price.push($scope.menuPrice);
                    }
                });
            });
        };
        $scope.editMenuPrice = function(menu_id, price_id) {
            angular.forEach($scope.categories, function(cat, key) {
                angular.forEach(cat.menus, function(value, key) {
                    if (value.id === menu_id) {
                        var new_value = value;
                        angular.forEach(value.restaurant_menu_price, function(v, k) {
                            if (k === 0) {
                                delete new_value.restaurant_menu_price;
                                new_value.restaurant_menu_price = [];
                            }
                            if (parseInt(v.price_type_id) === parseInt(value.price_type_id)) {
                                new_value.restaurant_menu_price.push(v);
                            }
                        });
                        $scope.editMenu = new_value;
                        restaurantMenuUpdate.update($scope.editMenu, function(response) {
                            if (response.error.code == 0) {
                                notification.log('Menu price updated successfully', {
                                    addnCls: 'humane-flatty-success'
                                });
                                $scope.load_category();
                            }
                        });
                    }
                });
            });
        };
        $scope.deleteMenuPrice = function(menu_id, price_id, k) {
            angular.forEach($scope.categories, function(cat, key) {
                angular.forEach(cat.menus, function(value, key) {
                    if (value.id === menu_id) {
                        value.restaurant_menu_price.splice(k, 1);
                        if (angular.isDefined(price_id)) {
                            var params = {};
                            params.restaurant_id = $scope.restaurant_id;
                            params.id = menu_id;
                            params.restaurant_menu_price = value.restaurant_menu_price;
                            restaurantMenuUpdate.update(params, function(response) {
                                if (response.error.code == 0) {
                                    notification.log('Menu price deleted successfully', {
                                        addnCls: 'humane-flatty-success'
                                    });
                                    $scope.load_category();
                                }
                            });
                        }
                    }
                });
            });
        };
        $scope.updateAddonItems = function(menu_id, addon_item_id, menu_price_id) {
            angular.forEach($scope.categories, function(cat, key) {
                angular.forEach(cat.menus, function(value, key) {
                    if (angular.isDefined(value.restaurant_addon) && value.restaurant_addon !== null && value.id === menu_id) {
                        angular.forEach(value.restaurant_addon, function(values, keys) {
                            angular.forEach(values.restaurant_addon_item, function(value1, key1) {
                                if (value1.restaurant_menu_addon_price.length > 0) {
                                    angular.forEach(value1.restaurant_menu_addon_price, function(value2, key2) {
                                        if (value2.id === undefined) {
                                            value2.restaurant_addon_id = values.id;
                                            value2.restaurant_addon_item_id = addon_item_id;
                                            value2.restaurant_menu_id = menu_id;
                                            value2.is_active = true;
                                            if (value2.price) {
                                                value2.is_free = false;
                                            } else {
                                                value2.is_free = true;
                                                value2.price = 0;
                                            }
                                        }
                                    });
                                }
                            });
                        });
                        var params = {};
                        params.restaurant_id = $scope.restaurant_id;
                        params.id = menu_id;
                        params.restaurant_addon = value.restaurant_addon;
                        restaurantMenuUpdate.update(params, function(response) {
                            if (response.error.code == 0) {
                                notification.log('Addon Item price updated successfully', {
                                    addnCls: 'humane-flatty-success'
                                });
                                $scope.load_category();
                            }
                        });
                    }
                });
            });
        };
    });