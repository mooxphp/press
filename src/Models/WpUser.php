<?php

namespace Moox\Press\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Moox\Press\Database\Factories\WpUserFactory;

/**
 * @property int $ID
 * @property string $user_login
 * @property string $user_nicename
 * @property string $user_email
 */
class WpUser extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use Notifiable;

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

    protected $metatable;

    protected $primaryKey = 'ID';

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->wpPrefix = config('press.wordpress_prefix');
        $this->table = $this->wpPrefix.'users';
        $this->metatable = $this->wpPrefix.'usermeta';
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->addOrUpdateMeta('created_at', now()->toDateTimeString());
        });

        static::updated(function ($model) {
            $model->addOrUpdateMeta('updated_at', now()->toDateTimeString());
        });
    }

    protected $casts = [
        'user_registered' => 'datetime',
        'spam' => 'boolean',
        'deleted' => 'boolean',
    ];

    protected $appends = [
        'id', 'name', 'email', 'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at',
    ];

    public function getIdAttribute()
    {
        return $this->attributes['ID'];
    }

    public function getNameAttribute()
    {
        return $this->attributes['user_login'];
    }

    public function getEmailAttribute()
    {
        return $this->attributes['user_email'];
    }

    public function getPasswordAttribute()
    {
        return $this->attributes['user_pass'];
    }

    public function getRememberTokenAttribute()
    {
        return $this->getMeta('remember_token');
    }

    public function setRememberTokenAttribute($value)
    {
        $this->addOrUpdateMeta('remember_token', $value);
    }

    public function getEmailVerifiedAtAttribute()
    {
        return $this->getMeta('email_verified_at');
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->addOrUpdateMeta('email_verified_at', $value);
    }

    public function getCreatedAtAttribute()
    {
        return $this->getMeta('created_at');
    }

    public function getUpdatedAtAttribute()
    {
        return $this->getMeta('updated_at');
    }

    protected static function newFactory(): Factory
    {
        return WpUserFactory::new();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function userMeta()
    {
        return $this->hasMany(WpUserMeta::class, 'user_id');
    }

    protected function getMeta($key)
    {
        return DB::table($this->metatable)
            ->where('user_id', $this->ID)
            ->where('meta_key', $key)
            ->value('meta_value');
    }

    protected function addOrUpdateMeta($key, $value)
    {
        $exists = DB::table($this->metatable)
            ->where('user_id', $this->ID)
            ->where('meta_key', $key)
            ->exists();

        if ($exists) {
            DB::table($this->metatable)
                ->where('user_id', $this->ID)
                ->where('meta_key', $key)
                ->update(['meta_value' => $value]);
        } else {
            DB::table($this->metatable)
                ->insert([
                    'user_id' => $this->ID,
                    'meta_key' => $key,
                    'meta_value' => $value,
                ]);
        }
    }
}
