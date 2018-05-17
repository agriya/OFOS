<?php
$menus = array (
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
        'order' => 4
    )
);
$tables = array (
  'orders' => 
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
        8 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        ),
        9 => 
        array (
          'name' => 'delivered_date',
          'label' => 'Delivered On'
        ),
      ),
      'title' => 'Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<delivery-person ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == true" entry="entry" entity="entity" type="site" size="sm" label="Assign" ></delivery-person>',
        2 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == false" entry="entry" entity="entity"></change-status>',
        3 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PENDING .'|| entry.values.order_status_id == ' .\Constants\OrderStatus::AWAITINGCODVALIDATION.'" entry="entry" entity="entity"></change-status>'
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
          'name' => 'order_status_id',
          'label' => 'Order Status',
          'targetEntity' => 'order_statuses',
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        11 => 
        array (
          'name' => 'delivered_date',
          'label' => 'Delivered On',
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ),
  'payment_gateways' => array (
      'listview' => array (
          'fields' => array (
              0 => array (
                  'name' => 'name',
                  'label' => 'Name',
              ),
              1 => array (
                  'name' => 'is_test_mode',
                  'label' => 'Test Mode',
                  'type' => 'boolean'
              ),
          ),
          'title' => 'Payment Gateways',
          'perPage' => '10',
          'sortField' => '',
          'sortDir' => '',
          'infinitePagination' => false,
          'listActions' => array (
              0 => '<ma-edit-button ng-if="entry.values.id == '.\Constants\PaymentGateways::SUDOPAY.' || entry.values.id == '.\Constants\PaymentGateways::PAYPAL.' " entry="entry" entity="entity" size="sm" label="Edit" ></ma-edit-button>',
          ),
          'permanentFilters' => '',
          'actions' => array (
              0 => 'filter',
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
                  'name' => 'display_name',
                  'label' => 'Display Name',
                  'type' => 'string',
                  'validation' => array (
                      'required' => true,
                  ),
              ),
              2 => array (
                  'name' => 'slug',
                  'label' => 'Slug',
                  'type' => 'string',
                  'validation' => array (
                      'required' => false,
                  ),
              ),
              3 => array (
                  'name' => 'description',
                  'label' => 'Description',
                  'type' => 'text',
                  'validation' => array (
                      'required' => true,
                  ),
              ),
              4 => array (
                  'name' => 'gateway_fees',
                  'label' => 'Gateway Fees',
                  'type' => 'number',
                  'validation' => array (
                      'required' => true,
                  ),
              ),
              5 => array (
                  'name' => 'is_test_mode',
                  'label' => ' Test Mode?',
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
              6 => array (
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
              7 => array (
                  'name' => 'is_enable_for_wallet',
                  'label' => 'Is Enable For Wallet',
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
          ),
      ),
      'editionview' => array (
          'fields' => array (
              0 => array (
                  'name' => 'name',
                  'label' => 'Name',
                  'editable' => false,
              ),
              1 => array (
                  'name' => 'description',
                  'label' => 'description',
                  'editable' => false,
              ),
              2 => array (
                  'name' => 'is_test_mode',
                  'label' => '',
                  'template' => '<payment-gateway entry="entry" entity="entity" label="Edit"></payment-gateway>',
              ),
          ),
          'title' => 'Edit Payment Gateway',
          'actions' => array ('list')
      ),
  ),
  'transactions' => array (
      'listview' => array (
          'fields' => array (
              0 => 
              array (
                  'name' => 'id',
                  'label' => 'ID',
                  'isDetailLink' => true,
              ),
              2 => 
              array (
                  'name' => 'user.username',
                  'label' => 'From'
              ),
              3 => 
              array (
                  'name' => 'other_user.username',
                  'label' => 'To'
              ),
              4 => 
              array (
                  'name' => 'restaurant.name',
                  'label' => 'Shop'
              ),
              5 => 
              array (
                  'name' => 'description',
                  'label' => 'Description'
              ),
              6 => 
              array (
                  'name' => 'credit_amount',
                  'label' => 'Credit'
              ),
              7 => 
              array (
                  'name' => 'debit_amount',
                  'label' => 'Debit'
              ),
              6 => array (
                  'name' => 'created_at',
                  'label' => 'Created On',
                    'suborder' => 1
              ),
          ),
          'title' => 'Transactions',
          'perPage' => '10',
          'sortField' => '',
          'sortDir' => '',
          'infinitePagination' => false,
          'listActions' => '',
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
                  'label' => 'From User',
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
                  'name' => 'other_user_id',
                  'label' => 'To User',
                  'targetEntity' => 'users',
                  'targetField' => 'username',
                  'map' => 
                  array (
                  0 => 'truncate',
                  ),
                  'type' => 'reference',
                  'remoteComplete' => true,
              ),
              3 => 
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
              )
          ),
          'permanentFilters' => '',
          'actions' => 
          array (
              0 => 'batch',
              1 => 'filter'
          ),
      ),
  ),
  'own_restaurant_delivery_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
        8 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        ),
        9 => 
        array (
          'name' => 'delivered_date',
          'label' => 'Delivered On'
        ),
      ),
      'title' => 'Restaurant Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<delivery-person ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == true" entry="entry" type="restaurant" entity="entity" size="sm" label="Assign" ></delivery-person>',
        2 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == false" entry="entry" entity="entity"></change-status>',
        3 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PENDING .'|| entry.values.order_status_id == ' .\Constants\OrderStatus::AWAITINGCODVALIDATION.'" entry="entry" entity="entity"></change-status>'
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
          'name' => 'order_status_id',
          'label' => 'Order Status',
          'targetEntity' => 'order_statuses',
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        11 => 
        array (
          'name' => 'delivered_date',
          'label' => 'Delivered On',
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ),
  'assingned_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
        8 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        ),
      ),
      'title' => 'Assigned Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<out-for-delivery ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::DELIVERYPERSONASSIGNED . '" entry="entry" entity="entity"></out-for-delivery>'
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ), 
  'delivered_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
        8 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        ),
        9 => 
        array (
          'name' => 'delivered_date',
          'label' => 'Delivered On'
        ),
      ),
      'title' => 'Delivered Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<delivery-person ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == true" entry="entry" entity="entity" size="sm" label="Assign" ></delivery-person>',
        2 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == false" entry="entry" entity="entity"></change-status>',
        3 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PENDING . '" entry="entry" entity="entity"></change-status>'
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        11 => 
        array (
          'name' => 'delivered_date',
          'label' => 'Delivered On',
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ),
  'own_restaurant_assingned_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
        8 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        )
      ),
      'title' => 'Restaurant Assigned Order',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<out-for-delivery ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::DELIVERYPERSONASSIGNED . '" entry="entry" entity="entity"></out-for-delivery>'
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ), 
  'own_restaurant_delivered_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
        8 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        ),
        9 => 
        array (
          'name' => 'delivered_date',
          'label' => 'Delivered On'
        ),
      ),
      'title' => 'Restaurant Delivered Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<delivery-person ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == true" entry="entry" entity="entity" size="sm" label="Assign" ></delivery-person>',
        2 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == false" entry="entry" entity="entity"></change-status>',
        3 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PENDING . '" entry="entry" entity="entity"></change-status>'
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        11 => 
        array (
          'name' => 'delivered_date',
          'label' => 'Delivered On',
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ),
  'processing_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        )
      ),
      'title' => 'Processing Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<delivery-person ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == true" entry="entry" entity="entity" size="sm" label="Assign" ></delivery-person>',
        2 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == false" entry="entry" entity="entity"></change-status>',
        3 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PENDING . '" entry="entry" entity="entity"></change-status>'
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ),    
  'own_restaurant_processing_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
      ),
      'title' => 'Restaurant Processing Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<delivery-person ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == true" entry="entry" entity="entity" size="sm" label="Assign" ></delivery-person>',
        2 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == false" entry="entry" entity="entity"></change-status>',
        3 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PENDING . '" entry="entry" entity="entity"></change-status>'
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ), 
  'pending_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
      ),
      'title' => 'Pending Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<delivery-person ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == true" entry="entry" entity="entity" size="sm" label="Assign" ></delivery-person>',
        2 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == false" entry="entry" entity="entity"></change-status>',
        3 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PENDING . '" entry="entry" entity="entity"></change-status>'
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
          'name' => 'order_status_id',
          'label' => 'Order Status',
          'targetEntity' => 'order_statuses',
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ),    
  'own_restaurant_pending_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
      ),
      'title' => 'Restaurant Pending Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<delivery-person ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == true" entry="entry" entity="entity" size="sm" label="Assign" ></delivery-person>',
        2 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == false" entry="entry" entity="entity"></change-status>',
        3 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PENDING . '" entry="entry" entity="entity"></change-status>'
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ),  
  'out_for_delivery_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
        8 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        )
      ),
      'title' => 'Out For Delivery Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<change-order-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::OUTFORDELIVERY . '" entry="entry" entity="entity"></change-order-status>'
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
          'name' => 'order_status_id',
          'label' => 'Order Status',
          'targetEntity' => 'order_statuses',
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        13 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        )
      ),
    ),
  ),    
  'own_restaurant_out_for_delivery_orders' => array (
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
          'label' => 'Username'
        ),
        2 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        ),
        3 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        4 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        5 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Status'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On',
        ),
        8 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        )
      ),
      'title' => 'Restaurant Out For Delivery Orders',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => '<change-order-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::OUTFORDELIVERY . '" entry="entry" entity="entity"></change-order-status>'
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
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'order_menus',
          'label' => '',
          'template' => '<display-order entry="entry" entity="entity"></display-order>'
        ),
        1 => 
        array (
          'name' => 'created_at',
          'label' => 'Ordered On'
        ),
        2 => 
        array (
          'name' => 'user.username',
          'label' => 'Username'
        ),
        3 => 
        array (
          'name' => 'total_price',
          'label' => 'Amount',
        ),
        4 => 
        array (
          'name' => 'is_pickup_or_delivery',
          'label' => 'Delivery/Pickup ',
          'template' => '<pickup-or-delivery entry="entry" entity="entity"></pickup-or-delivery>'
        ),
        5 => 
        array (
          'name' => 'address',
          'label' => 'Address',
        ),
        6 => 
        array (
          'name' => 'city.name',
          'label' => 'City'
        ),
        7 => 
        array (
          'name' => 'state.name',
          'label' => 'State'
        ),
        8 => 
        array (
          'name' => 'country.name',
          'label' => 'Country'
        ),
        9 => 
        array (
          'name' => 'order_status.name',
          'label' => 'Order Status'
        ),
        10 => 
        array (
          'name' => 'later_delivery_date',
          'label' => 'Later Deliver Date'
        ),
        11 => 
        array (
          'name' => 'delivery_person.user.username',
          'label' => 'Delivery Person',
        ),
        12 => 
        array (
          'name' => 'restaurant.name',
          'label' => 'Shop'
        )
      ),
    ),
  ),  
);
if (isPluginEnabled('Order/OwnDelivery')) {
    $delivery_person = array (
        'Order' => array (
            'title' => 'Order',
            'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
            'child_sub_menu' => array (
                'own_restaurant_delivery_orders' => 
                array (
                    'title' => 'Restaurant Orders',
                    'icon_template' => '<span class="fa fa-shopping-cart fa-fw"></span>',
                    'suborder' => 3
                ),
            ),
            'order' => 4
        )
    );
    $menus = merged_menus($menus, $delivery_person);
}
if (isPluginEnabled('Order/OutsourcedDelivery')) {
    $delivery_person = array (
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
          'order' => 4
      )
    );
    $menus = merged_menus($menus, $delivery_person);
}
global $authUser;
if ($authUser['role_id'] != \Constants\ConstUserTypes::ADMIN) {
  unset($menus['Order']);
}
if ($authUser['role_id'] == \Constants\ConstUserTypes::DELIVERYPERSON) {
  $tables['orders']['listview']['listActions'] = array (
    0 => 'show',
    1 => '<change-order-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::DELIVERYPERSONASSIGNED . '" entry="entry" entity="entity"></change-order-status>'
  );
}
if ($authUser['role_id'] == \Constants\ConstUserTypes::RESTAURANT) {
  $tables['orders']['listview']['listActions'] = array (
    0 => 'show'
  );
}
if ($authUser['role_id'] == \Constants\ConstUserTypes::SUPERVISOR) {
  $tables['orders']['listview']['listActions'] = array (
     0 => 'show',
    1 => '<delivery-person ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == true" entry="entry" entity="entity" size="sm" label="Assign" ></delivery-person>',
    2 => '<change-status ng-if="entry.values.order_status_id == ' . \Constants\OrderStatus::PROCESSING . ' && entry.values.is_pickup_or_delivery == false" entry="entry" entity="entity"></change-status>',
  );
}