<?php
$menus = array (
    'Performance' => array (
        'title' => 'Performance',
        'icon_template' => '<span class="glyphicon glyphicon-home"></span>',
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
$tables = array (
'restaurant_reviews' => 
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
          'label' => 'Customer Name'
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
        4 => 
        array (
          'name' => 'created_at',
          'label' => 'Reviewed Date'
        )
      ),
      'title' => 'Reviews',
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
          'name' => 'user_id',
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'creationview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'user_id',
          'label' => 'User',
          'targetEntity' => 'users',
          'targetField' => 'username',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => true,
          ),
          'remoteComplete' => true,
        ),
        1 => 
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
        2 => 
        array (
          'name' => 'order_id',
          'label' => 'Order',
          'targetEntity' => 'orders',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => true,
          ),
          'remoteComplete' => true,
        ),
        3 => 
        array (
          'name' => 'rating',
          'label' => 'Rating',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        4 => 
        array (
          'name' => 'message',
          'label' => 'Message',
          'type' => 'wysiwyg',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        5 => 
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
    ),
    'editionview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'created_at',
          'label' => 'Reviewed Date',
          'editable' => false
        ),
        1 => 
        array (
          'name' => 'user_id',
          'label' => 'Customer Name',
          'targetEntity' => 'users',
          'targetField' => 'username',
          'type' => 'reference',
          'editable' => false
        ),
        2 => 
        array (
          'name' => 'rating',
          'label' => 'Rating',
          'editable' => false,
          'template' => '<star-rating stars="{{entry.values.rating}}"></star-rating>'
        ),
        3 => 
        array (
          'name' => 'message',
          'label' => 'Message',
          'type' => 'text',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
      ),
      'title' => 'Edit Restaurant Review'
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'label' => 'ID'
        ),
        1 => 
        array (
          'name' => 'user.username',
          'label' => 'Customer Name'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'order.restaurant.name',
          'label' => 'Order'
        ),
        4 => 
        array (
          'name' => 'rating',
          'label' => 'Rating',
          'template' => '<star-rating stars="{{entry.values.rating}}"></star-rating>'          
        ),
        5 => 
        array (
          'name' => 'message',
          'label' => 'Message',
        ),
        6 => array (
            'name' => 'created_at',
            'label' => 'Created On',
        ),
      ),
    ),
  )
);
if ($authUser['role_id'] != \Constants\ConstUserTypes::ADMIN && $authUser['role_id'] != \Constants\ConstUserTypes::RESTAURANT) {
  unset($menus['Performance']);
}
if ($authUser['role_id'] == \Constants\ConstUserTypes::RESTAURANT) {
  $tables['restaurant_reviews']['listview']['listActions'] = array (
    0 => 'show'
  );
  $tables['restaurant_reviews']['showview']['actions'] = array (
    0 => 'list'
  );
}
