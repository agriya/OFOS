<?php
global $authUser;
$menus = array (
    'Users' => array (
        'title' => 'Users',
        'icon_template' => '<span class="fa fa-users fa-fw"></span>',
        'child_sub_menu' => array (
            'users' => array (
                'title' => 'Users',
                'icon_template' => '<span class="fa fa-user fa-fw"></span>',
                'suborder' => 1
            )
        ),
        'order' => 3
    ),
    'Master' => array (
        'title' => 'Master',
        'icon_template' => '<span class="fa fa-dashboard fa-fw"></span>',
        'child_sub_menu' => array (
            'cities' => array (
                'title' => 'Cities',
                'icon_template' => '<span class="fa fa-flag fa-fw"></span>',
                'suborder' => 1
            ),
            'states' => array (
                'title' => 'States',
                'icon_template' => '<span class="fa fa-globe fa-fw"></span>',
                'suborder' => 2
            ),
            'countries' => array (
                'title' => 'Countries',
                'icon_template' => '<span class="fa fa-globe fa-fw"></span>',
                'suborder' => 3
            ),
            'pages' => array (
                'title' => 'Pages',
                'icon_template' => '<span class="fa fa-table fa-fw"></span>',
                'suborder' => 4
            ),
            'languages' => array (
                'title' => 'Languages',
                'icon_template' => '<span class="fa fa-language fa-fw"></span>',
                'suborder' => 5
            ),
            'contacts' => array (
                'title' => 'Contacts',
                'icon_template' => '<span class="fa fa-file-text-o fa-fw"></span>',
                'suborder' => 6
            ),
            'providers' => array (
                'title' => 'Providers',
                'icon_template' => '<span class="fa fa-user fa-fw"></span>',
                'suborder' => 7
            ),
            'email_templates' => array (
                'title' => 'Email Templates',
                'icon_template' => '<span class="fa fa-inbox fa-fw"></span>',
                'suborder' => 8
            )
        ),
        'order' => 8
    ),
    'Settings' => array (
        'title' => 'Settings',
        'icon_template' => '<span class="fa fa-cog fa-fw"></span>',
        'child_sub_menu' => array (
            'setting_categories' => array (
                'title' => 'Site Settings',
                'icon_template' => '<span class="fa fa-cog"></span>',
                'suborder' => 1
            )
        ),
        'order' => 7
    ),
    'Plugins' => array (
        'title' => 'Plugins',
        'icon_template' => '<span class="fa fa-th-large"></span>',
        'child_sub_menu' => array (
            'plugins' => array (
                'title' => 'Plugins',
                'icon_template' => '<span class="fa fa-th-large"></span>',
                 'link' => "/plugins",
                'suborder' => 1
            )
        ),
        'order' => 1
    ),
    'Listing' => array (
        'title' => 'Listing',
        'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
        'child_sub_menu' => array (
            'cuisines' =>  array (
                'title' => 'Specialities',
                'icon_template' => '<span class="fa fa-cutlery fa-fw"></span>',
                'suborder' => 5
            ),
        ),
        'order' => 5
    ),
    'Performance' => array (
        'title' => 'Performance',
        'icon_template' => '<span class="fa fa-home fa-fw"></span>',
        'child_sub_menu' => array (
            'dashboard' => 
            array (
                'title' => 'Dashboard',
                'icon_template' => '<span class="fa fa-clock-o fa-fw"></span>',
                'suborder' => 1,
                'link' => '/dashboard'
            ),
        ),
        'order' => 2
    )
);
$tables = array (
    'users' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'template' => '<a href="#/users/show/{{entry.values.id}}">{{entry.values.id}}</a>',
                ),
                
                1 => array (
                    'name' => 'username',
                    'label' => 'Username',
                ),
                2 => array (
                    'name' => 'email',
                    'label' => 'Email',
                ),
                3 => array (
                    'name' => 'role.name',
                    'label' => 'Role',
                ),
                4 => array (
                    'name' => 'mobile',
                    'label' => 'Mobile',
                ),
                8 => array (
                    'name' => 'total_orders',
                    'label' => 'Total Orders',
                ),
                9 => array (
                    'name' => 'total_reviews',
                    'label' => 'Total Reviews',
                ),
                10 => array (
                    'name' => 'available_wallet_amount',
                    'label' => 'Available Balance'
                ),
                11 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean'
                ),
                12 => array (
                    'name' => 'last_logged_in_time',
                    'label' => 'Last Visit'
                ),
                13 => 
                array (
                'name' => 'created_at',
                'label' => 'Created On',
                )
            ),
            'title' => 'Users',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
                3 => '<change-password entry="entry" entity="entity" size="sm" label="Change Password" ></change-password>',
            ),
            'batchActions' => array (
                0 => '<batch-actions resource="users" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
                1 => '<batch-actions resource="users" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
                2 => 'delete'
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>',
                ),
                1 => array (
                    'name' => 'role_id',
                    'label' => 'Role',
                    'targetEntity' => 'roles',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                ),
                2 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
                3 => array (
                    'name' => 'provider_id',
                    'label' => 'Provides',
                    'targetEntity' => 'providers',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                ),
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
                2 => 'create'
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'role_id',
                    'label' => 'Role',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Admin',
                            'value' => 1,
                        ),
                        1 => array (
                            'label' => 'User',
                            'value' => 2,
                        ),
                    ),
                ),
                1 => array (
                    'name' => 'username',
                    'label' => 'Username',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                3 => array (
                    'name' => 'mobile',
                    'label' => 'Mobile',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                4 => array (
                    'name' => 'mobile_code',
                    'label' => 'Mobile Code',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                5 => array (
                    'name' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                6 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    )
                )
            ),
            'title' => 'Add User',
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'role_id',
                    'label' => 'Role',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Admin',
                            'value' => 1,
                        ),
                        1 => array (
                            'label' => 'User',
                            'value' => 2,
                        ),
                    ),
                ),
                1 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                3 => array (
                    'name' => 'gender_id',
                    'label' => 'Gender',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Male',
                            'value' => 0,
                        ),
                        1 => array (
                            'label' => 'Female',
                            'value' => 1,
                        ),
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
                6 => array (
                    'name' => 'location',
                    'label' => 'Location',
                    'template' => '<google-places entry="entry" entity="entity"></google-places>',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                7 => array (
                    'name' => 'address',
                    'label' => 'Address',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                8 => array (
                    'name' => 'address1',
                    'label' => 'Area',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                9 => array (
                    'name' => 'city.name',
                    'label' => 'City',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                10 => array (
                    'name' => 'state.name',
                    'label' => 'State',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                11 => array (
                    'name' => 'country.iso2',
                    'label' => 'Country',
                    'validation' => array (
                        'required' => true,
                    )
                ),
                12 => array (
                    'name' => 'zip_code',
                    'label' => 'Zip Code',
                    'validation' => array (
                        'required' => true,
                    )
                ),
                13 => array (
                    'name' => 'latitude',
                    'label' => 'Latitude',
                    'validation' => array (
                        'required' => true,
                    )
                ),
                14 => array (
                    'name' => 'longitude',
                    'label' => 'Longitude',
                    'validation' => array (
                        'required' => true,
                    )
                ),
                15 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
                16 => array (
                    'name' => 'is_agree_terms_conditions',
                    'label' => 'Agree Terms Condition?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
                17 => array (
                    'name' => 'is_subscribed',
                    'label' => 'Subscribed?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    )
                )
            ),
            'title' => 'Edit User'
        ),
        'showview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'role.name',
                    'label' => 'Role',
                ),
                2 => array (
                    'name' => 'username',
                    'label' => 'Username',
                ),
                3 => array (
                    'name' => 'email',
                    'label' => 'Email',
                ),
                4 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                ),
                5 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                ),
                6 => array (
                    'name' => 'gender_id',
                    'label' => 'Gender',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Male',
                            'value' => 0,
                        ),
                        1 => array (
                            'label' => 'Female',
                            'value' => 1,
                        ),
                    ),
                ),
                7 => array (
                    'name' => 'address',
                    'label' => 'Address',
                ),
                8 => array (
                    'name' => 'address1',
                    'label' => 'Area',
                ),
                9 => array (
                    'name' => 'city.name',
                    'label' => 'City',
                ),
                10 => array (
                    'name' => 'state.name',
                    'label' => 'State',
                ),
                11 => array (
                    'name' => 'country.name',
                    'label' => 'Country',
                ),
                12 => array (
                    'name' => 'zip_code',
                    'label' => 'Zip Code',
                ),
                13 => array (
                    'name' => 'latitude',
                    'label' => 'Latitude',
                ),
                14 => array (
                    'name' => 'longitude',
                    'label' => 'Longitude',
                ),
                15 => array (
                    'name' => 'mobile',
                    'label' => 'Mobile',
                ),
                16 => array (
                    'name' => 'is_subscribed',
                    'label' => 'Subscribed?',
                    'type' => 'boolean',
                ),
                17 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ),
                18 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            )
        )
    ),
    'settings' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ),
                1 => array (
                    'name' => 'created',
                    'label' => 'Created On',
                ),
                2 => array (
                    'name' => 'modified',
                    'label' => 'Modified',
                ),
            ),
        ),
        array (
            'title' => 'Settings',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ),
                1 => array (
                    'name' => 'setting_category_id',
                    'label' => 'Setting Category',
                    'targetEntity' => 'setting_categories',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                ),
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => '',
            ),
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'label',
                    'label' => 'Name',
                    'editable' => false
                ),
                2 => array (
                    'name' => 'value',
                    'label' => 'Value',
                    'template' => '<input-type entry="entry" entity="entity"></input-type>',
                ),
                1 => array (
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'wysiwyg',
                    'editable' => false
                ),
            ),
            'title' => 'Edit Setting',
            'actions' => array ()
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'value',
                    'label' => 'Value',
                    'template' => '',
                ),
                2 => array (
                    'name' => 'description',
                    'label' => 'Description',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
            ),
        ),
    ),
    'setting_categories' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true
                ),
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'wysiwyg'
                ),
                2 => array (
                    'name' => 'description',
                    'label' => 'Description',
                    'map' => array (
                        0 => 'truncate',
                    ),
                ),
            ),
            'title' => 'Site Settings',
            'perPage' => '25',
            'sortField' => '',
            'sortDir' => 'ASC',
            'infinitePagination' => false,
            'listActions' => array (
                0 => '<ma-show-button entry="entry" entity="entity" size="sm" label="Config" ></ma-show-button>',
            ),
            'filters' => array (
            ),
            'permanentFilters' => '',
            'actions' => array (
                'setting_category_action_tpl' => '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button><ma-export-to-csv-button entry="entry" entity="entity" size="sm" datastore="::datastore"></ma-export-to-csv-button>',
                'settings_category_edit_template' => '<ma-list-button entry="entry" entity="entity" size="sm"></ma-list-button>',
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'description',
                    'label' => 'Description',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
            ),
        ),
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string'
                ),
                1 => array (
                    'name' => 'description',
                    'label' => 'Description'
                ),
                2 => array (
                    'name' => 'setting_category_id',
                    'label' => 'Related Settings',
                    'targetEntity' => 'settings',
                    'targetReferenceField' => 'setting_category_id',
                    'targetFields' => array (
                        0 => array (
                            'name' => 'label',
                            'label' => 'Name',
                        ),
                        1 => array (
                            'name' => 'value',
                            'label' => 'Value',
                        ),
                    ),
                    'listActions' => array (
                        0 => 'edit',
                    ),
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'referenced_list',
                ),
                3 => array (
                    'name' => 'icon',
                    'label' => '',
                    'template' => '<add-sync entry="entry" entity="entity" size="sm" label="Synchronize" ></add-sync>'
                ),
                4 => array (
                    'name' => 'icon',
                    'label' => '',
                    'type' => 'template',
                    'template' => '<mooc-sync entry="entry" entity="entity" size="sm" label="Synchronize" ></mooc-sync>'
                ),
            ),
            'actions' => array ('list')
        ),
    ),
    'cities' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                1 => array (
                    'name' => 'state.name',
                    'label' => 'State',
                ),
                2 => array (
                    'name' => 'country.name',
                    'label' => 'Country',
                ),
                3 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ),
                4 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
                
            ),
            'title' => 'Cities',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ),
            'batchActions' => array (
                0 => 'delete',
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ),
                1 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true
                        ),
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false
                        ),
                    ),
                ),
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                1 => array (
                    'name' => 'state_id',
                    'label' => 'State',
                    'targetEntity' => 'states',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                4 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => false,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'title' => 'Add City',
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                1 => array (
                    'name' => 'state_id',
                    'label' => 'State',
                    'targetEntity' => 'states',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                4 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array (
                        'required' => false,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'title' => 'Edit City',
        ),
        'showview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'country.name',
                    'label' => 'Country Name',
                ),
                2 => array (
                    'name' => 'state.name',
                    'label' => 'State',
                ),
                3 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                5 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ),
                6 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
                
            ),
        ),
    ),
    'states' => array (
        'listview' => array (
            'fields' => array (
                
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                1 => array (
                    'name' => 'country.name',
                    'label' => 'Country',
                ),
                2 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ),
                3 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
            'title' => 'States',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ),
            'batchActions' => array (
                0 => 'delete',
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ),
                1 => array (
                    'name' => 'is_active',
                    'type' => 'choice',
                    'label' => 'Active?',
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                3 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'title' => 'Add State',
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                3 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'title' => 'Edit State',
        ),
        'showview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'country.name',
                    'label' => 'Country',
                ),
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                4 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ),
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
        ),
    ),
    'countries' => array (
        'listview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                3 => array (
                    'name' => 'iso2',
                    'label' => 'Iso2',
                ),
                4 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                ),
                5 => 
                array (
                'name' => 'created_at',
                'label' => 'Created On',
                )
            ),
            'title' => 'Countries',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ),
            'batchActions' => array (
                0 => 'delete',
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ),
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'create'
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                3 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                7 => array (
                    'name' => 'continent',
                    'label' => 'Continent',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                9 => array (
                    'name' => 'currency',
                    'label' => 'Currency',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                10 => array (
                    'name' => 'currencyname',
                    'label' => 'Currency Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                11 => array (
                    'name' => 'phone',
                    'label' => 'Phone',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                12 => array (
                    'name' => 'postalcodeformat',
                    'label' => 'Postal Code Format',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                13 => array (
                    'name' => 'postalcoderegex',
                    'label' => 'Postal Code Regex',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                14 => array (
                    'name' => 'languages',
                    'label' => 'Language',
                    'validation' => array (
                        'required' => false,
                    ),
                )
            ),
            'title' => 'Add Country',
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                3 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                7 => array (
                    'name' => 'continent',
                    'label' => 'Continent',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                9 => array (
                    'name' => 'currency',
                    'label' => 'Currency',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                10 => array (
                    'name' => 'currencyname',
                    'label' => 'Currency Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                11 => array (
                    'name' => 'phone',
                    'label' => 'Phone',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                12 => array (
                    'name' => 'postalcodeformat',
                    'label' => 'Postal Code Format',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                13 => array (
                    'name' => 'postalcoderegex',
                    'label' => 'Postal Code Regex',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                14 => array (
                    'name' => 'languages',
                    'label' => 'Language',
                    'validation' => array (
                        'required' => false,
                    ),
                )
            ),            'title' => 'Edit Country',
        ),
        'showview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'iso2',
                    'label' => 'Iso Alpha2',
                ),
                2 => array (
                    'name' => 'iso3',
                    'label' => 'Iso Alpha3',
                ),
                5 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                9 => array (
                    'name' => 'continent',
                    'label' => 'Continent',
                ),
                11 => array (
                    'name' => 'currency',
                    'label' => 'Currency',
                ),
                12 => array (
                    'name' => 'currencyname',
                    'label' => 'Currency Name',
                ),
                13 => array (
                    'name' => 'phone',
                    'label' => 'Phone',
                ),
                14 => array (
                    'name' => 'postalcodeformat',
                    'label' => 'Postal Code Format',
                ),
                15 => array (
                    'name' => 'postalcoderegex',
                    'label' => 'Postal Code Regex',
                ),
                16 => array (
                    'name' => 'languages',
                    'label' => 'Languages',
                ),
                18 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                )
            ),
        ),
    ),
    'pages' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'title',
                    'label' => 'Title',
                ),
                1 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ),
                2 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
            'title' => 'Pages',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ),
            'batchActions' => array (
                0 => 'delete',
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ),
                1 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false
                        ),
                    ),
                ),
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'content',
                    'label' => 'Content',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'meta_keywords',
                    'label' => 'Meta Keywords',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                3 => array (
                    'name' => 'meta_description',
                    'label' => 'Meta Description',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                4 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'title' => 'Add Page',
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'content',
                    'label' => 'Content',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'meta_keywords',
                    'label' => 'Meta Keywords',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                3 => array (
                    'name' => 'meta_description',
                    'label' => 'Meta Description',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                4 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'title' => 'Edit Page'
        ),
        'showview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'title',
                    'label' => 'Title',
                ),
                2 => array (
                    'name' => 'content',
                    'label' => 'Content',
                    'type' => 'wysiwyg',
                ),
                3 => array (
                    'name' => 'meta_keywords',
                    'label' => 'Meta Keywords',
                ),
                4 => array (
                    'name' => 'meta_description',
                    'label' => 'Meta Description',
                ),
                5 => array (
                    'name' => 'is_default',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ),
                6 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
        ),
    ),
    'languages' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                1 => array (
                    'name' => 'iso2',
                    'label' => 'Iso2',
                ),
                2 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                ),
                3 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ),
                4 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
            'title' => 'Languages',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ),
            'batchActions' => array (
                0 => 'delete',
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ),
                1 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ),
        ),
        'showview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                2 => array (
                    'name' => 'iso2',
                    'label' => 'Iso2',
                ),
                3 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                ),
                4 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ),
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'type' => 'text',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'type' => 'text',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                3 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array (
                        'required' => false,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'title' => 'Add Language',
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'type' => 'text',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'type' => 'text',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                3 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array (
                        'required' => false,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'title' => 'Edit Language',
        ),
    ),
    'email_templates' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                2 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                ),
                3 => 
                array (
                'name' => 'created_at',
                'label' => 'Created On',
                )
            ),
            'title' => 'Email Templates',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'filter',
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'from_email',
                    'label' => 'From',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'reply_to_email',
                    'label' => 'Reply To',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                3 => array (
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                4 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                5 => array (
                    'name' => 'text_email_content',
                    'label' => 'Text Email Content',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                6 => array (
                    'name' => 'html_email_content',
                    'label' => 'Html Email Content',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                7 => array (
                    'name' => 'notification_content',
                    'label' => 'Notification Content',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                8 => array (
                    'name' => 'email_variables',
                    'label' => 'Email Variables',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                9 => array (
                    'name' => 'display_name',
                    'label' => 'Display Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
            ),
            'title' => ' Add Email Template',
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'from_email',
                    'label' => 'From',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                1 => array (
                    'name' => 'reply_to_email',
                    'label' => 'Reply To',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                4 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                5 => array (
                    'name' => 'text_email_content',
                    'label' => 'Text Email Content',
                    'type' => 'text',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                6 => array (
                    'name' => 'html_email_content',
                    'label' => 'Html Email Content',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                8 => array (
                    'name' => 'is_admin_email',
                    'label' => 'Admin Email',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    )
                ),
                9 => array (
                    'name' => 'email_variables',
                    'label' => 'Email Variables',
                    'editable' => false,
                    'validation' => array (
                        'required' => true,
                    ),
                ),
            ),
            'title' => 'Edit Email Template',
            'actions' => array ('list')
        ),
    ),
    'contacts' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                ),
                1 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                ),
                2 => array (
                    'name' => 'email',
                    'label' => 'Email',
                ),
                3 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                ),
                4 => array (
                    'name' => 'message',
                    'label' => 'Message',
                    'type' => 'wysiwyg',
                    'map' => array (
                        0 => 'truncate',
                    ),
                ),
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
            'title' => 'Contacts',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'show',
                1 => 'delete',
            ),
            'batchActions' => array (
                0 => 'delete',
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch'
            ),
        ),
        'showview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                ),
                2 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                ),
                3 => array (
                    'name' => 'email',
                    'label' => 'Email',
                ),
                4 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                ),
                5 => array (
                    'name' => 'message',
                    'label' => 'Message',
                    'type' => 'wysiwyg',
                ),
                6 => array (
                    'name' => 'phone',
                    'label' => 'Mobile',
                ),
                7 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
                 
            ),
        ),
    ),
    'providers' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                2 => array (
                    'name' => 'secret_key',
                    'label' => 'Secret Key',
                ),
                1 => array (
                    'name' => 'api_key',
                    'label' => 'Client ID',
                ),
                3 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ),
                5 => 
                array (
                'name' => 'created_at',
                'label' => 'Created On',
                )
            ),
            'title' => 'Providers',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>',
                ),
                1 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'permanentFilters' => '',
            'batchActions' => array (
                0 => '<batch-actions resource="providers" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
                1 => '<batch-actions resource="providers" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
            ),
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                1 => array (
                    'name' => 'slug',
                    'label' => 'Slug',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'secret_key',
                    'label' => 'Secret Key',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                3 => array (
                    'name' => 'api_key',
                    'label' => 'Api Key',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                4 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                5 => array (
                    'name' => 'button_class',
                    'label' => 'Button Class',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                6 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
                7 => array (
                    'name' => 'position',
                    'label' => 'Position',
                    'type' => 'number',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
            ),
            'title' => 'Add Provider',
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                1 => array (
                    'name' => 'secret_key',
                    'label' => 'Secret Key',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                2 => array (
                    'name' => 'api_key',
                    'label' => 'Client ID',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                3 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array (
                        'required' => true,
                    ),
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ),
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ),
                    ),
                ),
            ),
            'title' => 'Edit Provider',
            'actions' => array ( 
                'list'
            )
        ),
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ),
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ),
                2 => array (
                    'name' => 'slug',
                    'label' => 'Slug',
                ),
                3 => array (
                    'name' => 'secret_key',
                    'label' => 'Secret Key',
                ),
                4 => array (
                    'name' => 'api_key',
                    'label' => 'Api Key',
                ),
                5 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                ),
                6 => array (
                    'name' => 'button_class',
                    'label' => 'Button Class',
                ),
                7 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                ),
                8 => array (
                    'name' => 'position',
                    'label' => 'Position',
                ),
                9 => 
                array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                )
            ),
            'title' => 'Providers',
        ),
    ),
  'cuisines' => 
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
          'label' => 'Speciality',
        ),
        2 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        3 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On',
        ),
      ),
      'title' => 'Specialities',
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
      ),
      'permanentFilters' => '',
      'batchActions' => array (
        0 => '<batch-actions resource="cuisines" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
        1 => '<batch-actions resource="cuisines" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
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
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        1 => 
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
      ),
      'title' => 'Add Speciality'
    ),
    'editionview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        1 => 
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
      ),
      'title' => 'Edit Cuisine'
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        1 => 
        array (
          'name' => 'name',
          'label' => 'Cuisine',
        ),
        2 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        3 => 
        array (
            'name' => 'created_at',
            'label' => 'Created On',
        )
      ),
    ),
  ),  
);
$dashboard = array (
    'users' => array (
        'addCollection' => array (
            'fields' => array (
                2 => array (
                    'name' => 'role.name',
                    'label' => 'Role'
                ),
                0 => array (
                    'name' => 'username',
                    'label' => 'Username'
                ),
                1 => array (
                    'name' => 'email',
                    'label' => 'Email'
                )
            ),
            'title' => 'Recent Registered Customers',
            'name' => 'recent_users',
            'perPage' => 10,
            'order' => 2,
            'template' => '<div class="col-lg-6"><div class="panel"><ma-dashboard-panel collection="dashboardController.collections.recent_users" entries="dashboardController.entries.recent_users" datastore="dashboardController.datastore"> </ma-dashboard-panel></div></div>'
        )
    ),
);
if (isPluginEnabled('Order/Review')) {
    $review_dashboard = array (
        'restaurant_reviews' => array (
            'addCollection' => array (
                'fields' => array (
                    0 => 
                    array (
                    'name' => 'user.username',
                    'label' => 'Customer Name'
                    ),
                    1 => 
                    array (
                    'name' => 'restaurant.name',
                    'label' => 'Shop'
                    ),
                    2 => 
                    array (
                    'name' => 'rating',
                    'label' => 'Rating',
                    'template' => '<star-rating stars="{{entry.values.rating}}"></star-rating>'
                    ),
                    3 => 
                    array (
                    'name' => 'message',
                    'label' => 'Message',
                    ),
                ),
                'title' => 'Recent Reviews',
                'name' => 'recent_reviews',
                'perPage' => 10,
                'order' => 3,
                'template' => '<div class="col-lg-6"><div class="panel"><ma-dashboard-panel collection="dashboardController.collections.recent_reviews" entries="dashboardController.entries.recent_reviews" datastore="dashboardController.datastore"> </ma-dashboard-panel></div></div>'
            )
        ),
    );
    $dashboard = array_merge($dashboard, $review_dashboard);
}
if (isPluginEnabled('Order/Order')) {
    $order_dashboard = array (
      'orders' => array (
          'addCollection' => array (
              'fields' => array (
                  0 => array (
                      'name' => 'id',
                      'label' => 'ID',
                      'isDetailLink' => true 
                  ),
                  1 => array (
                      'name' => 'restaurant.name',
                      'label' => 'Username'
                  ),
                  2 => array (
                      'name' => 'user.username',
                      'label' => 'Username'
                  ),
                  3 => array (
                      'name' => 'is_pickup_or_delivery',
                      'label' => 'Delivery/Pickup',
                      'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
                  ),
                  4 => array (
                      'name' => 'total_price',
                      'label' => 'Amount',
                  ),
                  5 => array (
                      'name' => 'order_status.name',
                      'label' => 'Status'
                  ),
                  6 => array (
                      'name' => 'created_at',
                      'label' => 'Order On',
                  ),
              ),
              'title' => 'Recent Orders',
              'name' => 'recent_orders',
              'perPage' => 10,
              'order' => 1,
              'template' => '<div class="col-lg-12"><div class="panel"><ma-dashboard-panel collection="dashboardController.collections.recent_orders" entries="dashboardController.entries.recent_orders" datastore="dashboardController.datastore"> </ma-dashboard-panel></div></div>'
          )
      )
  );
  $dashboard = array_merge($dashboard, $order_dashboard);
}
if (isPluginEnabled('Order/Order')) {
    $order_menu = array (
        'Payments' => array (
        'title' => 'Payments',
        'icon_template' => '<span class="fa fa-credit-card fa-fw"></span>',
        'child_sub_menu' => array (
            'payment_gateways' => array (
                'title' => 'Payment Gateways',
                'icon_template' => '<span class="fa fa-credit-card fa-fw"></span>',
                'suborder' => 1
            ),
            'transactions' => 
                array (
                    'title' => 'Transactions',
                    'icon_template' => '<span class="fa fa-usd fa-fw"></span>',
                    'suborder' => 2
                ),
            ),
            'order' => 5
        ),
    );
    $menus = array_merge($menus, $order_menu);
}

if ($authUser['role_id'] != \Constants\ConstUserTypes::ADMIN) {
    $tables['cuisines']['listview']['listActions'] = array (
        0 => 'show'
    );
}
if ($authUser['role_id'] == \Constants\ConstUserTypes::RESTAURANT) {
    if (isPluginEnabled('Restaurant/MultiRestaurant') || isPluginEnabled('Restaurant/SingleRestaurant')) {
        $menus = array (
            'Performance' => array (
                'title' => 'Performance',
                'icon_template' => '<span class="fa fa-home fa-fw"></span>',
                'child_sub_menu' => array (
                    'dashboard' => 
                    array (
                        'title' => 'Dashboard',
                        'icon_template' => '<span class="fa fa-clock-o fa-fw"></span>',
                        'suborder' => 1,
                        'link' => '/dashboard'
                    )
                ),
                'order' => 1
            ),
            'Payments' => array (
                'title' => 'Payments',
                'icon_template' => '<span class="fa fa-credit-card fa-fw"></span>',
                'child_sub_menu' => array (
                    'transactions' => 
                        array (
                            'title' => 'Transactions',
                            'icon_template' => '<span class="fa fa-usd fa-fw"></span>',
                            'suborder' => 2
                        ),
                ),
                'order' => 5
            ),
            'Listing' => array (
                'title' => 'Listing',
                'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
                'child_sub_menu' => array (
                    'restaurants' => array (
                        'title' => 'Restaurants',
                        'icon_template' => '<span class="fa fa-cutlery fa-fw"></span>',
                        'suborder' => 1
                    ),
                    'restaurant_timings' => 
                    array (
                        'title' => 'Timing',
                        'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                        'suborder' => 3,
                        'link' => '/restaurant/timing'
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
            ),
        );
    }
    if (isPluginEnabled('Order/OwnDelivery') && $authUser->restaurant->is_delivered_by_own == true) {
        $delivery_person = array (
            'Listing' => array (
                'title' => 'Listing',
                'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_delivery_persons' => 
                    array (
                        'title' => 'Restaurant Delivery Persons',
                        'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
                        'suborder' => 3
                    ),
                ),
                'order' => 4
            ),
            'Order' => array (
                'title' => 'Order',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_delivery_orders' => 
                    array (
                        'title' => 'Restaurant Orders',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 1
                    )
                ),
                'order' => 3
            )
        );
        $menus = merged_menus($menus, $delivery_person);
        if (isPluginEnabled('Order/Supervisor')) {
            $delivery_person = array (
                'Listing' => array (
                    'title' => 'Listing',
                    'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
                    'child_sub_menu' => array (
                        'own_restaurant_restaurant_supervisors' => 
                        array (
                            'title' => 'Restaurant Supervisors',
                            'icon_template' => '<span class="glyphicon glyphicon-user"></span>',
                            'suborder' => 2
                        ),
                    ),
                    'order' => 4
                )
            );
            $menus = merged_menus($menus, $delivery_person);
        }
    } else {
        if (isPluginEnabled('Order/Order')) {
            $order_menu = array (
            'Order' => array (
                'title' => 'Order',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'orders' => 
                    array (
                        'title' => 'Orders',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 2
                    )
                ),
                'order' => 3
            )
        );
        $menus = merged_menus($menus, $order_menu);
        }
    }    
    if (isPluginEnabled('Common/Withdrawal')) {
        $payment = array (
            'Payments' => array (
                'title' => 'Payments',
                'icon_template' => '<span class="glyphicon glyphicon-usd"></span>',
                'child_sub_menu' => array (
                    'user_cash_withdrawals' => array (
                        'title' => 'Withdrawals',
                        'icon_template' => '<span class="fa fa-money fa-fw"></span>',
                        'suborder' => 4
                    ),
                    'money_transfer_accounts' => array (
                        'title' => 'Money Transfer Accounts',
                        'icon_template' => '<span class="glyphicon glyphicon-record"></span>',
                        'suborder' => 5
                    ),
                ),
                'order' => 5
            ),
        );
        $menus = merged_menus($menus, $payment);
    }
    if (isPluginEnabled('Order/Review')) {
        $Performance = array (
            'Performance' => array (
                'title' => 'Performance',
                'icon_template' => '<span class="fa fa-home fa-fw"></span>',
                'child_sub_menu' => array (
                    'restaurant_reviews' => 
                    array (
                        'title' => 'Reviews',
                        'icon_template' => '<span class="fa fa-comments fa-fw"></span>',
                        'suborder' => 2
                    ),
                ),
                'order' => 1
            )
        );
        $menus = merged_menus($menus, $Performance);
    }
}
else if ($authUser['role_id'] == \Constants\ConstUserTypes::DELIVERYPERSON) {
    $menus = array (
        'Performance' => array (
            'title' => 'Performance',
            'icon_template' => '<span class="fa fa-home fa-fw"></span>',
            'child_sub_menu' => array (
                'dashboard' => 
                array (
                    'title' => 'Dashboard',
                    'icon_template' => '<span class="fa fa-clock-o fa-fw"></span>',
                    'suborder' => 1,
                    'link' => '/dashboard'
                ),
            ),
            'order' => 1
        ),
    );
    if (isPluginEnabled('Order/OwnDelivery') && !empty($authUser->restaurant_delivery_person->restaurant_id) && $authUser->restaurant_delivery_person->restaurant->is_delivered_by_own) {
        $order_menu = array (
            'Assigned Orders' => array (
                'title' => 'Assigned Orders',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_assingned_orders' => array (
                        'title' => 'Restaurant Assigned Order',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 1
                    )
                ),
                'order' => 2
            ),
            'Delivered Orders' => array (
                'title' => 'Delivered Orders',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_delivered_orders' => 
                    array (
                        'title' => 'Restaurant Delivered Order',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 3
                    )
                ),
                'order' => 4
            ),
            'Out For Delivery' => array (
                'title' => 'Restaurant Out For Delivery Orders',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_out_for_delivery_orders' => 
                    array (
                        'title' => 'Restaurant Out For Delivery Orders',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 2
                    )
                ),
                'order' => 3
            )
        );
        $menus = merged_menus($menus, $order_menu);
    } else {
        $order_menu = array (
            'Assigned Orders' => array (
                'title' => 'Assigned Orders',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'assingned_orders' => array (
                        'title' => 'Assigned Order',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 2
                    )
                ),
                'order' => 2
            ),
            'Delivered Orders' => array (
                'title' => 'Delivered Orders',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'delivered_orders' => 
                    array (
                        'title' => 'Delivered Order',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 2
                    )
                ),
                'order' => 4
            ),
            'Out For Delivery' => array (
                'title' => 'Out For Delivery Orders',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'out_for_delivery_orders' => 
                    array (
                        'title' => 'Out For Delivery Orders',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 2
                    )
                ),
                'order' => 3
            )
        );
        $menus = merged_menus($menus, $order_menu);
    }
} else if ($authUser['role_id'] == \Constants\ConstUserTypes::SUPERVISOR) {
    $menus = array (
        'Performance' => array (
            'title' => 'Performance',
            'icon_template' => '<span class="fa fa-home fa-fw"></span>',
            'child_sub_menu' => array (
                'dashboard' => 
                array (
                    'title' => 'Dashboard',
                    'icon_template' => '<span class="fa fa-clock-o fa-fw"></span>',
                    'suborder' => 1,
                    'link' => '/dashboard'
                ),
            ),
            'order' => 1
        ),
        
    );
    if (isPluginEnabled('Order/OwnDelivery') && !empty($authUser->restaurant_supervisor->restaurant_id) && $authUser->restaurant_supervisor->restaurant->is_delivered_by_own) {
        $delivery_person = array (
            'Delivery Persons' => array (
                'title' => 'Delivery Persons',
                'icon_template' => '<span class="glyphicon glyphicon-user"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_delivery_persons' => 
                    array (
                        'title' => 'Restaurant Delivery Persons',
                        'icon_template' => '<span class="glyphicon glyphicon-user"></span>',
                        'suborder' => 4
                    ),
                ),
                'order' => 3
            ),
            'Menus' => array (
                'title' => 'Menus',
                'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
                'child_sub_menu' => array (
                    'restaurant_menus' => 
                    array (
                        'title' => 'Restaurant Menus',
                        'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
                        'suborder' => 4,
                        'link' => '/menus'
                    ),
                ),
                'order' => 2
            ),
            'Delivered Orders' => array (
                'title' => 'Delivered Orders',
                'icon_template' => '<span class="glyphicon glyphicon-ok"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_delivered_orders' => 
                    array (
                        'title' => 'Restaurant Delivered Orders',
                        'icon_template' => '<span class="glyphicon glyphicon-ok"></span>',
                        'suborder' => 2,
                    ),
                ),
                'order' => 7
            ),
            'Assigned Orders' => array (
                'title' => 'Assigned Orders',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_assingned_orders' => array (
                        'title' => 'Restaurant Assigned Order',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 2
                    )
                ),
                'order' => 5
            ),            
            'Processing Orders' => array (
                'title' => 'Processing Orders',
                'icon_template' => '<span class="glyphicon glyphicon-cog"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_processing_orders' => 
                    array (
                        'title' => 'Restaurant Processing Orders',
                        'icon_template' => '<span class="glyphicon glyphicon-cog"></span>',
                        'suborder' => 2,
                    ),
                ),
                'order' => 5
            ),
            'Pending Orders' => array (
                'title' => 'Pending Orders',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_pending_orders' => 
                    array (
                        'title' => 'Restaurant Pending Orders',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 2
                    ),
                ),
                'order' => 4
            ),
            'Out For Delivery' => array (
                'title' => 'Restaurant Out For Delivery Orders',
                'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                'child_sub_menu' => array (
                    'own_restaurant_out_for_delivery_orders' => 
                    array (
                        'title' => 'Restaurant Out For Delivery Orders',
                        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                        'suborder' => 6
                    )
                ),
                'order' => 6
            )
        );
        $menus = merged_menus($menus, $delivery_person);
    } else {
        if (isPluginEnabled('Order/OutsourcedDelivery')) {
            $delivery_menu = array (
                'Delivery Persons' => array (
                    'title' => 'Delivery Persons',
                    'icon_template' => '<span class="glyphicon glyphicon-user"></span>',
                    'child_sub_menu' => array (
                        'restaurant_delivery_persons' => 
                        array (
                            'title' => 'Delivery Persons',
                            'icon_template' => '<span class="glyphicon glyphicon-user"></span>',
                            'suborder' => 4
                        ),
                    ),
                    'order' => 2
                ),
                'Delivered Orders' => array (
                    'title' => 'Delivered Orders',
                    'icon_template' => '<span class="glyphicon glyphicon-ok"></span>',
                    'child_sub_menu' => array (
                        'delivered_orders' => 
                        array (
                            'title' => 'Delivered Orders',
                            'icon_template' => '<span class="glyphicon glyphicon-ok"></span>',
                            'suborder' => 2,
                        ),
                    ),
                    'order' => 7
                ),
                'Processing Orders' => array (
                    'title' => 'Processing Orders',
                    'icon_template' => '<span class="glyphicon glyphicon-cog"></span>',
                    'child_sub_menu' => array (
                        'processing_orders' => 
                        array (
                            'title' => 'Processing Orders',
                            'icon_template' => '<span class="glyphicon glyphicon-cog"></span>',
                            'suborder' => 2,
                        ),
                    ),
                    'order' => 4
                ),
                'Pending Orders' => array (
                    'title' => 'Pending Orders',
                    'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                    'child_sub_menu' => array (
                        'pending_orders' => 
                        array (
                            'title' => 'Pending Orders',
                            'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                            'suborder' => 1
                        ),
                    ),
                    'order' => 3
                ),
                'Assigned Orders' => array (
                    'title' => 'Assigned Orders',
                    'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                    'child_sub_menu' => array (
                        'assingned_orders' => array (
                            'title' => 'Assigned Order',
                            'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                            'suborder' => 2
                        )
                    ),
                    'order' => 5
                ),
                'Out For Delivery' => array (
                    'title' => 'Out For Delivery Orders',
                    'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                    'child_sub_menu' => array (
                        'out_for_delivery_orders' => 
                        array (
                            'title' => 'Out For Delivery Orders',
                            'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
                            'suborder' => 1
                        )
                    ),
                    'order' => 6
                )
            );
            $menus = merged_menus($menus, $delivery_menu);
        }

    }   
}
    