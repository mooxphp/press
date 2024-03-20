<?php

namespace Moox\Press\Services;

class WpAuthService
{
    public function hashPassword($password)
    {
        return wp_hash_password($password);
    }

    public function checkPassword($password, $hashedPassword)
    {
        return wp_check_password($password, $hashedPassword);
    }

    public function attachUserMeta($userId, $key, $value)
    {
        return add_user_meta($userId, $key, $value);
    }
}
