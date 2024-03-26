<?php

namespace Moox\Press\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Moox\Press\Database\Factories\WpUserFactory;

class WpUser extends Authenticatable implements FilamentUser
{
    use HasFactory;

    public function userMeta()
    {
        return $this->hasMany(WpUserMeta::class, 'user_id');
    }

    protected $fillable = [
        'user_login',
        'user_pass',
        'user_nicename',
        'user_email',
        'user_url',
        'user_registered',
        'user_activation_key',
        'user_status',
        'display_name',

    ];

    protected $searchableFields = ['*'];

    protected $wpPrefix;

    protected $table;

    protected $primaryKey = 'ID';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->wpPrefix = config('press.wordpress_prefix');
        $this->table = $this->wpPrefix.'users';
    }

    public $timestamps = false;

    protected $casts = [
        'user_registered' => 'datetime',
        'spam' => 'boolean',
        'deleted' => 'boolean',
    ];

    // Accessor for "name" attribute
    public function getNameAttribute()
    {
        return $this->attributes['user_login'];
    }

    // Accessor for "email" attribute
    public function getEmailAttribute()
    {
        return $this->attributes['user_email'];
    }

    // Accessor for "password" attribute
    public function getPasswordAttribute()
    {
        return $this->attributes['user_pass'];
    }

    protected static function newFactory(): Factory
    {
        return WpUserFactory::new();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
