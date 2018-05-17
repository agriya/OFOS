<?php
$menus = array (
    'Listing' => array (
        'title' => 'Listing',
        'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
        'child_sub_menu' => array (
             'shops' => array (
                'title' => 'Shops',
                'icon_template' => '<span class="fa fa-cutlery fa-fw"></span>',
                'suborder' => 1
            ),
            'restaurant_addons' => 
            array (
                'title' => 'Addons',
                'icon_template' => '<span class="fa fa-puzzle-piece fa-fw"></span>',
                'suborder' => 7
            ),
            'restaurant_menus' => 
            array (
                'title' => 'Menus',
                'icon_template' => '<span class="fa fa-list fa-fw"></span>',
                'suborder' => 6,
                'link' => '/menus'
            ),
        ),
        'order' => 4
    )
);
$tables = array (
  'shops' => 
  array (
    'listview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'label' => 'ID',
          'isDetailLink' => true,
        ),
        1 => array (
            'name' => 'image',
            'lable' => 'Image',
            'template' => '<display-image entry="entry" type="Restaurant" entity="entity"></display-image>'
        ),
        2 => 
        array (
          'name' => 'name',
          'label' => 'Shop',
        ),
        3 => 
        array (
          'name' => 'contact_name',
          'label' => 'Contact Person',
        ),
        4 => 
        array (
          'name' => 'contact_phone',
          'label' => 'Contact Phone',
        ),
        5 => 
        array (
          'name' => 'total_orders',
          'label' => 'Total Orders',
        ),
        6 => 
        array (
          'name' => 'total_revenue',
          'label' => 'Total Revenue',
        ),
        7 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        8 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On',
        ),
      ),
      'title' => 'Shops',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'edit',
        1 => 'show',
        2 => 'delete',
        3 => '<a href="#/restaurant/timing?restaurant={{entry.values.id}}"><button type="button" class="glyphicon glyphicon-time btn btn-primary editable-table-button btn-xs">Timing</button></a>',
        4 => '<a ng-if="entry.values.parent_id === null" href="#/shops/list?search=%7B%22parent_id%22:{{entry.values.id}}%7D"><button type="button" class="btn btn-primary btn-xs">Branches</button></a>'
      ),
      'filters' => 
      array (
        0 => 
        array (
          'name' => 'q',
          'pinned' => true,
          'label' => 'Search',
          'type' => 'template',
          'template' => '',
        ),
        2 => 
        array (
          'name' => 'id',
          'label' => 'Shops',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        3 => 
        array (
          'name' => 'parent_id',
          'label' => 'Shop Branches',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
          'permanentFilters' => array (
            'parent_id' => null
          )
        ),
        1 => 
        array (
          'name' => 'is_active',
          'type' => 'choice',
          'label' => 'Active',
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        )
      ),
      'permanentFilters' => '',
      'batchActions' => array (
        0 => '<batch-actions resource="restaurants" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
        1 => '<batch-actions resource="restaurants" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
        2 => 'delete'
      ),
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter',
        2 => 'create',
      ),
    ),
    'creationview' => 
    array (
      'fields' => 
      array (
        0 => array (
          'name' => 'parent_id',
          'label' => 'Shops',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
          'permanentFilters' => array (
              "parent_id" =>  0
          )
        ),       
        1 => 
        array (
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        2 => 
        array (
          'name' => 'cuisine',
          'label' => 'Speciality',
          'targetEntity' => 'cuisines',
          'targetField' => 'name',
          'type' => 'reference_many',
          'validation' => 
          array (
            'required' => false,
          ),
          'remoteComplete' => true,
        ),
        3 => 
        array (
          'name' => 'email',
          'label' => 'Email',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        4 => array (
          'name' => 'mobile_code_country_id',
          'label' => 'Mobile Code',
          'targetEntity' => 'countries',
          'targetField' => 'phone',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'validation' => 
          array (
            'required' => true,
          ),
          'type' => 'reference',
          'remoteComplete' => true
        ),          
        5 => 
        array (
          'name' => 'mobile',
          'label' => 'Mobile',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),      
        6 => 
        array (
          'name' => 'image.attachment',
          'label' => 'Image',
          'type' => 'file',
          'uploadInformation' => array (
            'url' => 'api/v1/attachments',
            'apifilename' => 'id',
            'multiple' => false
          ),
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        7 => 
        array (
          'name' => 'fax',
          'label' => 'Fax',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        8 => 
        array (
          'name' => 'contact_name',
          'label' => 'Contact Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        9 => 
        array (
          'name' => 'contact_phone',
          'label' => 'Contact Phone',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        10 => 
        array (
          'name' => 'website',
          'label' => 'Website',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        11 => 
        array (
          'name' => 'location',
          'label' => 'Location',
          'template' => '<google-places entry="entry" entity="entity"></google-places>',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        12 => 
        array (
          'name' => 'address',
          'label' => 'Address',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        13 => 
        array (
          'name' => 'address1',
          'label' => 'Area',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        14 => 
        array (
          'name' => 'city.name',
          'label' => 'City',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        15 => 
        array (
          'name' => 'state.name',
          'label' => 'State',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        16 => 
        array (
          'name' => 'country.iso2',
          'label' => 'Country',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        17 => 
        array (
          'name' => 'zip_code',
          'label' => 'Postcode / Zip Code',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        18 => 
        array (
          'name' => 'latitude',
          'label' => 'Latitude',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        19 => 
        array (
          'name' => 'longitude',
          'label' => 'Longitude',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        20 => 
        array (
          'name' => 'sales_tax',
          'label' => 'Sales Tax',
          'template' => '<div class="input-group"><input type="number" ng-model="value" id="sales_tax" pattern="[0-9]+" min="1" max="100" name="sales_tax" class="form-control" required></input><span class="input-group-addon">%</span></div>',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        21 => 
        array (
          'name' => 'minimum_order_for_booking',
          'label' => 'Minimum Order For Booking',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        22 => 
        array (
          'name' => 'estimated_time_to_delivery',
          'label' => 'Estimated Time To Delivery (In mins)',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        23 => 
        array (
          'name' => 'delivery_charge',
          'label' => 'Delivery Charge',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        24 => 
        array (
          'name' => 'delivery_miles',
          'label' => 'Delivery Miles',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        25 => 
        array (
          'name' => 'is_allow_users_to_door_delivery_order',
          'label' => 'Allow Users To Door Delivery Order?',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        26 => 
        array (
          'name' => 'is_allow_users_to_pickup_order',
          'label' => 'Allow Users To Pickup Order?',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        27 => 
        array (
          'name' => 'is_allow_users_to_preorder',
          'label' => 'Allow Users To Preorder?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        28 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        29 => 
        array (
          'name' => 'restaurant_photos',
          'label' => 'Shop Photos',
          'type' => 'file',
          'uploadInformation' => array (
            'url' => 'api/v1/attachments',
            'apifilename' => 'id',
            'multiple' => true
          ),
          'validation' => 
          array (
            'required' => false,
          ),
        ),
      ),
      'title' => 'Create New Shops'
    ),
    'editionview' => array (
      'fields' => array (
        0 => array (
          'name' => 'parent_id',
          'label' => 'Shops',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
          'permanentFilters' => array (
              "parent_id" =>  0
          )
        ),
        1 => array (
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        2 => array (
          'name' => 'cuisine',
          'label' => 'Cuisine',
          'targetEntity' => 'cuisines',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference_many',
          'remoteComplete' => true
        ),
        3 => array (
          'name' => 'mobile_code_country_id',
          'label' => 'Mobile Code',
          'targetEntity' => 'countries',
          'targetField' => 'phone',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'validation' => 
          array (
            'required' => true,
          ),
          'type' => 'reference',
          'remoteComplete' => true
        ),           
        4 => array (
          'name' => 'mobile',
          'label' => 'Mobile',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        5 => array (
          'name' => 'image.attachment',
          'label' => 'Image',
          'type' => 'file',
          'uploadInformation' => array (
            'url' => 'api/v1/attachments',
            'apifilename' => 'id',
            'multiple' => false
          ),
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        6 => array (
          'name' => 'fax',
          'label' => 'Fax',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        7 => 
        array (
          'name' => 'contact_name',
          'label' => 'Contact Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        8 => 
        array (
          'name' => 'contact_phone',
          'label' => 'Contact Phone',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        9 => 
        array (
          'name' => 'website',
          'label' => 'Website',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        10 => 
        array (
          'name' => 'location',
          'label' => 'Location',
          'template' => '<google-places entry="entry" entity="entity"></google-places>',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        11 => 
        array (
          'name' => 'address',
          'label' => 'Address',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        12 => 
        array (
          'name' => 'address1',
          'label' => 'Area',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        13 => 
        array (
          'name' => 'city.name',
          'label' => 'City',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        14 => 
        array (
          'name' => 'state.name',
          'label' => 'State',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        15 => 
        array (
          'name' => 'country.iso2',
          'label' => 'Country',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        16 => 
        array (
          'name' => 'zip_code',
          'label' => 'Zip Code',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        17 => 
        array (
          'name' => 'latitude',
          'label' => 'Latitude',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        18 => 
        array (
          'name' => 'longitude',
          'label' => 'Longitude',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        19 => 
        array (
          'name' => 'sales_tax',
          'label' => 'Sales Tax',
          'template' => '<div class="input-group"><input type="text" ng-model="value" id="sales_tax" pattern="[0-9]+" name="sales_tax" class="form-control" required></input><span class="input-group-addon">%</span></div>',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        20 => 
        array (
          'name' => 'minimum_order_for_booking',
          'label' => 'Minimum Order For Booking',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        21 => 
        array (
          'name' => 'estimated_time_to_delivery',
          'label' => 'Estimated Time To Delivery (In mins)',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        22 => 
        array (
          'name' => 'delivery_charge',
          'label' => 'Delivery Charge',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        23 => 
        array (
          'name' => 'delivery_miles',
          'label' => 'Delivery Miles',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        24 => 
        array (
          'name' => 'is_allow_users_to_door_delivery_order',
          'label' => 'Allow Users To Door Delivery Order?',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        25 => 
        array (
          'name' => 'is_allow_users_to_pickup_order',
          'label' => 'Allow Users To Pickup Order?',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        26 => 
        array (
          'name' => 'is_allow_users_to_preorder',
          'label' => 'Allow Users To Preorder?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        27 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        28 => 
        array (
          'name' => 'restaurant_photos',
          'label' => 'Shop Photos',
          'type' => 'file',
          'uploadInformation' => array (
            'url' => 'api/v1/attachments',
            'apifilename' => 'id',
            'multiple' => true
          ),
          'validation' => 
          array (
            'required' => false,
          ),
        )
      ),
      'title' => 'Edit Shop'
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        1 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'mobile',
          'label' => 'Mobile'
        ),
        4 => 
        array (
          'name' => 'fax',
          'label' => 'Fax'
        ),
        5 => 
        array (
          'name' => 'contact_name',
          'label' => 'Contact Person'
        ),
        6 => 
        array (
          'name' => 'contact_phone',
          'label' => 'Contact Phone'
        ),
        7 => 
        array (
          'name' => 'website',
          'label' => 'Website'
        ),
        8 => 
        array (
          'name' => 'address',
          'label' => 'Address'
        ),
        9 => 
        array (
          'name' => 'address1',
          'label' => 'Area'
        ),
        10 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        11 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        12 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        13 => 
        array (
          'name' => 'latitude',
          'label' => 'Latitude'
        ),
        14 => 
        array (
          'name' => 'longitude',
          'label' => 'Longitude'
        ),
        15 => 
        array (
          'name' => 'zip_code',
          'label' => 'Zip Code'
        ),
        16 => 
        array (
          'name' => 'sales_tax',
          'label' => 'Sales Tax'
        ),
        17 => 
        array (
          'name' => 'minimum_order_for_booking',
          'label' => 'Minimum Order For Booking'
        ),
        18 => 
        array (
          'name' => 'estimated_time_to_delivery',
          'label' => 'Estimated Time To Delivery (In mins)'
        ),
        19 => 
        array (
          'name' => 'delivery_charge',
          'label' => 'Delivery Charge'
        ),
        20 => 
        array (
          'name' => 'delivery_miles',
          'label' => 'Delivery Miles'
        ),
        21 => 
        array (
          'name' => 'is_allow_users_to_door_delivery_order',
          'label' => 'Allow Users To Door Delivery Order?',
          'type' => 'boolean'
        ),
        22 => 
        array (
          'name' => 'is_allow_users_to_pickup_order',
          'label' => 'Allow Users To Pickup Order?',
          'type' => 'boolean'
        ),        
        23 => 
        array (
          'name' => 'is_allow_users_to_preorder',
          'label' => 'Allow Users To Preorder?',
          'type' => 'boolean'
        ),
        24 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        25 => array (
            'name' => 'created_at',
            'label' => 'Created On',
        ),
      ),
    ),
  ),
  'restaurant_addons' => 
  array (
    'listview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'label' => 'ID',
          'isDetailLink' => true,
        ),
        1 => 
        array (
          'name' => 'name',
          'label' => 'Addon',
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'restaurant_category.name',
          'label' => 'Category'
        ),
        4 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        5 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On',
        ),
      ),
      'title' => 'Addons',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'edit',
        1 => 'show',
        2 => 'delete',
      ),
      'filters' => 
      array (
        0 => 
        array (
          'name' => 'q',
          'pinned' => true,
          'label' => 'Search',
          'type' => 'template',
          'template' => '',
        ),
        1 => 
        array (
          'name' => 'is_active',
          'type' => 'choice',
          'label' => 'Active',
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        2 => 
        array (
          'name' => 'restaurant_id',
          'label' => 'Shops',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        3 => 
        array (
          'name' => 'restaurant_category_id',
          'label' => 'Shop Category',
          'targetEntity' => 'restaurant_categories',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),                 
      ),
      'permanentFilters' => '',
      'batchActions' => array (
        0 => '<batch-actions resource="restaurant_addons" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
        1 => '<batch-actions resource="restaurant_addons" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
        2 => 'delete'
      ),
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter',
        2 => 'create',
      ),
    ),
    'creationview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'restaurant_id',
          'label' => 'Shop',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => true,
          ),
          'remoteComplete' => true,
        ),
        1 => 
        array (
          'name' => 'restaurant_category_id',
          'label' => 'Shop Category',
          'targetEntity' => 'restaurant_categories',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => true,
          ),
          'remoteComplete' => true,
        ),
        2 => 
        array (
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        4 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        3 => 
        array (
          'name' => 'is_multiple',
          'label' => 'Multiple Selection? ',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        5 => 
        array (
          'name' => 'restaurant_addon_item',
          'label' => 'Shop Addon Item',
          'template' => '<addon-item-basket entry="entry" entity="entity" action="show"></addon-item-basket>'
        ),
      ),
      'title' => 'Create New Restaurant Addon',
    ),
    'editionview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'restaurant_id',
          'label' => 'Shop',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => true,
          ),
          'remoteComplete' => true,
        ),
        1 => 
        array (
          'name' => 'restaurant_category_id',
          'label' => 'Shop Category',
          'targetEntity' => 'restaurant_categories',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => true,
          ),
          'remoteComplete' => true,
        ),
        2 => 
        array (
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        4 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        3 => 
        array (
          'name' => 'is_multiple',
          'label' => 'Multiple Selection?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        5 => 
        array (
          'name' => 'restaurant_addon_item',
          'label' => 'Shop Addon Item',
          'template' => '<addon-item-basket entry="entry" entity="entity" action="show"></addon-item-basket>'
        ),
      ),
       'title' => 'Edit Restaurant Addon',
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        1 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        2 => 
        array (
          'name' => 'restaurant_category.name',
          'label' => 'Category'
        ),
        3 => 
        array (
          'name' => 'name',
          'label' => 'Name',
        ),
        4 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        4 => 
        array (
          'name' => 'restaurant_addon_item',
          'label' => 'Shop Addon Item',
          'template' => '<addon-item-basket entry="entry" entity="entity" type="show"></addon-item-basket>'
        ),
        5 => array (
            'name' => 'created_at',
            'label' => 'Created On',
        ),
      ),
    ),
  ),
  'restaurants' => 
  array (
    'listview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'label' => 'ID',
          'isDetailLink' => true,
        ),
        1 => array (
            'name' => 'image',
            'lable' => 'Image',
            'template' => '<display-image entry="entry" type="Restaurant" entity="entity"></display-image>'
        ),
        2 => 
        array (
          'name' => 'name',
          'label' => 'Shop',
        ),
        3 => 
        array (
          'name' => 'contact_name',
          'label' => 'Contact Person',
        ),
        4 => 
        array (
          'name' => 'contact_phone',
          'label' => 'Contact Phone',
        ),
        5 => 
        array (
          'name' => 'total_orders',
          'label' => 'Total Orders',
        ),
        6 => 
        array (
          'name' => 'total_revenue',
          'label' => 'Total Revenue',
        ),
        7 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        8 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On',
        ),
      ),
      'title' => 'Shops',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'edit',
        1 => 'show',
        2 => 'delete'
      ),
      'filters' => 
      array (
        0 => 
        array (
          'name' => 'q',
          'pinned' => true,
          'label' => 'Search',
          'type' => 'template',
          'template' => '',
        ),
        2 => 
        array (
          'name' => 'id',
          'label' => 'Shops',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        3 => 
        array (
          'name' => 'parent_id',
          'label' => 'Shop Branches',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
          'permanentFilters' => array (
            'parent_id' => null
          )
        ),
        1 => 
        array (
          'name' => 'is_active',
          'type' => 'choice',
          'label' => 'Active',
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        )
      ),
      'permanentFilters' => '',
      'batchActions' => array (
        0 => '<batch-actions resource="restaurants" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
        1 => '<batch-actions resource="restaurants" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
        2 => 'delete'
      ),
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter',
        2 => 'create',
      ),
    ),
    'creationview' => 
    array (
      'fields' => 
      array (
        1 => 
        array (
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        2 => array (
          'name' => 'cuisine',
          'label' => 'Speciality',
          'targetEntity' => 'cuisines',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference_many',
          'remoteComplete' => true
        ),
        3 => 
        array (
          'name' => 'email',
          'label' => 'Email',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        4 => array (
          'name' => 'mobile_code_country_id',
          'label' => 'Mobile Code',
          'targetEntity' => 'countries',
          'targetField' => 'phone',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'validation' => 
          array (
            'required' => true,
          ),
          'type' => 'reference',
          'remoteComplete' => true
        ),          
        5 => 
        array (
          'name' => 'mobile',
          'label' => 'Mobile',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),      
        6 => 
        array (
          'name' => 'image.attachment',
          'label' => 'Image',
          'type' => 'file',
          'uploadInformation' => array (
            'url' => 'api/v1/attachments',
            'apifilename' => 'id',
            'multiple' => false
          ),
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        7 => 
        array (
          'name' => 'fax',
          'label' => 'Fax',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        8 => 
        array (
          'name' => 'contact_name',
          'label' => 'Contact Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        9 => 
        array (
          'name' => 'contact_phone',
          'label' => 'Contact Phone',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        10 => 
        array (
          'name' => 'website',
          'label' => 'Website',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        11 => 
        array (
          'name' => 'location',
          'label' => 'Location',
          'template' => '<google-places entry="entry" entity="entity"></google-places>',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        12 => 
        array (
          'name' => 'address',
          'label' => 'Address',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        13 => 
        array (
          'name' => 'address1',
          'label' => 'Area',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        14 => 
        array (
          'name' => 'city.name',
          'label' => 'City',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        15 => 
        array (
          'name' => 'state.name',
          'label' => 'State',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        16 => 
        array (
          'name' => 'country.iso2',
          'label' => 'Country',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        17 => 
        array (
          'name' => 'zip_code',
          'label' => 'Postcode / Zip Code',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        18 => 
        array (
          'name' => 'latitude',
          'label' => 'Latitude',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        19 => 
        array (
          'name' => 'longitude',
          'label' => 'Longitude',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        20 => 
        array (
          'name' => 'sales_tax',
          'label' => 'Sales Tax',
          'template' => '<div class="input-group"><input type="number" ng-model="value" id="sales_tax" pattern="[0-9]+" min="1" max="100" name="sales_tax" class="form-control" required></input><span class="input-group-addon">%</span></div>',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        21 => 
        array (
          'name' => 'minimum_order_for_booking',
          'label' => 'Minimum Order For Booking',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        22 => 
        array (
          'name' => 'estimated_time_to_delivery',
          'label' => 'Estimated Time To Delivery (In mins)',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        23 => 
        array (
          'name' => 'delivery_charge',
          'label' => 'Delivery Charge',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        24 => 
        array (
          'name' => 'delivery_miles',
          'label' => 'Delivery Miles',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        25 => 
        array (
          'name' => 'is_allow_users_to_door_delivery_order',
          'label' => 'Allow Users To Door Delivery Order?',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        26 => 
        array (
          'name' => 'is_allow_users_to_pickup_order',
          'label' => 'Allow Users To Pickup Order?',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        27 => 
        array (
          'name' => 'is_allow_users_to_preorder',
          'label' => 'Allow Users To Preorder?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        28 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        29 => 
        array (
          'name' => 'restaurant_photos',
          'label' => 'Shop Photos',
          'type' => 'file',
          'uploadInformation' => array (
            'url' => 'api/v1/attachments',
            'apifilename' => 'id',
            'multiple' => true
          ),
          'validation' => 
          array (
            'required' => false,
          ),
        ),
      ),
      'title' => 'Create New Restaurants'
    ),
    'editionview' => array (
      'fields' => array (
        1 => array (
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        2 => array (
          'name' => 'cuisine',
          'label' => 'Cuisine',
          'targetEntity' => 'cuisines',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference_many',
          'remoteComplete' => true
        ),
        3 => array (
          'name' => 'mobile_code_country_id',
          'label' => 'Mobile Code',
          'targetEntity' => 'countries',
          'targetField' => 'phone',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'validation' => 
          array (
            'required' => true,
          ),
          'type' => 'reference',
          'remoteComplete' => true
        ),           
        4 => array (
          'name' => 'mobile',
          'label' => 'Mobile',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        5 => array (
          'name' => 'image.attachment',
          'label' => 'Image',
          'type' => 'file',
          'uploadInformation' => array (
            'url' => 'api/v1/attachments',
            'apifilename' => 'id',
            'multiple' => false
          ),
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        6 => array (
          'name' => 'fax',
          'label' => 'Fax',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        7 => 
        array (
          'name' => 'contact_name',
          'label' => 'Contact Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        8 => 
        array (
          'name' => 'contact_phone',
          'label' => 'Contact Phone',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        9 => 
        array (
          'name' => 'website',
          'label' => 'Website',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        10 => 
        array (
          'name' => 'location',
          'label' => 'Location',
          'template' => '<google-places entry="entry" entity="entity"></google-places>',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        11 => 
        array (
          'name' => 'address',
          'label' => 'Address',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        12 => 
        array (
          'name' => 'address1',
          'label' => 'Area',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        13 => 
        array (
          'name' => 'city.name',
          'label' => 'City',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        14 => 
        array (
          'name' => 'state.name',
          'label' => 'State',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        15 => 
        array (
          'name' => 'country.iso2',
          'label' => 'Country',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        16 => 
        array (
          'name' => 'zip_code',
          'label' => 'Zip Code',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        17 => 
        array (
          'name' => 'latitude',
          'label' => 'Latitude',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        18 => 
        array (
          'name' => 'longitude',
          'label' => 'Longitude',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        19 => 
        array (
          'name' => 'sales_tax',
          'label' => 'Sales Tax',
          'template' => '<div class="input-group"><input type="text" ng-model="value" id="sales_tax" pattern="[0-9]+" name="sales_tax" class="form-control" required></input><span class="input-group-addon">%</span></div>',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        20 => 
        array (
          'name' => 'minimum_order_for_booking',
          'label' => 'Minimum Order For Booking',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        21 => 
        array (
          'name' => 'estimated_time_to_delivery',
          'label' => 'Estimated Time To Delivery (In mins)',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        22 => 
        array (
          'name' => 'delivery_charge',
          'label' => 'Delivery Charge',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        23 => 
        array (
          'name' => 'delivery_miles',
          'label' => 'Delivery Miles',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
            'pattern' => '[0-9]+'
          ),
        ),
        24 => 
        array (
          'name' => 'is_allow_users_to_door_delivery_order',
          'label' => 'Allow Users To Door Delivery Order?',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        25 => 
        array (
          'name' => 'is_allow_users_to_pickup_order',
          'label' => 'Allow Users To Pickup Order?',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        26 => 
        array (
          'name' => 'is_allow_users_to_preorder',
          'label' => 'Allow Users To Preorder?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        27 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        28 => 
        array (
          'name' => 'restaurant_photos',
          'label' => 'Shop Photos',
          'type' => 'file',
          'uploadInformation' => array (
            'url' => 'api/v1/attachments',
            'apifilename' => 'id',
            'multiple' => true
          ),
          'validation' => 
          array (
            'required' => false,
          ),
        )
      ),
      'title' => 'Edit Shop'
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        1 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'mobile',
          'label' => 'Mobile'
        ),
        4 => 
        array (
          'name' => 'fax',
          'label' => 'Fax'
        ),
        5 => 
        array (
          'name' => 'contact_name',
          'label' => 'Contact Person'
        ),
        6 => 
        array (
          'name' => 'contact_phone',
          'label' => 'Contact Phone'
        ),
        7 => 
        array (
          'name' => 'website',
          'label' => 'Website'
        ),
        8 => 
        array (
          'name' => 'address',
          'label' => 'Address'
        ),
        9 => 
        array (
          'name' => 'address1',
          'label' => 'Area'
        ),
        10 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        11 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        12 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        13 => 
        array (
          'name' => 'latitude',
          'label' => 'Latitude'
        ),
        14 => 
        array (
          'name' => 'longitude',
          'label' => 'Longitude'
        ),
        15 => 
        array (
          'name' => 'zip_code',
          'label' => 'Zip Code'
        ),
        16 => 
        array (
          'name' => 'sales_tax',
          'label' => 'Sales Tax'
        ),
        17 => 
        array (
          'name' => 'minimum_order_for_booking',
          'label' => 'Minimum Order For Booking'
        ),
        18 => 
        array (
          'name' => 'estimated_time_to_delivery',
          'label' => 'Estimated Time To Delivery (In mins)'
        ),
        19 => 
        array (
          'name' => 'delivery_charge',
          'label' => 'Delivery Charge'
        ),
        20 => 
        array (
          'name' => 'delivery_miles',
          'label' => 'Delivery Miles'
        ),
        21 => 
        array (
          'name' => 'is_allow_users_to_door_delivery_order',
          'label' => 'Allow Users To Door Delivery Order?',
          'type' => 'boolean'
        ),
        22 => 
        array (
          'name' => 'is_allow_users_to_pickup_order',
          'label' => 'Allow Users To Pickup Order?',
          'type' => 'boolean'
        ),        
        23 => 
        array (
          'name' => 'is_allow_users_to_preorder',
          'label' => 'Allow Users To Preorder?',
          'type' => 'boolean'
        ),
        24 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        25 => array (
            'name' => 'created_at',
            'label' => 'Created On',
        )
      )
    )
  )
);
global $authUser;
if (!empty($authUser) && $authUser['role_id'] != \Constants\ConstUserTypes::ADMIN) {
  $tables['restaurants']['listview']['listActions'] = array (
    0 => 'show',
    1 => 'edit',
    2 => 'delete',
    3 => '<a href="#/restaurant/timing?restaurant={{entry.values.id}}"><button type="button" class="glyphicon glyphicon-time btn btn-primary editable-table-button btn-xs">Timing</button></a>',
  );
  unset($tables['restaurants']['listview']['batchActions']);
}
if ($authUser['role_id'] != \Constants\ConstUserTypes::ADMIN) {
  unset($menus['Listing']);
  unset($menus['Performance']);
}