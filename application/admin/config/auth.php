<?php
return [
    'auth_on'           =>  true,
    'auth_type'         =>  1,
    'auth_group'        =>  'auth_group',
    'auth_group_access' =>  'auth_group_access',
    'auth_rule'         =>  'auth_rule',
    'auth_user'         =>  'admin_user',
    'auth_user_id_field'=>  'id',

    'administrators'    =>  [1],
    'uncontrollers'     =>  [
        'admin/index/index'
        // 'admin/systemctl.control/login',
        // 'admin/systemctl.control/do_login',
        // 'admin/systemctl.control/do_logout',
        // 'admin/systemctl.control/captcha',
    ]
];
