<?php
global $authUser;
$menus = array (
     'Order' => array (
        'title' => 'Order',
        'icon_template' => '<span class="fa fa-plane fa-fw"></span>',
        'child_sub_menu' => array (
            'coupons' => 
            array (
                'title' => 'Coupons',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                'suborder' => 1
            )
        ),
        'order' => 4
    )
);
$tables = array (
  'coupons' => 
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
          'label' => 'User'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Restaurant'
        ),
        3 => 
        array (
          'name' => 'coupon_code',
          'label' => 'Coupon Code',
        ),
        4 => 
        array (
          'name' => 'discount',
          'label' => 'Discount',
        ),
        5 => 
        array (
          'name' => 'is_flat_discount_in_amount',
          'label' => 'Flat Discount In Amount?',
          'type' => 'boolean'
        ),
        6 => 
        array (
          'name' => 'no_of_quantity_allowed',
          'label' => 'No Of Quantity Allowed',
        ),
        7 => 
        array (
          'name' => 'no_of_quantity_used',
          'label' => 'No Of Quantity Used',
        ),
        8 => 
        array (
          'name' => 'validity_start_date',
          'label' => 'Validity Start Date',
        ),
        9 => 
        array (
          'name' => 'validity_end_date',
          'label' => 'Validity End Date',
        ),
        10 => 
        array (
          'name' => 'maximum_discount_amount',
          'label' => 'Maximum Discount Amount',
        ),
        11 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        12 => array (
            'name' => 'created_at',
            'label' => 'Created On',
              'suborder' => 1
        ),
      ),
      'title' => 'Coupons',
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
        2 => 
        array (
          'name' => 'restaurant_id',
          'label' => 'Restaurant',
          'targetEntity' => 'restaurants',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        4 => 
        array (
          'name' => 'is_active',
          'type' => 'choice',
          'label' => 'Active',
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Active',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'Inactive',
              'value' => false,
            ),
          ),
        ),
      ),
      'permanentFilters' => '',
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
          'label' => 'Restaurant',
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
          'name' => 'coupon_code',
          'label' => 'Coupon Code',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        3 => 
        array (
          'name' => 'discount',
          'label' => 'Discount',
          'type' => 'float',
          'defaultValue' => '0.0',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        4 => 
        array (
          'name' => 'is_flat_discount_in_amount',
          'label' => 'Flat Discount In Amount?',
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
          'name' => 'no_of_quantity_allowed',
          'label' => 'No Of Quantity Allowed',
          'type' => 'number',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        7 => 
        array (
          'name' => 'validity_start_date',
          'label' => 'Validity Start Date',
          'type' => 'date',
          'format' => 'yyyy-MM-dd',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        8 => 
        array (
          'name' => 'validity_end_date',
          'label' => 'Validity End Date',
          'type' => 'date',
          'format' => 'yyyy-MM-dd',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        9 => 
        array (
          'name' => 'maximum_discount_amount',
          'label' => 'Maximum Discount Amount',
          'type' => 'float',
          'defaultValue' => '0.0',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        10 => 
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
      'title' => 'Add Coupon'
    ),
    'editionview' => 
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
          'label' => 'Restaurant',
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
          'name' => 'coupon_code',
          'label' => 'Coupon Code',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        3 => 
        array (
          'name' => 'discount',
          'label' => 'Discount',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        4 => 
        array (
          'name' => 'is_flat_discount_in_amount',
          'label' => 'Flat Discount In Amount?',
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
          'name' => 'no_of_quantity_allowed',
          'label' => 'No Of Quantity Allowed',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        7 => 
        array (
          'name' => 'validity_start_date',
          'label' => 'Validity Start Date',
          'type' => 'date',
          'format' => 'yyyy-MM-dd',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        8 => 
        array (
          'name' => 'validity_end_date',
          'label' => 'Validity End Date',
          'type' => 'date',
          'format' => 'yyyy-MM-dd',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        9 => 
        array (
          'name' => 'maximum_discount_amount',
          'label' => 'Maximum Discount Amount',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        10 => 
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
      'title' => 'Edit Coupon'
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
          'label' => 'User'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Restaurant'
        ),
        3 => 
        array (
          'name' => 'coupon_code',
          'label' => 'Coupon Code',
        ),
        4 => 
        array (
          'name' => 'discount',
          'label' => 'Discount',
        ),
        5 => 
        array (
          'name' => 'is_flat_discount_in_amount',
          'label' => 'Flat Discount In Amount?',
          'type' => 'boolean'
        ),
        6 => 
        array (
          'name' => 'no_of_quantity_allowed',
          'label' => 'No Of Quantity Allowed',
        ),
        7 => 
        array (
          'name' => 'no_of_quantity_used',
          'label' => 'No Of Quantity Used',
        ),
        8 => 
        array (
          'name' => 'validity_start_date',
          'label' => 'Validity Start Date',
        ),
        9 => 
        array (
          'name' => 'validity_end_date',
          'label' => 'Validity End Date',
        ),
        10 => 
        array (
          'name' => 'maximum_discount_amount',
          'label' => 'Maximum Discount Amount',
        ),
        11 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        12 => array (
            'name' => 'created_at',
            'label' => 'Created On',
        ),
      ),
    ),
  ),
);
if ($authUser['role_id'] != \Constants\ConstUserTypes::ADMIN) {
  unset($menus['Order']);
}
