<?php

namespace Moox\Press\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $ID
 * @property string $post_title
 * @property string $post_name
 * @property string $post_author
 * @property \Illuminate\Database\Eloquent\Collection|\Moox\Press\Models\WpPostMeta[] $meta
 */
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

    public function comment()
    {
        return $this->hasMany(WpComment::class, 'comment_post_ID');
    }

    protected $searchableFields = ['*'];

    protected $wpPrefix;

    protected $table;

    protected $metatable;

    public $timestamps = false;

    protected $appends;

    protected $primaryKey = 'ID';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->wpPrefix = config('press.wordpress_prefix');
        $this->table = $this->wpPrefix.'posts';
        $this->metatable = $this->wpPrefix.'postmeta';

        $this->appends = [
            'wichtig',
            'verantwortlicher',
            'gultig_bis',
            'turnus',
            'fruhwarnung',
        ];
    }

    protected $casts = [
        'post_date' => 'datetime',
        'post_date_gmt' => 'datetime',
        'post_modified' => 'datetime',
        'post_modified_gmt' => 'datetime',
        'wichtig' => 'boolean',
    ];

    public function scopeWichtig($query)
    {
        return $query->whereHas('meta', function ($query) {
            $query->where('meta_key', 'wichtig');
        });
    }

    public function getVerantwortlicherAttribute()
    {
        return $this->getMeta('verantwortlicher') ?? null;
    }

    public function setVerantwortlicherAttribute($value)
    {
        $this->addOrUpdateMeta('verantwortlicher', $value);
    }

    public function postMeta()
    {
        return $this->hasMany(WpPostMeta::class, 'post_id', 'ID');
    }

    public function meta()
    {
        return $this->hasMany(WpPostMeta::class, 'post_id', 'ID');
    }

    public function metaKey($key)
    {
        if (! Str::startsWith($key, $this->wpPrefix)) {
            $key = "{$this->wpPrefix}{$key}";
        }

        return $this->getMeta($key);
    }

    protected function getMeta($key)
    {
        $meta = $this->postMeta()->where('meta_key', $key)->first();

        return $meta ? $meta->meta_value : null;
    }

    protected function addOrUpdateMeta($key, $value)
    {
        $meta = $this->postMeta()->where('meta_key', $key)->first();

        if ($meta) {
            $meta->meta_value = $value;
            $meta->save();
        } else {
            $this->postMeta()->create([
                'meta_key' => $key,
                'meta_value' => $value,
            ]);
        }
    }

    public function author()
    {
        return $this->belongsTo(WpUser::class, 'post_author', 'ID');
    }
}
