<?php

namespace Moox\Press\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpCommentMeta extends Model
{
    use HasFactory;

    protected $fillable = ['comment_id', 'meta_key', 'meta_value'];

    protected $searchableFields = ['*'];

    protected $table = 'wp_commentmeta';
}
