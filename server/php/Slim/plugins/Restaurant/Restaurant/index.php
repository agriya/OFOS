<?php
/**
 * API Endpoints
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
/**
 * GET RestaurantsGet
 * Summary: Get  restaurants.
 * Notes: Filter restaurants.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurants', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurants = Models\Restaurant::Filter($queryParams);
        if (!empty($queryParams['latitude']) && !empty($queryParams['longitude'])) {
            $lat = $latitude = $queryParams['latitude'];
            $lng = $longitude = $queryParams['longitude'];
            $radius = isset($queryParams['radius']) ? $queryParams['radius'] : 5;
            $distance = 'ROUND(( 6371 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ')) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) )))';
            $restaurants->select('restaurants.*')->selectRaw($distance . ' AS distance')->whereRaw('(' . $distance . ')<=' . $radius);
        }
        $restaurants = $restaurants->Filter($queryParams)->paginate()->toArray();
        $data = $restaurants['data'];
        unset($restaurants['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $restaurants
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
});
/**
 * POST RestaurantPost
 * Summary: Create New restaurant.
 * Notes: Create restaurant.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/restaurants', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    if (isPluginEnabled('Restaurant/SingleRestaurant') && empty($args['parent_id'])) {
        $restaurantCount = Models\Restaurant::where('parent_id', null)->count();
        if ($restaurantCount > 0) {
            return renderWithJson($result, 'Restaurant Could not be addedd.', '', 1, 422);
        }
    }
    if(empty($args['parent_id'])) {
        unset($args['parent_id']);
    }
    if (!empty($args['country']['iso2'])) {
        $country_id = Models\Country::findCountryIdFromIso2($args['country']['iso2']);
        $args['country_id'] = $country_id;
    } elseif (isset($args['country']['iso2'])) {
        $args['country_id'] = '';
    }
    if (!empty($args['state']['name']) && !empty($args['country_id'])) {
        $state_id = Models\State::findOrSaveAndGetStateId($args['state']['name'], $args['country_id']);
        $args['state_id'] = $state_id;
    } elseif (isset($args['state']['name'])) {
        $args['state_id'] = '';
    }
    if (!empty($args['city']['name']) && !empty($args['country_id']) && !empty($args['state_id'])) {
        $city_id = Models\City::findOrSaveAndGetCityId($args['city']['name'], $args['country_id'], $args['state_id']);
        $args['city_id'] = $city_id;
    } elseif (isset($args['city']['name'])) {
        $args['city_id'] = '';
    } 
    if (isset($args['mobile_code_country_id'])) {
        $country = Models\Country::select('iso2')->find($args['mobile_code_country_id']);
        $args['mobile_code'] = $country['iso2'];
    }    
    $restaurant = new Models\Restaurant($args);
    if (!empty($args['restaurant_id'])) {
        unset($args['mobile']);
        unset($args['email']);
    }
    $validationErrorFieldsRestaurant = $restaurant->validate($args);
    if (is_object($validationErrorFieldsRestaurant)) {
        $validationErrorFieldsRestaurant = $validationErrorFieldsRestaurant->toArray();
    }
    $validationErrorFieldsRestaurant = empty($validationErrorFieldsRestaurant) ? [] : $validationErrorFieldsRestaurant;    
    if (!empty($args['email']) && checkAlreadyEmailExists($args['email'])) {
        $validationErrorFieldsRestaurant['email'] = array(
            'unique'
        );        
    } elseif (!empty($args['mobile']) && checkAlreadyMobileExists($args['mobile'])) {
        $validationErrorFieldsRestaurant['mobile'] = array(
            'unique'
        );        
    } elseif (!empty($args['name']) && checkAlreadyRestaurantNameExists($args['name'])) {
        $validationErrorFieldsRestaurant['name'] = array(
            'unique'
        );        
    }
    //mobile number validation
    if(!empty($args['mobile']) && !empty($args['mobile_code'])){
        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $swissNumberProto = $phoneUtil->parse($args['mobile'], $args['mobile_code']);
            if(!$phoneUtil->isValidNumber($swissNumberProto)) {
                $validationErrorFieldsRestaurant['mobile'] = 'Invalid mobile number and mobile code.';
            } else {
                $mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                $mobile_number = $mobile_without_code = $swissNumberProto->getNationalNumber();
                $mobile_country_code = str_replace($mobile_without_code, '', $mobile);
                $args['mobile_code'] = $mobile_country_code;
                $args['mobile'] = $mobile_number;
            }  
        } catch(\libphonenumber\NumberParseException $e){
            $validationErrorFieldsRestaurant['mobile'] = array ('Couldn’t authenticate the mobile number and code.');
        }				
    } else {
        if (empty($args['mobile_code'])) {
            $validationErrorFieldsRestaurant['mobile_code'] = array ('Required');
        } 
        if (empty($args['mobile'])) {
            $validationErrorFieldsRestaurant['mobile'] = array ('Required');
        }
    }      
    $user = new Models\User($args);
    $validationErrorFieldsUser = $user->validate($args);
    if (is_object($validationErrorFieldsUser)) {
        $validationErrorFieldsUser = $validationErrorFieldsUser->toArray();
    } 
    $validationErrorFieldsUser = empty($validationErrorFieldsUser) ? [] : $validationErrorFieldsUser; 
    $validationErrorFields = array_merge( $validationErrorFieldsRestaurant , $validationErrorFieldsUser );      
    if (empty($validationErrorFields)) {
        $password = substr(md5($args['name']), 6, 6);
        $user->username = $user->checkUserName(strtolower(str_replace(' ', '', $args['name'])));
        $user->password = getCryptHash($password);
        $user->role_id = \Constants\ConstUserTypes::RESTAURANT;
        $user->is_email_confirmed = 1;
        $user->is_active = 1;
        if (!empty($args['phone'])) {
            $user->phone = $args['phone'];
        }   
        try {
            unset($restaurant->cuisine);
            if (!empty($restaurant->parent_id)) {
                $restaurantUser = Models\Restaurant::select('user_id', 'mobile')->where('id', $restaurant->parent_id)->first();
                $user->id = $restaurantUser->user_id;
                $restaurant->mobile = $restaurantUser->mobile;
            } elseif ($authUser['role_id'] == \Constants\ConstUserTypes::RESTAURANT) {
                $restaurantParent = Models\Restaurant::select('id')->where('user_id', $authUser['id'])->where('parent_id', null)->first();
                $user->id = $authUser['id'];
                $restaurant->parent_id = $restaurantParent->id;
            }
            if (empty($restaurant->parent_id)) {
                $user->save();
            }
            $restaurant->slug = Inflector::slug(strtolower($args['name']), '-');
            $restaurant->user_id = $user->id;
            $restaurant->mobile_code = $user->mobile_code;
            $restaurant->mobile = $user->mobile;
            $this->geohash = new Geohash();
            $restaurant->hash = $this->geohash->encode(round($args['latitude'], 6), round($args['longitude'], 6));
            $restaurant->save();
            if (!empty($args['cuisine'])) {
                foreach ($args['cuisine'] as $key => $value) {
                    $restaurant_cuisine = new Models\RestaurantCuisine;
                    $restaurant_cuisine->restaurant_id = $restaurant->id;
                    $restaurant_cuisine->cuisine_id = $value;
                    $restaurant_cuisine->save();
                }
            }
            $days = array(
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            );
            foreach ($days as $day) {
                for ($i = 1; $i <= 3; $i++) {
                    $restaurantTiming = new Models\RestaurantTiming;
                    $restaurantTiming->restaurant_id = $restaurant->id;
                    $restaurantTiming->period_type = $i;
                    $restaurantTiming->day = $day;
                    $restaurantTiming->start_time = "00:00";
                    $restaurantTiming->end_time = "00:00";
                    $restaurantTiming->save();
                }
            }
            if (empty($restaurant->parent_id)) {
                $emailFindReplace = array(
                    '##RESTAURANT_NAME##' => $restaurant->name,
                    '##USERNAME##' => $user->username,
                    '##PASSWORD##' => $password,
                );
                //Send Restaurant Welcome Mail
                sendMail('restaurantwelcomemail', $emailFindReplace, $user->email);
            }
            if(!empty($args['image']['attachment'])){
                saveImage('Restaurant', $args['image']['attachment'], $restaurant->id);
            }
            if(!empty($args['image']['image_data'])){
                saveImageData('Restaurant', $args['image']['image_data'],  $restaurant->id);
            }
            if (!empty($args['restaurant_photos'])) {
                if (!is_array($args['restaurant_photos'])) {
                    $args['restaurant_photos'] = explode(',', $args['restaurant_photos']);
                }
                foreach ($args['restaurant_photos'] as $restaurantPhoto) {
                    if (!empty($restaurantPhoto['image_data'])) {
                        saveImageData('RestaurantPhoto', $restaurantPhoto['image_data'], $restaurant->id, true);
                    } else {
                        saveImage('RestaurantPhoto', isset($restaurantPhoto['image']) ? $restaurantPhoto['image'] : $restaurantPhoto, $restaurant->id, true);
                    }
                }
            }
            if(!empty($args['shop_trading_certificate']['attachment'])){
                saveImage('ShopTradingCertificate', $args['shop_trading_certificate']['attachment'], $restaurant->id);
            }
            if(!empty($args['shop_trading_certificate']['image_data'])) {
                saveImageData('ShopTradingCertificate', $args['shop_trading_certificate']['image_data'],  $restaurant->id);
            }
            $restaurants = Models\Restaurant::with('image', 'restaurant_photo', 'trading_certificate')->find($restaurant->id); 
            $result = $restaurants->toArray();                                   
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant could not be added. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateRestaurant'));
/**
 * GET RestaurantGet
 * Summary: Get particular restaurant
 * Notes: Get particular restaurant.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurants/{restaurantId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $restaurant = Models\Restaurant::Filter($queryParams)->find($request->getAttribute('restaurantId'));
    if (!empty($restaurant)) {
        $result['data'] = $restaurant->toArray();
        if(!empty($result['data']['restaurant_cuisine'])){
            foreach ($result['data']['restaurant_cuisine'] as $key => $value) {
                $cusine_id[] = $value['cuisine_id'];
            }
            $result['data']['cuisine'] = $cusine_id;
        }
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
/**
 * PUT RestaurantRestaurantIdPut
 * Summary: Update restaurant
 * Notes: Update restaurant.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurants/{restaurantId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurant = Models\Restaurant::with('attachment')->find($request->getAttribute('restaurantId'));
    if (empty($args['timing'])) {
        if (!empty($args['country']['iso2'])) {
            $country_id = Models\Country::findCountryIdFromIso2($args['country']['iso2']);
            $args['country_id'] = $country_id;
        }
        if (!empty($args['state']['name']) && !empty($args['country_id'])) {
            $state_id = Models\State::findOrSaveAndGetStateId($args['state']['name'], $args['country_id']);
            $args['state_id'] = $state_id;
        }
        if (!empty($args['city']['name']) && !empty($args['country_id']) && !empty($args['state_id'])) {
            $city_id = Models\City::findOrSaveAndGetCityId($args['city']['name'], $args['country_id'], $args['state_id']);
            $args['city_id'] = $city_id;
        }
        if (!empty($args['latitude']) && !empty($args['longitude'])) {
            $this->geohash = new Geohash();
            $args['hash'] = $this->geohash->encode(round($args['latitude'], 6), round($args['longitude'], 6));
        }
    }   
    if (isset($args['mobile_code_country_id'])) {
        $args['mobile_code_country_id'] = 102;
        $country = Models\Country::select('iso2')->find($args['mobile_code_country_id']);
        $args['mobile_code'] = $country['iso2'];
    }
    //mobile number validation
    if(!empty($args['mobile']) && !empty($args['mobile_code'])){
        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $swissNumberProto = $phoneUtil->parse($args['mobile'], $args['mobile_code']);
            if(!$phoneUtil->isValidNumber($swissNumberProto)) {
                $validationErrorFields['mobile'] = 'Invalid mobile number and mobile code.';
            } else {
                $mobile = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                $mobile_number = $mobile_without_code = $swissNumberProto->getNationalNumber();
                $mobile_country_code = str_replace($mobile_without_code, '', $mobile);
                $args['mobile_code'] = $mobile_country_code;
                $args['mobile'] = $mobile_number;
            }  
        } catch(\libphonenumber\NumberParseException $e){
            $validationErrorFields['mobile'] = array ('Couldn’t authenticate the mobile number and code.');
        }				
    }
    $validationErrorFields = $restaurant->validate($args);
    if (empty($validationErrorFields)) {
        $restaurant->fill($args);
        $restaurant->slug = Inflector::slug(strtolower($restaurant->name), '-');
        try {
            if(!empty($args['image']['attachment'])){
                saveImage('Restaurant', $args['image']['attachment'], $restaurant->id);
            }
            if(!empty($args['image']['image_data'])){
                saveImageData('Restaurant', $args['image']['image_data'],  $restaurant->id);
            }
            if (!empty($args['restaurant_photos'])) {
                if (!is_array($args['restaurant_photos'])) {
                    $args['restaurant_photos'] = explode(',', $args['restaurant_photos']);
                }
                foreach ($args['restaurant_photos'] as $restaurantPhoto) {
                    if (!empty($restaurantPhoto['image_data'])) {
                        saveImageData('RestaurantPhoto', $restaurantPhoto['image_data'], $restaurant->id, true);
                    } else {
                        saveImage('RestaurantPhoto', isset($restaurantPhoto['image']) ? $restaurantPhoto['image'] : $restaurantPhoto, $restaurant->id, true);
                    }
                }
            }
            if(!empty($args['shop_trading_certificate']['attachment'])){
                saveImage('ShopTradingCertificate', $args['shop_trading_certificate']['attachment'], $restaurant->id);
            }
            if(!empty($args['shop_trading_certificate']['image_data'])) {
                saveImageData('ShopTradingCertificate', $args['shop_trading_certificate']['image_data'],  $restaurant->id);
            }           
            if ($restaurant->save()) {
                if (!empty($args['timing'])) {
                    //Update Restaurant timings
                    foreach ($args['timing'] as $key => $value) {
                        $day = $value['day'];
                        foreach ($value['period'] as $pkey => $pvalue) {
                            $restaurantTiming = Models\RestaurantTiming::where('restaurant_id', $restaurant->id)->where('period_type', $pvalue['period_type'])->where('day', $day)->update(array(
                                'start_time' => $pvalue['start_time'],
                                'end_time' => $pvalue['end_time']
                            ));
                        }
                    }
                } 
                if (isset($args['cuisine'])) {
                    //Delete already available cuisine id
                    $res_cuisine = Models\RestaurantCuisine::where('restaurant_id', $restaurant->id)->delete();
                    foreach ($args['cuisine'] as $key => $value) {
                        $restaurant_cuisine = new Models\RestaurantCuisine;
                        $restaurant_cuisine->restaurant_id = $restaurant->id;
                        $restaurant_cuisine->cuisine_id = $value;
                        $restaurant_cuisine->save();
                    }
                }
                $restaurants = Models\Restaurant::with('image', 'restaurant_photo', 'trading_certificate', 'restaurant_cuisine', 'restaurant_timing')->find($restaurant->id);
                $result['data'] = $restaurants->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'Restaurant could not be updated. Please, try again.', '', 1);
            }
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateRestaurant'));
/**
 * DELETE RestaurantRestaurantIdDelete
 * Summary: DELETE restaurant
 * Notes: DELETE restaurant.
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/restaurants/{restaurantId}', function ($request, $response, $args) {
    $result = array();
    $restaurant = Models\Restaurant::find($request->getAttribute('restaurantId'));
    try {
        $restaurant->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteRestaurant'));
/**
 * GET RestaurantAddonsGet
 * Summary: Get restaurant offers details
 * Notes: Get restaurant offers details \n
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_addons', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantAddons = Models\RestaurantAddon::Filter($queryParams)->paginate()->toArray();
        $data = $restaurantAddons['data'];
        unset($restaurantAddons['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $restaurantAddons
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
});
/**
 * POST RestaurantAddonsPost
 * Summary: Create restarant addon
 * Notes: create restarant addon
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/restaurant_addons', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantAddon = new Models\RestaurantAddon($args);
    $validationErrorFields = $restaurantAddon->validate($args);
    if (!empty($request->getAttribute('restaurantId'))) {
        $restaurantAddon->restaurant_id = $request->getAttribute('restaurantId');
    }
    if (empty($validationErrorFields)) {
        try {
            if ($restaurantAddon->save()) {
                if (!empty($args['restaurant_addon_item'])) {
                    foreach ($args['restaurant_addon_item'] as $addon_item) {
                        $restaurantAddonItem = new Models\RestaurantAddonItem;
                        $restaurantAddonItem->restaurant_addon_id = $restaurantAddon->id;
                        $restaurantAddonItem->name = $addon_item['name'];
                        $restaurantAddonItem->save();
                    }
                }
                $result = $restaurantAddon->toArray();
                return renderWithJson($result);
            } else {
               return renderWithJson($result, 'Restaurant addon could not be added. Please, try again', '', 1); 
            }
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant addon could not be added. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateRestaurantAddon'));
/**
 * GET RestaurantAddonsRestaurantIdGet
 * Summary: Get particular addons lists
 * Notes: Get particular addons lists.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_addons/{restaurantAddonId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $restaurantAddon = Models\RestaurantAddon::Filter($queryParams)->find($request->getAttribute('restaurantAddonId'));
        if (!empty($restaurantAddon)) {
            $result['data'] = $restaurantAddon->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
});
/**
 * PUT RestaurantAddonsRestaurantIdPut
 * Summary: Updated addons information
 * Notes: Updated addons information.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurant_addons/{restaurantAddonId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurant_addon_item_id = array();
    $args_restaurant_addon_item_id = array();
    $restaurantAddon = Models\RestaurantAddon::find($request->getAttribute('restaurantAddonId'));
    $validationErrorFields = $restaurantAddon->validate($args);
    if (empty($validationErrorFields)) {
        $restaurantAddon->fill($args);
        try {
            if ($restaurantAddon->save()) {
                $restaurantAddonItems = Models\RestaurantAddonItem::where('restaurant_addon_id', $restaurantAddon->id)->get()->toArray();
                if (isset($args['restaurant_addon_item'])) {
                    if (!empty($restaurantAddonItems)) {
                        foreach ($restaurantAddonItems as $restaurant_addon_item) {
                            $restaurant_addon_item_id[] = $restaurant_addon_item['id'];
                        }
                    }
                    if (empty($args['restaurant_addon_item']) && !empty($restaurant_addon_item_id)) {
                        $restaurantAddonItems->whereIn('id', $restaurant_addon_item_id)->delete();
                    } else {
                        foreach ($args['restaurant_addon_item'] as $key => $value) {
                            if (!empty($value['id'])) {
                                $args_restaurant_addon_item_id[] = $value['id'];
                            }
                        }
                        $ids_to_delete = array_diff($restaurant_addon_item_id, $args_restaurant_addon_item_id);
                        if (!empty($ids_to_delete)) {
                            Models\RestaurantAddonItem::whereIn('id', $ids_to_delete)->delete();
                        }
                        foreach ($args['restaurant_addon_item'] as $key => $value) {
                            if (!empty($value['id'])) {
                                $restaurantAddonItem = Models\RestaurantAddonItem::find($value['id']);
                                $restaurantAddonItem->name = $value['name'];
                                $restaurantAddonItem->id = $value['id'];
                                $restaurantAddonItem->save();
                            } else {
                                $restaurantAddonItem = new Models\RestaurantAddonItem;
                                $restaurantAddonItem->restaurant_addon_id = $restaurantAddon->id;
                                $restaurantAddonItem->name = $value['name'];
                                $restaurantAddonItem->save();
                            }
                        }
                    }
                }
                $result['data'] = $restaurantAddon->toArray();
                return renderWithJson($result);
            }
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant addon not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateRestaurantAddon'));
/**
 * DELETE RestaurantAddonsRestaurantAddonIdDelete
 * Summary: Delete addons
 * Notes: Delete addons\n
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/restaurant_addons/{restaurantAddonId}', function ($request, $response, $args) {
    $result = array();
    $restaurantAddon = Models\RestaurantAddon::find($request->getAttribute('restaurantAddonId'));
    try {
        if (!empty($restaurantAddon)) {
            $restaurantAddon->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Restaurant addon could not be deleted. Please, try again.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, '', '', 1);
    }    
})->add(new Acl\ACL('canDeleteRestaurantAddon'));
/**
 * GET RestaurantMenusGet
 * Summary: Get Restaurant menus.
 * Notes: Filter Restaurant menus.
 * Output-Formats: [application/json]
 */
// _getRestaurantMenu 
$app->GET('/api/v1/restaurant_menus', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantMenus = Models\RestaurantMenu::Filter($queryParams)->paginate()->toArray();
        $data = $restaurantMenus['data'];
        unset($restaurantMenus['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $restaurantMenus
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
});
/**
 * POST RestaurantMenusPost
 * Summary: Create new restaurant menu
 * Notes: create new restaurant menu
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/restaurant_menus', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantMenu = new Models\RestaurantMenu($args);
    $validationErrorFields = $restaurantMenu->validate($args);
    if (empty($validationErrorFields)) {
        if (!empty($request->getAttribute('restaurantId'))) {
            $restaurantMenu->restaurant_id = $request->getAttribute('restaurantId');
        }
        unset($restaurantMenu->image);
        try {
            $restaurantMenu->slug = Inflector::slug(strtolower($restaurantMenu->name), '-');
            $position = Models\RestaurantMenu::where('restaurant_category_id', $args['restaurant_category_id'])->max('display_order');
            $restaurantMenu->display_order = $position+1;
            $restaurantMenu->save();
            if (!empty($args['restaurant_menu_price'])) {
                $restaurantMenuPrice = new Models\RestaurantMenuPrice;
                $restaurantMenuPrice->restaurant_menu_id = $restaurantMenu->id;
                $restaurantMenuPrice->price_type_id = $args['restaurant_menu_price']['price_type_id'];
                $restaurantMenuPrice->price_type_name = 'Fixed';
                $restaurantMenuPrice->price = $args['restaurant_menu_price']['price'];
                $restaurantMenuPrice->save();
            }
            if(!empty($args['image']['attachment'])){
                saveImage('RestaurantMenu', $args['image']['attachment'], $restaurantMenu->id);
            }
            if(!empty($args['image']['image_data'])){
                saveImageData('RestaurantMenu', $args['image']['image_data'],  $restaurantMenu->id);
            }
            $restaurantMenu = Models\RestaurantMenu::with('image')->find($restaurantMenu->id);
            $result = $restaurantMenu->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant menu could not be added. Please, try again', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateRestaurantMenu'));
/**
 * PUT RestaurantsRestaurantIdUpdateMenuPositionsPut
 * Summary: Update Restaurant menu position
 * Notes: Update Restaurant menu position
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurants/{restaurantId}/update_position', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $args = $request->getParsedBody();
    $result = array();
    $restaurantMenus = Models\RestaurantMenu::where('restaurant_id', $request->getAttribute('restaurantId'))->get()->toArray();
    try {
        foreach ($args['categories'] as $category) {
            if (!empty($category['menus'])) {
                foreach ($category['menus'] as $key => $value) {
                    if (!empty($restaurantMenus)) {
                        foreach ($restaurantMenus as $restaurantMenu) {
                            if ($restaurantMenu['id'] == $value['id']) {
                                $restaurantMenu = Models\RestaurantMenu::find($value['id']);
                                if ($restaurantMenu['display_order'] != $value['position']) {
                                    $restaurantMenu->display_order = $value['position'];
                                }
                                if ($restaurantMenu['restaurant_category_id'] != $category['category_id']) {
                                    $restaurantMenu->restaurant_category_id = $category['category_id'];
                                }
                                $restaurantMenu->save();
                            }
                        }
                    }
                }
            }
        }
        $restaurantMenus = Models\RestaurantMenu::where('restaurant_id', $request->getAttribute('restaurantId'))->get();
        $result['data'] = $restaurantMenus->toArray();
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canUpdateRestaurantMenuPosition'));
/**
 * GET  RestaurantMenusRestaurantMenuIdGet
 * Summary: Get particular restaurant menu.
 * Notes: particular restaurant menu.
 * Output-Formats: [application/json]
 */
// _viewRestaurantMenu 
$app->GET('/api/v1/restaurant_menus/{restaurantMenuId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantMenu = Models\RestaurantMenu::Filter($queryParams)->find($request->getAttribute('restaurantMenuId'));
        if (!empty($restaurantMenu)) {
            $result['data'] = $restaurantMenu->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }     
});
/**
 * PUTRestaurantMenusRestaurantMenuIdPut
 * Summary: Update Restaurant menu
 * Notes: Update Restaurant menu
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurant_menus/{restaurantMenuId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantMenu = Models\RestaurantMenu::find($request->getAttribute('restaurantMenuId'));
    $validationErrorFields = $restaurantMenu->validate($args);
    $validationErrorFields = 0;
    if (empty($validationErrorFields)) {
        $restaurantMenu->fill($args);
        try {
            if(!empty($args['image']['attachment'])){
                saveImage('RestaurantMenu', $args['image']['attachment'], $restaurantMenu->id);
            }
            if(!empty($args['image']['image_data'])){
                saveImageData('RestaurantMenu', $args['image']['image_data'],  $restaurantMenu->id);
            }
            if (!empty($args['restaurant_menu_price'])) {
                $restaurantMenuPrices = Models\RestaurantMenuPrice::where('restaurant_menu_id', $request->getAttribute('restaurantMenuId'))->get()->toArray();
                if (!empty($restaurantMenuPrices)) {
                    foreach ($restaurantMenuPrices as $restaurantMenuPrice) {
                        $restaurant_menu_price_id[] = $restaurantMenuPrice['id'];
                        $restaurant_menu_price_price_type_id[] = $restaurantMenuPrice['price_type_id'];
                    }
                    foreach ($args['restaurant_menu_price'] as $key => $value) {
                        if (!empty($value['id'])) {
                            $args_restaurant_menu_price_id[] = $value['id'];
                        }
                        $args_restaurant_menu_price_price_type_id[] = $value['price_type_id'];
                    }
                    if (!empty($restaurant_menu_price_price_type_id) && !empty($args_restaurant_menu_price_price_type_id)) {
                        $diff = array_diff($restaurant_menu_price_price_type_id, $args_restaurant_menu_price_price_type_id);
                        if (!empty($diff)) {
                            foreach ($diff as $key => $val) {
                                Models\RestaurantMenuPrice::where('restaurant_menu_id', $request->getAttribute('restaurantMenuId'))->where('price_type_id', $val)->delete();
                            }
                            if (!empty($restaurant_menu_price_id) && !empty($args_restaurant_menu_price_id)) {
                                $ids_to_delete = array_diff($restaurant_menu_price_id, $args_restaurant_menu_price_id);
                                Models\RestaurantMenuPrice::whereIn('id', $ids_to_delete)->delete();
                            }
                        } else {
                            if (!empty($restaurant_menu_price_id) && !empty($args_restaurant_menu_price_id)) {
                                $ids_to_delete = array_diff($restaurant_menu_price_id, $args_restaurant_menu_price_id);
                                Models\RestaurantMenuPrice::whereIn('id', $ids_to_delete)->delete();
                            }
                        }
                    }
                    foreach ($args['restaurant_menu_price'] as $key => $value) {
                        if (!empty($value['id'])) {
                            $restaurantMenuPrice = Models\RestaurantMenuPrice::find($value['id']);
                            $restaurantMenuPrice->restaurant_menu_id = $request->getAttribute('restaurantMenuId');
                            $restaurantMenuPrice->price_type_id = $value['price_type_id'];
                            $restaurantMenuPrice->price_type_name = $value['price_type_name'];
                            $restaurantMenuPrice->price = $value['price'];
                            $restaurantMenuPrice->id = $value['id'];
                            $restaurantMenuPrice->save();
                        } else {
                            $restaurantMenuPrice = new Models\RestaurantMenuPrice;
                            $restaurantMenuPrice->restaurant_menu_id = $request->getAttribute('restaurantMenuId');
                            $restaurantMenuPrice->price_type_id = $value['price_type_id'];
                            $restaurantMenuPrice->price_type_name = $value['price_type_name'];
                            $restaurantMenuPrice->price = $value['price'];
                            $restaurantMenuPrice->save();
                        }
                    }
                } else {
                    foreach ($args['restaurant_menu_price'] as $key => $value) {
                        $restaurantMenuPrice = new Models\RestaurantMenuPrice;
                        $restaurantMenuPrice->restaurant_menu_id = $request->getAttribute('restaurantMenuId');
                        $restaurantMenuPrice->price_type_id = $value['price_type_id'];
                        $restaurantMenuPrice->price_type_name = $value['price_type_name'];
                        $restaurantMenuPrice->price = $value['price'];
                        $restaurantMenuPrice->save();
                    }
                }
            }
            if (!empty($args['restaurant_addon'])) {
                foreach ($args['restaurant_addon'] as $restaurantAddon) {
                    if (!empty($restaurantAddon['restaurant_addon_item'])) {
                        foreach ($restaurantAddon['restaurant_addon_item'] as $restaurantAddonItem) {
                            if (!empty($restaurantAddonItem['restaurant_menu_addon_price'])) {
                                $restaurantMenuAddonPrices = Models\RestaurantMenuAddonPrice::where('restaurant_menu_id', $request->getAttribute('restaurantMenuId'))->get()->toArray();
                                if (!empty($restaurantMenuAddonPrices)) {
                                    foreach ($restaurantMenuAddonPrices as $restaurantMenuPrice) {
                                        $restaurant_menu_addon_price_id[] = $restaurantMenuPrice['id'];
                                    }
                                    foreach ($restaurantAddonItem['restaurant_menu_addon_price'] as $key => $value) {
                                        if (!empty($value['id'])) {
                                            $args_restaurant_menu_addon_price_id[] = $value['id'];
                                        }
                                    }
                                    if (!empty($restaurant_menu_addon_price_id) && !empty($args_restaurant_menu_addon_price_id)) {
                                        $ids_to_delete = array_diff($restaurant_menu_addon_price_id, $args_restaurant_menu_addon_price_id);
                                        Models\RestaurantMenuAddonPrice::whereIn('id', $ids_to_delete)->delete();
                                    }
                                    foreach ($restaurantAddonItem['restaurant_menu_addon_price'] as $key => $value) {
                                        if (!empty($value['id'])) {
                                            $restaurantMenuAddonPrice = Models\RestaurantMenuAddonPrice::find($value['id']);
                                            $restaurantMenuAddonPrice->restaurant_menu_id = $request->getAttribute('restaurantMenuId');
                                            $restaurantMenuAddonPrice->restaurant_addon_item_id = $value['restaurant_addon_item_id'];
                                            $restaurantMenuAddonPrice->restaurant_addon_id = $value['restaurant_addon_id'];
                                            $restaurantMenuAddonPrice->price = $value['price'];
                                            $restaurantMenuAddonPrice->is_free = $value['is_free'];
                                            $restaurantMenuAddonPrice->is_active = $value['is_active'];
                                            $restaurantMenuAddonPrice->id = $value['id'];
                                            $restaurantMenuAddonPrice->save();
                                        } else {
                                            $restaurantMenuAddonPrice = new Models\RestaurantMenuAddonPrice;
                                            $restaurantMenuAddonPrice->restaurant_menu_id = $request->getAttribute('restaurantMenuId');
                                            $restaurantMenuAddonPrice->restaurant_addon_item_id = $value['restaurant_addon_item_id'];
                                            $restaurantMenuAddonPrice->restaurant_addon_id = $value['restaurant_addon_id'];
                                            $restaurantMenuAddonPrice->price = $value['price'];
                                            $restaurantMenuAddonPrice->is_free = !empty($value['is_free']) ? $value['is_free'] : 0;
                                            $restaurantMenuAddonPrice->is_active = $value['is_active'];
                                            $restaurantMenuAddonPrice->save();
                                        }
                                    }
                                } else {
                                    if (!empty($restaurantAddonItem['restaurant_menu_addon_price'])) {
                                        foreach ($restaurantAddonItem['restaurant_menu_addon_price'] as $key => $value) {
                                            $restaurantMenuAddonPrice = new Models\RestaurantMenuAddonPrice;
                                            $restaurantMenuAddonPrice->restaurant_menu_id = $request->getAttribute('restaurantMenuId');
                                            $restaurantMenuAddonPrice->restaurant_addon_item_id = $value['restaurant_addon_item_id'];
                                            $restaurantMenuAddonPrice->restaurant_addon_id = $value['restaurant_addon_id'];
                                            $restaurantMenuAddonPrice->price = $value['price'];
                                            $restaurantMenuAddonPrice->is_free = $value['is_free'];
                                            $restaurantMenuAddonPrice->is_active = $value['is_active'];
                                            $restaurantMenuAddonPrice->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $restaurantMenu->slug = Inflector::slug(strtolower($restaurantMenu->name), '-');
            $restaurantMenu->save();
            $restaurantMenu = Models\RestaurantMenu::with('cuisine')->find($restaurantMenu->id);
            $result['data'] = $restaurantMenu->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant menu could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateRestaurantMenu'));
/**
 * DELETE RestaurantMenusRestaurantMenuIdDelete
 * Summary: DELETE Restaurant Menu
 * Notes: DELETE Restaurant Menu
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/restaurant_menus/{restaurantMenuId}', function ($request, $response, $args) {
    $result = array();
    $restaurantMenu = Models\RestaurantMenu::find($request->getAttribute('restaurantMenuId'));
    try {
        if (!empty($restaurantMenu)) {
            $restaurantMenu->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Restaurant menu could not be deleted. Please, try again.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
})->add(new Acl\ACL('canDeleteRestaurantMenu'));
/**
 * GET RestaurantCategoriesGet
 * Summary: Get categotries
 * Notes: Filter categotry
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_categories', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantCategories = Models\RestaurantCategory::Filter($queryParams)->paginate()->toArray();
        $data = $restaurantCategories['data'];
        unset($restaurantCategories['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $restaurantCategories
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
});
/**
 * POST RestaurantCategoriesPost
 * Summary: Create admin restaurant category
 * Notes: Create admin restaurant category
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/restaurant_categories', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantCategory = new Models\RestaurantCategory($args);
    $validationErrorFields = $restaurantCategory->validate($args);
    $validationErrorFields['unique'] = array();
    if (checkAlreadyCategoryNameExists($args['name'], $args['restaurant_id'])) {
        array_push($validationErrorFields['unique'], 'name');
    }
    if (empty($validationErrorFields['unique'])) {
        unset($validationErrorFields['unique']);
    }
    if (empty($validationErrorFields)) {
        if (!empty($request->getAttribute('restaurantId'))) {
            $restaurantCategory->restaurant_id = $request->getAttribute('restaurantId');
        }
        unset($restaurantCategory->image);
        try {
            $restaurantCategory->slug = Inflector::slug(strtolower($restaurantCategory->name), '-');
            $position = Models\RestaurantCategory::where('restaurant_id', $args['restaurant_id'])->max('display_order');
            $restaurantCategory->display_order = $position+1;
            $restaurantCategory->save();
            if(!empty($args['image']['attachment'])){
                saveImage('RestaurantCategory', $args['image']['attachment'], $restaurantCategory->id);
            }
            if(!empty($args['image']['image_data'])){
                saveImageData('RestaurantCategory', $args['image']['image_data'],  $restaurantCategory->id);
            }
            $restaurantCategory = Models\RestaurantCategory::with('image')->find($restaurantCategory->id);
            $result = $restaurantCategory->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant category not be added. Please, try again.', $validationErrorFields, 1);
    }    
})->add(new Acl\ACL('canCreateRestaurantCategory'));
/**
 * PUT RestaurantsRestaurantIdUpdateCategoriesPut
 * Summary: Update Restaurant category position
 * Notes: Update Restaurant category position
 * Output-Formats: [application/json]
 */
// _putRestaurantCategoryPosition 
$app->PUT('/api/v1/restaurants/{restaurantId}/category_update_position', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();    
    $args = $request->getParsedBody();
    $result = array();
    $restaurantCategories = Models\RestaurantCategory::where('restaurant_id', $request->getAttribute('restaurantId'))->get()->toArray();
    try {
        if (!empty($restaurantCategories) && !empty($args['categories'])) {
            foreach ($args['categories'] as $key => $value) {
                foreach ($restaurantCategories as $restaurantCategory) {
                    if ($restaurantCategory['id'] == $value['category_id']) {
                        $restaurantCategory = Models\RestaurantCategory::find($value['category_id']);
                        if ($restaurantCategory['display_order'] != $value['position']) {
                            $restaurantCategory->display_order = $value['position'];
                        }
                        $restaurantCategory->save();
                    }
                }
            }
        }
        $restaurantCategories = Models\RestaurantCategory::where('restaurant_id', $request->getAttribute('restaurantId'))->get()->toArray();
        $result['data'] = $restaurantCategories;
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), $e->getMessage(), 1);
    }    
})->add(new Acl\ACL('canUpdateRestaurantCategoryPosition'));
/**
 * GET restaurantCategoriesRestaurantIdCategoryIdGet
 * Summary: Get particular restaurant category details
 * Notes: Get particular restaurant category details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_categories/{restaurantCategoryId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantCategory = Models\RestaurantCategory::Filter($queryParams)->find($request->getAttribute('restaurantCategoryId'));
        if (!empty($restaurantCategory)) {
            $result['data'] = $restaurantCategory->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Language not found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }     
});
/**
 * PUT RestaurantCategoriesRestaurantCategoryIdPut
 * Summary: Update categotry by  admin
 * Notes: update categotry by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurant_categories/{restaurantCategoryId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantCategory = Models\RestaurantCategory::find($request->getAttribute('restaurantCategoryId'));
    $validationErrorFields = $restaurantCategory->validate($args);
    if (empty($validationErrorFields)) {
        $restaurantCategory->fill($args);
        if (!empty($request->getAttribute('restaurantId'))) {
            $restaurantCategory->restaurant_id = $request->getAttribute('restaurantId');
        }
        unset($restaurantCategory->attachment);
        unset($restaurantCategory->image);
        try {
            $restaurantCategory->slug = Inflector::slug(strtolower($restaurantCategory->name), '-');
            $restaurantCategory->save();
            if(!empty($args['image']['attachment'])){
                saveImage('RestaurantCategory', $args['image']['attachment'], $restaurantCategory->id);
            }
            if(!empty($args['image']['image_data'])){
                saveImageData('RestaurantCategory', $args['image']['image_data'],  $restaurantCategory->id);
            }
            $restaurantCategory = Models\RestaurantCategory::with('image')->find($restaurantCategory->id);
            
            $result['data'] = $restaurantCategory->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant Category could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateRestaurantCategory'));
/**
 * DELETE restaurantCategoriesCategoryIdDelete
 * Summary: Delete restaurant Category by admin
 * Notes: Delete restaurant Category by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/restaurant_categories/{restaurantCategoryId}', function ($request, $response, $args) {
    $result = array();
    $restaurantCategory = Models\RestaurantCategory::find($request->getAttribute('restaurantCategoryId'));
    if (!empty($restaurantCategory)) {
        try {
            $restaurantCategory->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant Category could not be deleted. Please, try again.', '', 1);
    }    
})->add(new Acl\ACL('canDeleteRestaurantCategory'));
/**
 * GET RestaurantTimingsGet
 * Summary: Get restaurant timings
 * Notes: Filter restaurant timings
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_timings', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantTimings = Models\RestaurantTiming::Filter($queryParams)->paginate()->toArray();
        $data = $restaurantTimings['data'];
        unset($restaurantTimings['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $restaurantTimings
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    
});
/**
 * POST RestaurantRestaurantIdRestaurantTimingPost
 * Summary: Create restaurant timing
 * Notes: Create restaurant timing
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/restaurant_timings', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $queryParams = $request->getQueryParams();
    $result = array();
    $restaurant_timing = new Models\RestaurantTiming;
    $validationErrorFields = $restaurant_timing->validate($body);
    if (empty($validationErrorFields)) {
        try {
            foreach ($body['period'] as $key => $arg) {
                $restaurantTiming = new Models\RestaurantTiming;
                $restaurantTiming->restaurant_id = $queryParams['restaurantId'];
                $restaurantTiming->day = $body['day'];
                $restaurantTiming->period_type = $arg['peroid_type'];
                $restaurantTiming->start_time = $arg['start_time'];
                $restaurantTiming->end_time = $arg['end_time'];
                $restaurantTiming->save();
            }
            $restaurantTimings = Models\RestaurantTiming::where('restaurant_id', $request->getAttribute('restaurantId'))->get();
            $result['data'] = $restaurantTimings->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant timing not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canRestaurantCreateRestaurantTiming'));
/**
 * GET restaurantsrestaurantIdrestaurantTimingsrestaurantTimingIdGET
 * Summary: Get particular restaurant timing details
 * Notes: Get particular restaurant timing details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_timings/{restaurantTimingId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $restaurantTiming = Models\RestaurantTiming::Filter($queryParams)->find($request->getAttribute('restaurantTimingId'));
    if (!empty($restaurantTiming)) {
        $result['data'] = $restaurantTiming->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1);
    }
});
/**
 * PUT restaurantsrestaurantIdrestaurantTimingsrestaurantTimingIdPut
 * Summary: Update restaurant timing
 * Notes: update restaurant timing
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/restaurant_timings/{restaurantTimingId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $restaurantTiming = Models\RestaurantTiming::find($request->getAttribute('restaurantTimingId'));
    $validationErrorFields = $restaurantTiming->validate($args);
    if (empty($validationErrorFields)) {
        try {
            $restaurantTiming->fill($args);
            $restaurantTiming->save();
            $result['data'] = $restaurantTiming->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Restaurant Timing not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canRestaurantUpdateRestaurantTiming'));
/**
 * DELETE  restaurantsrestaurantIdrestaurantTimingsrestaurantTimingIdDelete
 * Summary: Delete restaurant timing
 * Notes: Delete restaurant timing
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/restaurant_timings/{restaurantTimingId}', function ($request, $response, $args) {
    $result = array();
    $restaurantTiming = Models\RestaurantTiming::find($request->getAttribute('restaurantTimingId'));
    try {
        if(!empty($restaurantTiming)){
            $restaurantTiming->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        }else{
            return renderWithJson($result, 'No record Found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canRestaurantDeleteRestaurantTiming'));
/**
 * GET CuisinesGet
 * Summary: Get  cuisines
 * Notes: Filter cuisines.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/cuisines', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $cuisines = Models\Cuisine::Filter($queryParams)->paginate()->toArray();
        $data = $cuisines['data'];
        unset($cuisines['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $cuisines
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }    

});
/**
 * POST cuisinesPost
 * Summary: Create new cuisine
 * Notes: create new cuisine
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/cuisines', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $cuisine = new Models\Cuisine($args);
    $result = array();
    $validationErrorFields = $cuisine->validate($args);
    $validationErrorFields['unique'] = array();
    if (checkAlreadyCuisineNameExists($args['name'])) {
        array_push($validationErrorFields['unique'], 'name');
    }
    if (empty($validationErrorFields['unique'])) {
        unset($validationErrorFields['unique']);
    }
    if (empty($validationErrorFields)) {
        $cuisine->slug = Inflector::slug(strtolower($cuisine->name), '-');
        try {
            $cuisine->save();
            $result = $cuisine->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Cuisine could not be added. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canCreateCuisine'));
/**
 * GET CuisinescuisineIdGet
 * Summary:Get cuisine details
 * Notes: Get cuisine details
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/cuisines/{cuisineId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $cuisine = Models\Cuisine::Filter($queryParams)->find($request->getAttribute('cuisineId'));
        if (!empty($cuisine)) {
            $result['data'] = $cuisine->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Cuisines not found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canViewCuisine'));
/**
 * PUT CuisinesecuisineIdPut
 * Summary: Update cuisine by admin
 * Notes: Update cuisine by admin
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/cuisines/{cuisineId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $result = array();
    $cuisine = Models\Cuisine::find($request->getAttribute('cuisineId'));
    $validationErrorFields = $cuisine->validate($args);
    if (empty($validationErrorFields)) {
        $cuisine->fill($args);
        $cuisine->slug = Inflector::slug(strtolower($cuisine->name), '-');
        try {
            $cuisine->save();
            $data = $cuisine->toArray();
            $result = array(
                'data' => $data,
                'message' => 'Success'
            );
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1);
        }
    } else {
        return renderWithJson($result, 'Cuisine could not be updated. Please, try again.', $validationErrorFields, 1);
    }
})->add(new Acl\ACL('canUpdateCuisine'));
/**
 * DELETE CuisinesCuisineIdDelete
 * Summary: DELETE cuisine by admin
 * Notes: DELETE cuisine by admin
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/cuisines/{cuisineId}', function ($request, $response, $args) {
    $result = array();
    $cuisine = Models\Cuisine::find($request->getAttribute('cuisineId'));
    try {
        $cuisine->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1);
    }
})->add(new Acl\ACL('canDeleteCuisine'));
/**
 * GET restaurantCuisinesGet
 * Summary: Fetch all restaurant cuisines
 * Notes: Returns all restaurant cuisines from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_cuisines', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$restaurantCuisines = Models\RestaurantCuisine::Filter($queryParams)->paginate()->toArray();
		$data = $restaurantCuisines['data'];
		unset($restaurantCuisines['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $restaurantCuisines
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, 'No record found', $e->getMessage(), '', 1, 422);
	}
});
/**
 * DELETE restaurantCuisinesRestaurantCuisineIdDelete
 * Summary: Delete restaurant cuisine
 * Notes: Deletes a single restaurant cuisine based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/restaurant_cuisines/{restaurantCuisineId}', function($request, $response, $args) {
    global $authUser;
	$restaurantCuisine = Models\RestaurantCuisine::with('restaurant')->find($request->getAttribute('restaurantCuisineId'));
	$result = array();
	try {
		if (!empty($restaurantCuisine)) {
            if ($restaurantCuisine->restaurant->user_id == $authUser->id || $authUser->role_id == \Constants\ConstUserTypes::ADMIN) {
                $restaurantCuisine->delete();
                $result = array(
                    'status' => 'success',
                );
                return renderWithJson($result);
            } else {
               return renderWithJson($result, 'Restaurant cuisine could not be deleted. Please, try again.', '', '', 1, 422); 
            }
		} else {
			return renderWithJson($result, 'No record found', '', '', 1, 404);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'Restaurant cuisine could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
	}
})->add(new Acl\ACL('canDeleteRestaurantCuisine'));
/**
 * GET restaurantCuisinesRestaurantCuisineIdGet
 * Summary: Fetch restaurant cuisine
 * Notes: Returns a restaurant cuisine based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/restaurant_cuisines/{restaurantCuisineId}', function($request, $response, $args) {
	$result = array();
    $queryParams = $request->getQueryParams();
    try {
        $restaurantCuisine = Models\RestaurantCuisine::Filter($queryParams)->find($request->getAttribute('restaurantCuisineId'));
        if (!empty($restaurantCuisine)) {
            $result['data'] = $restaurantCuisine;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch(Exception $e) {
		return renderWithJson($result, 'No record found', $e->getMessage(), '', 1, 422);
	}
});
