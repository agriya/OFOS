<?php
global $authUser;
$menus = array ();

$tables = array (
  'restaurant_delivery_persons' => 
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
          'label' => 'Delivery Person'
        ),
        2 => 
        array (
          'name' => 'restaurant_supervisor.user.username',
          'label' => 'Supervisor'
        ),
        5 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        6 => array (
            'name' => 'created_at',
            'label' => 'Created On',
              'suborder' => 1
        ),
      ),
      'title' => 'Delivery Persons',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'delete',
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
          'name' => 'restaurant_id',
          'label' => 'Shop',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        2 => 
        array (
          'name' => 'restaurant_branch_id',
          'label' => 'Shop Branch',
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
          'name' => 'restaurant_supervisor_id',
          'label' => 'Shop Supervisor',
          'targetEntity' => 'users',
          'targetField' => 'username',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        4 => 
        array (
          'name' => 'restaurant_delivery_persons.user_id',
          'label' => 'User',
          'targetEntity' => 'users',
          'targetField' => 'username',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        5 => array (
            'name' => 'restaurant_delivery_persons.is_active',
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
        0 => '<batch-actions resource="restaurant_delivery_persons" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
        1 => '<batch-actions resource="restaurant_delivery_persons" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
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
          'name' => 'restaurant_supervisor_id',
          'label' => 'Supevisor',
          'targetEntity' => 'users',
          'targetField' => 'username',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
          'permanentFilters' => array (
            'role_id' => \Constants\ConstUserTypes::SUPERVISOR,
            'supervisor' => 'site_supervisor'
          ),
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
      'title' => 'Create New Delivery Person'
    )
  ),
  'own_restaurant_delivery_persons' => 
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
          'label' => 'Delivery Person'
        ),
        2 => 
        array (
          'name' => 'restaurant_supervisor.user.username',
          'label' => 'Supervisor'
        ),
        3 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        4 => 
        array (
          'name' => 'restaurant_branch.name',
          'label' => 'Branch'
        ),
        5 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        6 => array (
            'name' => 'created_at',
            'label' => 'Created On',
              'suborder' => 1
        ),
      ),
      'title' => 'Restaurant Delivery Persons',
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
          'name' => 'restaurant_id',
          'label' => 'Shop',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        2 => 
        array (
          'name' => 'restaurant_branch_id',
          'label' => 'Shop Branch',
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
          'name' => 'restaurant_supervisor_id',
          'label' => 'Shop Supervisor',
          'targetEntity' => 'users',
          'targetField' => 'username',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        4 => 
        array (
          'name' => 'restaurant_delivery_persons.user_id',
          'label' => 'User',
          'targetEntity' => 'users',
          'targetField' => 'username',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        5 => array (
            'name' => 'restaurant_delivery_persons.is_active',
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
        0 => '<batch-actions resource="restaurant_delivery_persons" label="Deactive" icon="glyphicon-remove" action="inactive" selection="selection"></batch-actions>',
        1 => '<batch-actions resource="restaurant_delivery_persons" label="Active" icon="glyphicon-ok" action="active" selection="selection"></batch-actions>',
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
          'template' => '<load-restaurant-supervisors type="restaurant" action="edit" entry="entry" entity="entity"></load-restaurant-supervisors>'
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
      'title' => 'Create New Restaurant Delivery Person'
    ),
    'editionview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'restaurant_id',
          'label' => 'Shop',
          'template' => '<load-restaurant-supervisors type="restaurant" action="edit" entry="entry" entity="entity"></load-restaurant-supervisors>'
        )
      ),
      'title' => 'Edit Restaurant Delivery Person'
    )
  )
);
if (isPluginEnabled('Order/OutsourcedDelivery')) {
    $delivery_person = array (
        'Listing' => array (
          'title' => 'Listing',
          'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
          'child_sub_menu' => array (
              'restaurant_delivery_persons' => 
                array (
                  'title' => 'Delivery Persons',
                  'icon_template' => '<span class="glyphicon glyphicon-user"></span>',
                  'suborder' => 4
                )
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
                'own_restaurant_delivery_persons' =>
                array (
                    'title' => 'Restaurant Delivery Persons',
                    'icon_template' => '<span class="glyphicon glyphicon-user"></span>',
                    'suborder' => 5
                ),
            ),
            'order' => 4
        )
    );
    $menus = merged_menus($menus, $delivery_person);
}
if (!empty($authUser) && $authUser['role_id'] == \Constants\ConstUserTypes::RESTAURANT) {
  $tables['own_restaurant_delivery_persons']['creationview']['fields'] = array (
      0 => 
      array (
        'name' => 'restaurant_id',
        'label' => 'Shop',
        'template' => '<load-restaurant-branch-supervisor type="restaurant" action="edit" entry="entry" entity="entity"></load-restaurant-branch-supervisor>'
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
if (!empty($authUser) && $authUser['role_id'] == \Constants\ConstUserTypes::SUPERVISOR) {
  $tables['own_restaurant_delivery_persons']['creationview']['fields'] = array (
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
    $tables['restaurant_delivery_persons']['creationview']['fields'] = array (
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
if($authUser['role_id'] == \Constants\ConstUserTypes::SUPERVISOR) {
  $tables['restaurant_delivery_persons']['listview']['listActions'] = array ( 0 => 'delete');
}