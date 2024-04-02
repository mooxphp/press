<?php

namespace Moox\Press\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WpTermMeta extends Model
{
    use HasFactory;

    protected $fillable = ['term_id', 'meta_key', 'meta_value'];

    protected $searchableFields = ['*'];

    protected $table = 'wp_termmeta';
}
