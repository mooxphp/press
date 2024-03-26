<?php

$user_capabilities = [
    'Subscriber' => 'a:1:{s:10:"subscriber";b:1;}',
    'Administrator' => 'a:1:{s:13:"administrator";b:1;}',
];

return [
    'navigation_sort' => 1901,

    'wordpress_path' => env('WP_PATH', '/public/wp'),
    'wordpress_slug' => env('WP_SLUG', '/wp'),
    'wordpress_prefix' => env('WP_PREFIX', 'wp_'),

    /*
    'wp_database' => [
        'host' => env('_DB_HOST', 'localhost'),
        'name' => env('WP_DB_NAME', 'wordpress'),
        'user' => env('WP_DB_USER', 'root'),
        'password' => env('WP_DB_PASSWORD', ''),
    ],
    */
    $wpPrefix = env('WP_PREFIX', 'wp_'),

    'user_meta' => [
        'nickname' => 'user_login',
        'first_name' => true,
        'rich_edit' => 'true',
        $wpPrefix.'capabilities' => $user_capabilities['Subscriber'],
        'mm_sua_attachment_id' => '',

    ],

];
