<?php
global $authUser;
$menus = array (
    'Master' => array (
        'title' => 'Master',
        'icon_template' => '<span class="fa fa-dashboard fa-fw"></span>',
        'child_sub_menu' => array (
            'translations' => array(
                'title' => 'Translations',
                'icon_template' => '<span class="fa fa-language"></span>',
                'link' => '/translations/all',
                'suborder' => 11
            ) ,
        ),
        'order' => 7
    ),
);
if ($authUser['role_id'] != Constants\ConstUserTypes::ADMIN) {
  unset($menus['Master']);
}