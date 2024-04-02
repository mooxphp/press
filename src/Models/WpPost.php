<?php

namespace Moox\Press\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count',
    ];

    protected $searchableFields = ['*'];

    protected $wpPrefix;

    protected $table;

    protected $primaryKey = 'ID';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->wpPrefix = config('press.wordpress_prefix');
        $this->table = $this->wpPrefix.'posts';
    }

    protected $casts = [
        'post_date' => 'datetime',
        'post_date_gmt' => 'datetime',
        'post_modified' => 'datetime',
        'post_modified_gmt' => 'datetime',
    ];

    public function postMeta()
    {
        return $this->hasMany(WpPostMeta::class, 'post_id');
    }
}
