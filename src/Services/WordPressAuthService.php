<?php

namespace Moox\Press\Services;

class WordPressAuthService
{
    protected $hasher;

    public function __construct()
    {
        require_once base_path('public/wp/wp-includes/class-phpass.php');
        $this->hasher = new \PasswordHash(8, true);
    }

    public function hashPassword($password)
    {
        return $this->hasher->HashPassword($password);
    }

    public function checkPassword($password, $hashedPassword)
    {
        return $this->hasher->CheckPassword($password, $hashedPassword);
    }
}
