<?php
$menus = array (
    'Payments' => array (
        'title' => 'Payments',
        'icon_template' => '<span class="glyphicon glyphicon-usd"></span>',
        'child_sub_menu' => array (
            'user_cash_withdrawals' => array (
                'title' => 'Withdrawals',
                'icon_template' => '<span class="fa fa-money fa-fw"></span>',
                'suborder' => 4
            ),
            /*'money_transfer_accounts' => array (
                'title' => 'Transfer Accounts',
                'icon_template' => '<span class="glyphicon glyphicon-record"></span>',
                'suborder' => 5
            ),*/
        ),
        'order' => 6
    ),
);
$tables = array (
    'user_cash_withdrawals' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'created_at',
                    'label' => 'Requested On',
                    'suborder' => 1
                ),
                1 => array (
                    'name' => 'user.username',
                    'label' => 'User',
                    'suborder' => 2
                ),
                2 => array (
                    'name' => 'amount',
                    'label' => 'Amount',
                    'suborder' => 3
                ),
                3 => array (
                    'name' => 'status',
                    'label' => 'Status',
                    'template' => '<p ng-if="entry.values.status === 0" height="42" width="42">Pending</p><p ng-if="entry.values.status === 1" height="42" width="42">Approved</p><p ng-if="entry.values.status === 2" height="42" width="42">Rejected</p>',
                    'suborder' => 8
                ),
            ),
            'title' => 'Withdrawals',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show'
            ),
            'batchActions' => array (
               '0' => 'delete'
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
                    'name' => 'status',
                    'label' => 'Status?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Pending',
                            'value' => 0
                        ),
                        1 => array (
                            'label' => 'Approved',
                            'value' => 1
                        ),
                        2 => array (
                            'label' => 'Rejected',
                            'value' => 2
                        )
                    ),
                ),
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'money_transfer_account_id',
                    'label' => 'Money Transfer Account',
                    'targetEntity' => 'money_transfer_accounts',
                    'targetField' => 'account',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                ),
                1 => array (
                    'name' => 'amount',
                    'label' => 'Amount',
                    'type' => 'number',
                    'validation' => array (
                        'required' => true,
                    ),
                ),
                2 => array (
                    'name' => 'remark',
                    'label' => 'Remark',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
            ),
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'money_transfer_account_id',
                    'label' => 'Account',
                    'targetEntity' => 'money_transfer_accounts',
                    'targetField' => 'account',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'editable' => false,
                    'suborder' => 1
                ),
                1 => array (
                    'name' => 'amount',
                    'label' => 'Amount',
                    'editable' => false,
                    'suborder' => 2
                ),
                2 => array (
                    'name' => 'remark',
                    'label' => 'Remarks',
                    'editable' => false,
                    'validation' => array (
                        'required' => false,
                    ),
                    'suborder' => 3
                ),
                3 => array (
                    'name' => 'status',
                    'label' => ' Status?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Pending',
                            'value' => 0
                        ),
                        1 => array (
                            'label' => 'Approved',
                            'value' => 1
                        ),
                        2 => array (
                            'label' => 'Rejected',
                            'value' => 2
                        )
                    ),
                    'suborder' => 4
                ),
            ),
            'title' => 'Edit User Cash Withdrawal',
            'actions' => array (
                0 => 'list',
                1 => 'delete'
            )
        ),
        'showview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'user.username',
                    'label' => 'Username',
                     'suborder' => 2
                ),
                2 => array (
                    'name' => 'money_transfer_account.account',
                    'label' => 'Account',
                    'type' => 'wysiwyg',
                     'suborder' => 3
                ),
                3 => array (
                    'name' => 'amount',
                    'label' => 'Amount',
                     'suborder' => 4
                ),
                4 => array (
                    'name' => 'remark',
                    'label' => 'Remark',
                     'suborder' => 5
                ),
                5 => array (
                    'name' => 'status',
                    'label' => ' Status',
                    'template' => '<p ng-if="entry.values.status === 0" height="42" width="42">Pending</p><p ng-if="entry.values.status === 1" height="42" width="42">Approved</p><p ng-if="entry.values.status === 2" height="42" width="42">Rejected</p>',
                     'suborder' => 6
                ),
                6 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                     'suborder' => 1
                ),
            ),
        ),
    ),
    'money_transfer_accounts' => array (
        'listview' => array (
            'fields' => array (
                1 => array (
                    'name' => 'user.username',
                    'label' => 'User'
                ),
                2 => array (
                    'name' => 'account',
                    'label' => 'Account',
                    'type' => 'wysiwyg'
                ),
                3 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ),
                4 => array (
                    'name' => 'is_primary',
                    'type' => 'boolean',
                    'label' => 'Primary Account?',
                ),
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
            'title' => 'Money Transfer Accounts List',
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
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ),
                1 => array (
                    'name' => 'filter',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => 'active',
                        ),
                        1 => array (
                            'label' => 'Inactive',
                            'value' => 'inactive',
                        ),
                    ),
                ),
                2 => array (
                    'name' => 'is_primary',
                    'label' => 'Primary?',
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
                3 => array (
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
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
            ),
        ),
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ),
                1 => array (
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'type' => 'reference',
                    'singleApiCall' => 'getUsers',
                    'isDetailLink' => false
                ),
                2 => array (
                    'name' => 'account',
                    'label' => 'Account',
                ),
                3 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ),
                4 => array (
                    'name' => 'is_primary',
                    'type' => 'boolean',
                    'label' => 'Primary?',
                ),
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
        ),
    ),
);
global $authUser;
if ($authUser['role_id'] != Constants\ConstUserTypes::ADMIN) {
  unset($tables['user_cash_withdrawals']['listview']['listActions']);
  unset($tables['user_cash_withdrawals']['listview']['actions']);
}
if ($authUser['role_id'] != Constants\ConstUserTypes::ADMIN) {
  unset($menus['Payments']);
}