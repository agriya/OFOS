<?php
$menus = array ();

$tables = array (
  'restaurant_supervisors' => 
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
          'name' => 'user.username',
          'label' => 'Supervisor'
        ),
        3 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On'
        ),
        2 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
      ),
      'title' => 'Supervisors',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'edit',
        1 => 'delete',
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
        0 => '<batch-actions resource="restaurant_supervisors" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
        1 => '<batch-actions resource="restaurant_supervisors" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
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
          'name' => 'username',
          'label' => 'Username',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        2 => 
        array (
          'name' => 'email',
          'label' => 'Email',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        3 => 
        array (
          'name' => 'mobile',
          'label' => 'Mobile',
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
      ),
      'title' => 'Create New Shop Supervisor'
    ),
    'editionview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'restaurant_id',
          'label' => 'Shop',
          'template' => '<load-restaurant-branches entry="entry" entity="entity"></load-restaurant-branches>'
        ),
      ),
      'title' => 'Edit Restaurant Supervisor'
    ),
  ),
  'own_restaurant_restaurant_supervisors' => 
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
          'name' => 'user.username',
          'label' => 'Supervisor'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'restaurant_branch.name',
          'label' => 'Branch'
        ),
        5 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On'
        ),
        4 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
      ),
      'title' => 'Restaurant Supervisors',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'edit',
        1 => 'delete',
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
        0 => '<batch-actions resource="restaurant_supervisors" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
        1 => '<batch-actions resource="restaurant_supervisors" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
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
             'pattern' => '[a-z0-9_-]+'
          ),
          'template' => '<load-restaurant-branches entry="entry" entity="entity"></load-restaurant-branches>'
        ),
        1 => 
        array (
          'name' => 'username',
          'label' => 'Username',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        2 => 
        array (
          'name' => 'email',
          'label' => 'Email',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        3 => 
        array (
          'name' => 'mobile',
          'label' => 'Mobile',
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
      ),
      'title' => 'Create New Shop Supervisor'
    ),
    'editionview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'restaurant_id',
          'label' => 'Shop',
          'template' => '<load-restaurant-branches entry="entry" entity="entity"></load-restaurant-branches>'
        ),
      ),
      'title' => 'Edit Restaurant Supervisor'
    ),
  ),  
);
if (isPluginEnabled('Order/OutsourcedDelivery')) {
      $delivery_person = array (
          'Listing' => array (
            'title' => 'Listing',
            'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
            'child_sub_menu' => array (
                'restaurant_supervisors' => 
                array (
                    'title' => 'Supervisors',
                    'icon_template' => '<span class="fa fa-eye fa-fw"></span>',
                    'suborder' => 2
                ),
            ),
            'order' => 4
        )
      );
      $menus = merged_menus($menus, $delivery_person);
}
if (isPluginEnabled('Order/OwnDelivery')) {
      $delivery_person = array (
          'Listing' => array (
              'title' => 'Listing',
              'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
              'child_sub_menu' => array (
                  'own_restaurant_restaurant_supervisors' => 
                  array (
                      'title' => 'Restaurant Supervisors',
                      'icon_template' => '<span class="glyphicon glyphicon-user"></span>',
                      'suborder' => 3
                  ),
              ),
              'order' => 4
          )
      );
      $menus = merged_menus($menus, $delivery_person);
}
if ($authUser['role_id'] == \Constants\ConstUserTypes::RESTAURANT) {
  $tables['own_restaurant_restaurant_supervisors']['creationview']['fields'] = array (
      0 => 
      array (
        'name' => 'restaurant_branch_id',
        'label' => 'Branch',
        'targetEntity' => 'restaurants',
        'targetField' => 'name',
        'type' => 'reference',
        'validation' => 
        array (
          'required' => false,
            'pattern' => '[a-z0-9_-]+'
        ),
        'permanentFilters' => array (
          'parent_id' => $authUser->restaurant->id
        ),
        'remoteComplete' => true,
      ),
      1 => 
      array (
        'name' => 'username',
        'label' => 'Username',
        'validation' => 
        array (
          'required' => true,
        ),
      ),
      2 => 
      array (
        'name' => 'email',
        'label' => 'Email',
        'validation' => 
        array (
          'required' => true,
        ),
      ),
      3 => 
      array (
        'name' => 'mobile',
        'label' => 'Mobile',
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
    );
}
if ($authUser['role_id'] != \Constants\ConstUserTypes::ADMIN) {
  unset($menus['Listing']);
}