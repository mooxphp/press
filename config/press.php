<?php

return [
    'navigation_sort' => 1901,

    'wordpress_path' => env('WP_PATH', '/public/wp'),
    'wordpress_prefix' => env('WP_PREFIX', 'wp_'),
    'wordpress_slug' => env('WP_SLUG', '/wp'),

    'ip_whitelist' => env('IP_WHITELIST', ''),

    'lock_wordpress_site' => env('LOCK_WP', false),
    'obscure_login_from_external' => env('HIDE_LOGIN', false),
    'enable_honey_pot' => env('HONEY_POT', false),
    'enable_forgot_password' => env('FORGOT_PASSWORD', true),
    'enable_mfa' => env('ENABLE_MFA', false),
    'enable_registration' => env('REGISTRATION', false),

    'user_capabilities' => [
        'Subscriber' => 'a:1:{s:10:"subscriber";b:1;}',
        'Administrator' => 'a:1:{s:13:"administrator";b:1;}',
    ],

    'default_user_meta' => [
        'nickname' => 'user_login',
        'first_name' => '',
        'rich_edit' => 'true',
        'capabilities' => 'Subscriber',
        'mm_sua_attachment_id' => '',
    ],
];
